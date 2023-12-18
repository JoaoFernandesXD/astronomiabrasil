<?php

define( 'TT_FUNCTIONS', get_template_directory() . '/inc' );


// Limitador de titulo
function custom_limited_title($text, $limit = 45) {
    // Verifica se o texto excede o limite
    if (mb_strlen($text) > $limit) {
        // Limita o texto e adiciona reticências
        $text = mb_substr($text, 0, $limit) . '...';
    }

    // Retorna o texto limitado
    return esc_html($text);
}

add_action('wp_ajax_custom_login', 'custom_login');
add_action('wp_ajax_nopriv_custom_login', 'custom_login');

// Login Ajax
function custom_login() {
    $creds = array(
        'user_login' => $_POST['log'],
        'user_password' => $_POST['pwd'],
        'remember' => true,
    );

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        echo 'error';
    } else {
        echo 'success';
    }

    die();
}

//Ajax cadastro
add_action('wp_ajax_custom_register', 'custom_register');
add_action('wp_ajax_nopriv_custom_register', 'custom_register');

function custom_register() {
    $user_login = sanitize_user($_POST['user_cadastro']);
    $user_email = sanitize_email($_POST['user_email']);
    $user_password_registro = $_POST['user_password'];

    // Verificar se o usuário já existe
    if (username_exists($user_login) || email_exists($user_email)) {
        echo '<div class="alert alert-danger">Erro: Este usuário ou e-mail já está em uso.</div>';
    } else {
        // Criar o novo usuário com senha
        $user_id = wp_create_user($user_login, $user_password_registro, $user_email);

        if (is_wp_error($user_id)) {
            echo '<div class="alert alert-danger">Erro: Houve um problema ao criar o usuário.</div>';
        } else {
            // Fazer login automaticamente após o registro
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            do_action('wp_login', $user_login);

            
            // Enviar e-mail de boas-vindas ou confirmação
            $subject = 'Bem-vindo ao Meu Site';
            $message = 'Olá ' . $user_login . ', obrigado por se cadastrar no Meu Site!';
            $headers = 'Content-Type: text/html; charset=UTF-8';
            
            wp_mail($user_email, $subject, $message, $headers);

            echo '<div class="alert alert-success">Cadastro realizado com sucesso! Você foi logado automaticamente.</div>';
            // Adicionar script de redirecionamento após 2 segundos
            echo '<script>
                    setTimeout(function() {
                        window.location.href = "' . home_url() . '";
                    }, 2000);
                 </script>';

        }
    }

    die();
}

// Adicionar metabox
function add_alert_metabox() {
    add_meta_box(
        'alert_metabox',
        'Incluir Alerta na Notícia',
        'display_alert_metabox',
        'post',
        'side',
        'high'
    );
}

function display_alert_metabox($post) {
    $include_alert = get_post_meta($post->ID, '_include_alert', true);
    ?>
    <label for="include_alert">
        <input type="checkbox" name="include_alert" id="include_alert" <?php checked($include_alert, 'on'); ?>>
        Incluir Alerta
    </label>
    <?php
}

function save_alert_metabox($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['include_alert'])) {
        update_post_meta($post_id, '_include_alert', 'on');
    } else {
        delete_post_meta($post_id, '_include_alert');
    }
}

add_action('add_meta_boxes', 'add_alert_metabox');
add_action('save_post', 'save_alert_metabox');

function schedule_alert_deactivation($post_id) {
    wp_schedule_single_event(time() + 3600, 'deactivate_alert_event', array($post_id));
}

add_action('publish_post', 'schedule_alert_deactivation');

function deactivate_alert_event_handler($post_id) {
    update_post_meta($post_id, '_include_alert', 'off');
}

add_action('deactivate_alert_event', 'deactivate_alert_event_handler');



function registrar_tipo_postagem_emblemas() {
    $args = array(
        'public' => true,
        'label'  => 'Emblemas',
        'supports' => array('title', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-awards', // Ícone do menu - altere conforme necessário
    );
    register_post_type('emblemas', $args);
}

add_action('init', 'registrar_tipo_postagem_emblemas');


function adicionar_metabox_emblema() {
    add_meta_box(
        'metabox_emblema',
        'Configurações do Emblema',
        'exibir_metabox_emblema',
        'emblemas',
        'normal',
        'high'
    );
}

function exibir_metabox_emblema($post) {
    // Recupera os valores dos campos salvos anteriormente, se existirem
    $imagem_emblema = get_post_meta($post->ID, '_imagem_emblema', true);
    $descricao_emblema = get_post_meta($post->ID, '_descricao_emblema', true);
    $preco_emblema = get_post_meta($post->ID, '_preco_emblema', true);
    $pontos_necessarios_emblema = get_post_meta($post->ID, '_pontos_necessarios_emblema', true);
    $disponibilidade_emblema = get_post_meta($post->ID, '_disponibilidade_emblema', true);
    $data_validade_emblema = get_post_meta($post->ID, '_data_validade_emblema', true);
    $categoria_emblema = get_post_meta($post->ID, '_categoria_emblema', true);
    $link_relacionado_emblema = get_post_meta($post->ID, '_link_relacionado_emblema', true);
    $nivel_dificuldade_emblema = get_post_meta($post->ID, '_nivel_dificuldade_emblema', true);
    ?>

    <label for="imagem_emblema">Imagem do Emblema:</label>
    <input type="text" name="imagem_emblema" id="imagem_emblema" class="widefat" value="<?php echo esc_url($imagem_emblema); ?>" />
    <p class="description">Insira a URL da imagem ou faça upload.</p>

    <label for="descricao_emblema">Descrição:</label>
    <textarea name="descricao_emblema" id="descricao_emblema" class="widefat"><?php echo esc_textarea($descricao_emblema); ?></textarea>

    <label for="preco_emblema">Preço do Emblema:</label>
    <input type="number" name="preco_emblema" id="preco_emblema" class="widefat" value="<?php echo esc_attr($preco_emblema); ?>" />
    <p class="description">Defina o preço do emblema em pontos.</p>

    <label for="pontos_necessarios_emblema">Pontos Necessários:</label>
    <input type="number" name="pontos_necessarios_emblema" id="pontos_necessarios_emblema" class="widefat" value="<?php echo esc_attr($pontos_necessarios_emblema); ?>" />

    <label for="disponibilidade_emblema">Disponibilidade:</label>
    <select name="disponibilidade_emblema" id="disponibilidade_emblema" class="widefat">
        <option value="disponivel" <?php selected($disponibilidade_emblema, 'disponivel'); ?>>Disponível</option>
        <option value="indisponivel" <?php selected($disponibilidade_emblema, 'indisponivel'); ?>>Indisponível</option>
    </select>

    <label for="data_validade_emblema">Data de Validade:</label>
    <input type="date" name="data_validade_emblema" id="data_validade_emblema" class="widefat" value="<?php echo esc_attr($data_validade_emblema); ?>" />
    <p class="description">Opcional: Defina a data em que o emblema expira.</p>

    <label for="categoria_emblema">Categoria:</label>
    <input type="text" name="categoria_emblema" id="categoria_emblema" class="widefat" value="<?php echo esc_attr($categoria_emblema); ?>" />

    <label for="link_relacionado_emblema">Link Relacionado:</label>
    <input type="text" name="link_relacionado_emblema" id="link_relacionado_emblema" class="widefat" value="<?php echo esc_url($link_relacionado_emblema); ?>" />
    <p class="description">Opcional: Insira um link relacionado ao emblema.</p>

    <label for="nivel_dificuldade_emblema">Nível de Dificuldade:</label>
    <input type="text" name="nivel_dificuldade_emblema" id="nivel_dificuldade_emblema" class="widefat" value="<?php echo esc_attr($nivel_dificuldade_emblema); ?>" />

    <?php
}

function salvar_metabox_emblema($post_id) {
    // Salva os valores dos campos quando a postagem é salva
    update_post_meta($post_id, '_imagem_emblema', sanitize_text_field($_POST['imagem_emblema']));
    update_post_meta($post_id, '_descricao_emblema', sanitize_textarea_field($_POST['descricao_emblema']));
    update_post_meta($post_id, '_preco_emblema', intval($_POST['preco_emblema']));
    update_post_meta($post_id, '_pontos_necessarios_emblema', intval($_POST['pontos_necessarios_emblema']));
    update_post_meta($post_id, '_disponibilidade_emblema', sanitize_text_field($_POST['disponibilidade_emblema']));
    update_post_meta($post_id, '_data_validade_emblema', sanitize_text_field($_POST['data_validade_emblema']));
    update_post_meta($post_id, '_categoria_emblema', sanitize_text_field($_POST['categoria_emblema']));
    update_post_meta($post_id, '_link_relacionado_emblema', sanitize_text_field($_POST['link_relacionado_emblema']));
    update_post_meta($post_id, '_nivel_dificuldade_emblema', sanitize_text_field($_POST['nivel_dificuldade_emblema']));
}


add_action('add_meta_boxes', 'adicionar_metabox_emblema');
add_action('save_post', 'salvar_metabox_emblema');

add_action('init', 'custom_avatar_meta_field');

function obter_emblemas_disponiveis() {
    $emblemas_disponiveis = array();

    $emblemas_query = new WP_Query(array(
        'post_type' => 'emblemas',
        'posts_per_page' => -1,
    ));

    while ($emblemas_query->have_posts()) : $emblemas_query->the_post();
        $preco_emblema = get_post_meta(get_the_ID(), '_preco_emblema', true);
        $status = get_post_meta(get_the_ID(), '_disponibilidade_emblema', true);
        $pontos = get_post_meta(get_the_ID(), '_pontos_necessarios_emblema', true);
        $imagem_emblema = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');

        $emblema = array(
            'ID' => get_the_ID(),
            'titulo' => get_the_title(),
            'preco' => $preco_emblema,
            'status' => $status,
            'pontos' => $pontos,
            'imagem' => $imagem_emblema,
        );

        $emblemas_disponiveis[] = $emblema;
    endwhile;

    wp_reset_postdata();

    return $emblemas_disponiveis;
}

function custom_avatar_meta_field() {
    register_meta('user', 'avatar_custom', array(
        'type' => 'string',
        'description' => 'Custom avatar URL for the user',
        'single' => true,
        'show_in_rest' => true,
    ));
}


// Adicione moedas ao perfil do usuário ao criar um novo usuário
function inicializar_moedas_novo_usuario($user_id) {
    update_user_meta($user_id, 'moedas_usuario', 0);
}
add_action('user_register', 'inicializar_moedas_novo_usuario');

// Função para obter o saldo de moedas de um usuário
function obter_saldo_moedas($user_id) {
    return get_user_meta($user_id, 'moedas_usuario', true);
}

// Função para atualizar o saldo de moedas após uma transação
function atualizar_saldo_moedas($user_id, $quantidade_moedas) {
    $saldo_atual = obter_saldo_moedas($user_id);
    $novo_saldo = $saldo_atual + $quantidade_moedas;
    
    update_user_meta($user_id, 'moedas_usuario', $novo_saldo);
}

// Adiciona uma ação para usuários autenticados e não autenticados
add_action('wp_ajax_upload_avatar', 'upload_avatar_callback');
add_action('wp_ajax_nopriv_upload_avatar', 'upload_avatar_callback');

function upload_avatar_callback() {
    check_ajax_referer('update-avatar-nonce', 'security');

    if (!empty($_FILES['avatar']['name'])) {
        // Configurações para a biblioteca de manipulação de arquivos
        $configuracoes_upload = array(
            'test_type' => false,
            'test_form' => false
        );

        // Manipula o upload da imagem
        $anexo = wp_handle_upload($_FILES['avatar'], $configuracoes_upload);

        if (!empty($anexo['url'])) {
            // Obtém o ID do usuário atual
            $user_id = get_current_user_id();

            // Atualiza a URL da imagem no perfil do usuário
            update_user_meta($user_id, 'avatar_custom', $anexo['url']); // Use 'avatar_custom' como chave do campo personalizado

            // Adicional: Atualiza o avatar padrão do WordPress (caso esteja usando)
            update_user_meta($user_id, 'wp_user_avatar', $anexo['url']);

            // Retorna a URL do avatar personalizado
            wp_send_json_success(array('data' => $anexo['url'], 'message' => 'Avatar atualizado com sucesso!'));
        } else {
            wp_send_json_error(array('message' => 'Erro ao fazer o upload da imagem.'));
        }
    } else {
        wp_send_json_error(array('message' => 'Nenhum arquivo enviado.'));
    }
}

// Adiciona ação para usuários autenticados e não autenticados
add_action('wp_ajax_upload_avatar', 'upload_avatar_callback');
add_action('wp_ajax_nopriv_upload_avatar', 'upload_avatar_callback');

// forum 

function getNivel($interacoes) {
    // Definindo os grupos, níveis, requisitos e imagens
    $grupos = array(
        'Aprendiz' => array('rgb(253,187,49)', array(0, 5, 10, 15, 20, 25, 30, 35, 40), 'https://dev.astronomiabrasil.com/wp-content/themes/hFansite-master/uploads/forum/aprendiz.png'),
        'Terráqueo' => array('rgb(0,134,171)', array(50, 60, 70, 80, 90, 100, 110, 120, 130, 140), 'https://dev.astronomiabrasil.com/wp-content/themes/hFansite-master/uploads/forum/terraqueo.png'),
        'Astronauta' => array('rgb(123,105,115)', array(150, 160, 175, 190, 200, 215, 230, 240, 250, 260), 'https://dev.astronomiabrasil.com/wp-content/themes/hFansite-master/uploads/forum/astronauta.png'),
        'Viajante' => array('rgb(153,54,54)', array(275, 285, 300, 315, 330, 345, 360, 370, 380, 400), 'https://dev.astronomiabrasil.com/wp-content/themes/hFansite-master/uploads/forum/viajante.png'),
        'Marciano' => array('rgb(133,215,39)', array(425, 435, 450, 460, 475, 500, 520, 540, 550, 575), 'https://dev.astronomiabrasil.com/wp-content/themes/hFansite-master/uploads/forum/marciano.png'),
        'Alienigena' => array('rgb(69,135,58)', array(600, 610, 625, 640, 650, 665, 680, 690, 700, 720), 'https://dev.astronomiabrasil.com/wp-content/themes/hFansite-master/uploads/forum/alienigena.png'),
        'Estrelado' => array('rgb(18,13,38)', array(725, 740, 760, 780, 800, 830, 855, 870, 890, 905), 'https://dev.astronomiabrasil.com/wp-content/themes/hFansite-master/uploads/forum/estrelado.png'),
        'Galactico' => array('rgb(0,0,0)', array(930, 940, 950, 960, 970, 980, 1030, 1080, 1100, 1130), 'https://dev.astronomiabrasil.com/wp-content/themes/hFansite-master/uploads/forum/galactico.png'),
        'Vortex' => array('rgb(92,39,254)', array(1150, 1160, 1175, 1200, 1225, 1250, 1260, 1275, 1300, 1325), 'https://dev.astronomiabrasil.com/wp-content/themes/hFansite-master/uploads/forum/vortex.png'),
        'SuperNova' => array('rgb(0,0,0)', array(1350, 1400, 1450, 1500, 1550, 1700, 1750, 1775, 1800, 1950), 'https://dev.astronomiabrasil.com/wp-content/themes/hFansite-master/uploads/forum/supernova.png'),
        'BigBang' => array('rgb(255,255,255)', array(2000, 2100, 2200, 2300), 'https://dev.astronomiabrasil.com/wp-content/themes/hFansite-master/uploads/forum/bigbang.png')
        // Adicione mais grupos conforme necessário
    );

    $nivel = 1;
    foreach ($grupos as $grupo => $config) {
        list($cor, $limites, $imagem) = $config;

        foreach ($limites as $limite) {
            if ($interacoes <= $limite) {
                // Exibir o ranking com base no nível, cor do grupo e imagem
                echo "<div class='rank' style='background-color: {$cor}; background-image: url({$imagem}); max-width: 100%;'>";
                echo "{$grupo}";
                echo "<div class='nv'>Nv. {$nivel}</div>";
                echo "</div>";

                echo "<div class='text-muted d-none d-md-block'>";
                echo "<i class='fas fa-comment-alt mr-2'></i> <strong>{$interacoes}</strong> Comentários";
                echo "</div>";

                return $nivel;
            }
            $nivel++;
        }
    }

    return 0; // Se não encontrar nenhum grupo
}

add_action('wp_ajax_load_more_topics', 'load_more_topics');
add_action('wp_ajax_nopriv_load_more_topics', 'load_more_topics');

function load_more_topics() {
    $paged = $_POST['page'];
    $numberOfListings = $_POST['number'];

    $listings = new WP_Query(array(
        'post_type' => 'forum',
        'posts_per_page' => $numberOfListings,
        'paged' => $paged,
    ));

    ob_start();

    if ($listings->have_posts()) {
        while ($listings->have_posts()) {
            $listings->the_post();
            echo '<div class="col">';
            get_template_part('template-parts/card', 'topic');
            echo '</div>';
        }
        wp_reset_postdata();
    } else {
        echo 'no_more'; // Indica que não há mais tópicos
    }

    $output = ob_get_clean();
    echo $output;
    exit();
}





require_once(TT_FUNCTIONS . '/init.php');