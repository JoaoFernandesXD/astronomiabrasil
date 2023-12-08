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










require_once(TT_FUNCTIONS . '/init.php');