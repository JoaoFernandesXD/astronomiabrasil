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
    <form action="<?php echo wp_login_url(); ?>" method="post">
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
        <a href="<?php echo home_url() ?>/registro" class="btn btn-lg btn-success show-register">Criar nova conta</a>

    </div>
<?php
endif; ?> 