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
    <div id="registration-response"></div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
    jQuery(document).ready(function ($) {
        // Configuração das regras de validação
        $('#registration-form').validate({
            rules: {
                user_cadastro: {
                    required: true,
                },
                user_password: {
                    required: true,
                },
                user_email: {
                    required: true,
                    email: true,
                },
            },
            messages: {
                user_cadastro: {
                    required: 'Campo obrigatório',
                },
                user_email: {
                    required: 'Campo obrigatório',
                    email: 'Digite um e-mail válido',
                },
            },
            submitHandler: function (form) {
                // Se a validação passar, envie os dados via AJAX
                var user_cadastro = $('#user_cadastro').val();
                var userEmail = $('#user_email').val();
                var user_password = $('#user_password').val();

                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url("admin-ajax.php"); ?>',
                    data: {
                        action: 'custom_register',
                        user_cadastro: user_cadastro,
                        user_email: userEmail,
                        user_password: user_password,
                    },
                    success: function (response) {
                        $('#registration-response').html(response);
                    },
                    error: function (error) {
                        console.log(error); // Exibe erros no console para depuração
                    }
                });
            }
        });
    });
</script>





    <form id="registration-form" action="" method="post">
    <div class="form-group">
            <label>Usuário</label>
            <input type="text" name="user_cadastro" id="user_cadastro" class="form-control alt" />
        </div>
        <div class="form-group">
            <label>E-mail</label>
            <input type="text" name="user_email" id="user_email" class="form-control alt" />
        </div>
        <div class="form-group">
        <label>Senha</label>
        <input type="password" name="user_password" id="user_password" class="form-control alt" />
    </div>
        <p class="text-muted">Uma confirmação de registro será enviada para você por e-mail.</p>
        <?php do_action('register_form'); ?>

        <div class="text-center text-muted mt-4">
            Já tem uma conta? <a href="#" class="text-link text-primary show-login">Entre agora</a>
        </div>
        <br>
        <input type="submit" name="register" class="btn btn-lg btn-block btn-success" value="Cadastre-se" />
    </form>
<?php endif; ?>
