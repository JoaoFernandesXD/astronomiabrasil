<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    jQuery(document).ready(function ($) {
        $('#login-form').submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                data: {
                    action: 'custom_login', 
                    log: $('#user_login').val(),
                    pwd: $('#user_pass').val(),
                },
                success: function (response) {
                    if (response === 'success') {
                        window.location.href = '<?php echo home_url(); ?>';
                    } else {
                        $('.error-message').remove();
                        $('#login-form').prepend('<div class="alert alert-danger" role="alert">Usuário ou senha incorretos. Tente novamente.</div>');
                    }
                }
            });
        });
    });
</script>


<?php global $user_login;

	if ( is_user_logged_in() ) : ?>

<div class="text-center"> 
        <p class="text-muted mb-0">Você já está logado.</p>
        <h3 class="mb-4"><?php echo $user_login; ?></h3>
        <a class="btn btn-secondary px-5" href="<?php echo wp_logout_url( get_permalink() ); ?>">Sair</a>
    </div>
<?php
else :
?>
    <form id="login-form" action="<?php echo wp_login_url(); ?>" method="post">
	<div class="form-group">
        <label for="user_login">Nome de usuário:</label>
        <input type="text" name="log" id="user_login" class="form-control alt" />
</div>
	<div class="form-group">
        <label for="user_pass">Senha:</label>
        <input type="password" name="pwd" id="user_pass" class="form-control alt" />
	</div>

        <input type="submit" value="Entrar" class="btn btn-primary" />
        <input type="hidden" name="redirect_to" value="<?php echo home_url(); ?>" />
    </form>

    <hr class="ou my-4">

    <div class="text-center">
        <a href="#" class="btn btn-lg btn-success show-register">Criar nova conta</a>

    </div>
<?php
endif; ?> 