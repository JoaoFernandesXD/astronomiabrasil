<?php
	$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="<?php echo $theme; ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<nav class="navbar fixed-top navbar-expand-lg navbar-light">
		<a class="navbar-brand" href="<?php echo home_url() ?>">
			<img src="<?php echo home_url() ?>/wp-content/uploads/2023/12/logo.png" width="100px;">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'		 => false,
					'menu_class'	 => 'navbar-nav mr-auto',
					'walker'		 => new Bootstrap_NavWalker(),
					'fallback_cb'	 => 'Bootstrap_NavWalker::fallback',
				) );
			?>


			<div class="d-flex justify-content-center align-items-center ml-auto mt-3 mt-lg-0">
				<?php if ( is_user_logged_in() ): ?>
					<?php global $current_user; wp_get_current_user();?>
					<div class="dropdown mr-4">
						<div id="dropnew" class="btn btn-sm btn-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-plus mr-1"></i> <?php esc_html_e( 'Novo', 'hfansite' ); ?>
						</div>
						<div id="dropnew" class="btn btn-sm btn-warning">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
							<path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z"/>
							<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
							<path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12"/>
						</svg>
						<?php 
							// Obtém o saldo de moedas do usuário com ID 1
							$moedas_usuario = get_user_meta(get_current_user_id(), 'moedas_usuario', true);
							esc_html_e($moedas_usuario, 'hfansite' ); ?>
						</div>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropnew">
							<a class="dropdown-item" href="<?php echo home_url() ?>/novo-topico"><?php esc_html_e( 'Tópico', 'hfansite' ); ?></a>
							<a class="dropdown-item" href="<?php echo home_url() ?>/nova-arte"><?php esc_html_e( 'Galeria', 'hfansite' ); ?></a>
							<?php if(current_user_can('administrator')) { ?>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo admin_url('post-new.php') ?>"><?php esc_html_e( 'Noticias', 'hfansite' ); ?></a>
								<a class="dropdown-item" href="<?php echo admin_url('post-new.php?post_type=evento'); ?>"><?php esc_html_e( 'Lançamentos', 'hfansite' ); ?></a>
								<a class="dropdown-item" href="<?php echo admin_url('post-new.php?post_type=publicidade'); ?>"><?php esc_html_e( 'Publicidade', 'hfansite' ); ?></a>
							<?php } ?>
						</div>
					</div>

					<div class="dropdown">
						<div id="dropUser" class="d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<div class="user-avatar">
								<?php echo get_avatar( $current_user->id, 32 ); ?>
							</div>
						</div>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropUser">
							<a class="dropdown-item" href="<?php echo home_url() ?>/perfil/<?php echo $current_user->user_login ?>"><i class="fas fa-user text-muted mr-2"></i> <?php esc_html_e( 'Perfil', 'hfansite' ); ?></a>
							<label class="theme-switch dropdown-item" for="checkbox">
								<i class="fas fa-moon text-muted mr-2"></i> <?php esc_html_e( 'Modo Escuro', 'hfansite' ); ?>
								<div class="custom-control custom-switch ml-auto">
									<input type="checkbox" class="custom-control-input" id="checkbox" <?php echo $theme == 'dark' ? ' checked' : ''; ?>>
									<label class="custom-control-label" for="checkbox"></label>
								</div>
							</label>
							<a class="dropdown-item" href="<?php echo home_url() ?>/configuracoes"><i class="fas fa-cog text-muted mr-2"></i> <?php esc_html_e( 'Configurações', 'hfansite' ); ?></a>
							<a class="dropdown-item" href="<?php echo wp_logout_url( home_url() ); ?>"><i class="fas fa-sign-out-alt text-muted mr-2"></i> <?php esc_html_e( 'Sair', 'hfansite' ); ?></a>
						</div>
					</div>
				<?php else: ?>
					<div class="mr-4">
						<label class="theme-switch mb-0" for="checkbox">
							<i class="fas fa-moon text-muted"></i>
							<input class="d-none" type="checkbox" id="checkbox" <?php echo $theme == 'dark' ? ' checked' : ''; ?> />
						</label>
					</div>

					<a	href="/entrar" class="btn btn-primary btn-login" data-toggle="modal" data-target="#loginModal"><?php esc_html_e( 'Login', 'hfansite' ); ?></a>
				
				<?php endif; ?>

				<div class="search">
					<label for="search"><i class="fas fa-search"></i></label>

					<form action="<?php echo home_url() ?>/" method="get">
						<input type="text" name="s" id="search" value="<?php the_search_query(); ?>" placeholder="<?php esc_html_e( 'Pesquisar', 'hfansite' ); ?>" />
					</form>
				</div>
			</div>
		</div>
	</nav>

	<main>