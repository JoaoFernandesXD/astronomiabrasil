<?php

define( 'TT_FUNCTIONS', get_template_directory() . '/inc' );

// Desativar Gravatar
add_filter('avatar_defaults', 'disable_gravatar');
function disable_gravatar($avatar_defaults) {
    $avatar_defaults['default'] = get_template_directory_uri() . '/images/default-avatar.png'; // Substitua pelo caminho da imagem padrão desejada
    $avatar_defaults['gravatar_default'] = $avatar_defaults['default'];
    unset($avatar_defaults['gravatar_rating']);
    unset($avatar_defaults['avatar_rating']);
    return $avatar_defaults;
}
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



require_once(TT_FUNCTIONS . '/init.php');