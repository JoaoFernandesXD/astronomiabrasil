<?php
global $user_login;

if (isset($_GET['login']) && $_GET['login'] == 'failed') : ?>
    <div class="alert alert-danger">Erro: Tente novamente!</div>
<?php endif;

if (is_user_logged_in()) : ?>
    <div class="text-center">
        <p class="text-muted mb-0">Você já está logado.</p>
        <h3 class="mb-4"><?php echo $user_login; ?></h3>
        <a class="btn btn-secondary px-5" href="<?php echo wp_logout_url(get_permalink()); ?>">Sair</a>
    </div>

<?php else : ?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
        $user_login = sanitize_user($_POST['user_login']);
        $user_email = sanitize_email($_POST['user_email']);

        // Verificar se os campos necessários foram preenchidos
        if (empty($user_login) || empty($user_email)) {
            echo '<div class="alert alert-danger">Erro: Preencha todos os campos.</div>';
        } else {
            // Verificar se o usuário já existe
            if (username_exists($user_login) || email_exists($user_email)) {
                echo '<div class="alert alert-danger">Erro: Este usuário ou e-mail já está em uso.</div>';
            } else {
                // Criar o novo usuário
                $user_id = wp_create_user($user_login, wp_generate_password(), $user_email);

                if (is_wp_error($user_id)) {
                    echo '<div class="alert alert-danger">Erro: Houve um problema ao criar o usuário.</div>';
                } else {
                    // Fazer login automaticamente após o registro
                    wp_set_current_user($user_id);
                    wp_set_auth_cookie($user_id);
                    do_action('wp_login', $user_login);

                    echo '<div class="alert alert-success">Cadastro realizado com sucesso! Você foi logado automaticamente.</div>';
                }
            }
        }
    }
    ?>

    <form action="" method="post">
        <div class="form-group">
            <label>Usuário</label>
            <input type="text" name="user_login" id="user_login" class="form-control alt" />
        </div>
        <div class="form-group">
            <label>E-mail</label>
            <input type="text" name="user_email" id="user_email" class="form-control alt" />
        </div>
        <p class="text-muted">Uma confirmação de registro será enviada para você por e-mail.</p>
        <?php do_action('register_form'); ?>
        <input type="submit" name="register" class="btn btn-lg btn-block btn-success" value="Cadastre-se" />

        <div class="text-center text-muted mt-4">
            Já tem uma conta? <a href="<?php echo home_url() ?>/entrar" class="text-link text-primary show-login">Entre agora</a>
        </div>
    </form>

<?php endif; ?>
