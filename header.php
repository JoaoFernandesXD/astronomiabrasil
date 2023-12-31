<?php
	$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="<?php echo $theme; ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="wp-content/themes/hFansite-master/inc/assets/principal.js"></script>
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
								<?php
								$user_id = get_current_user_id();
								$avatar_url = get_user_meta($user_id, 'avatar_custom', true);
								echo '<img alt="" src="'. $avatar_url . '" srcset=""'. $avatar_url . '" class="avatar avatar-32 photo" height="32" width="32" decoding="async">';
								?>
								
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