<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php setPostViews(get_the_ID()); ?>
	
	<section class="position-relative">
		<div class="container">
			<div class="reading-content size-b">

				<div class="d-flex">
					<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" class="user-avatar lg mr-3">
						<?php 
								$user_id = get_the_author_meta('ID');
								$avatar_url = get_user_meta($user_id, 'avatar_custom', true);
								echo '<img alt="" src="'. $avatar_url . '" srcset=""'. $avatar_url . '" class="avatar avatar-56 photo" height="56" width="56" decoding="async">';
								?>
					</a>

					<div class="w-100 d-flex flex-column flex-md-row">
						<div class="w-100">
							<h4 class="mb-1"><?php echo the_title() ?></h4>

							<div class="text-muted">
								por <strong><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" data-toggle="tooltip" title="<?php echo get_the_author_meta('user_login'); ?>"><?php echo get_the_author(); ?></a></strong>

								<?php global $current_user; wp_get_current_user();
									$author_id = get_the_author_meta('ID');
									$current_id = $current_user->ID;
									$is_editor = current_user_can('editor') || current_user_can('administrator');

									if ((is_user_logged_in() && $author_id === $current_id) || $is_editor) { ?>
										<span class="mx-1">·</span> <a class="text-primary text-link" data-toggle="modal" data-target="#editModal"> Editar</a>
									<?php } ?>
							</div>
						</div>
			
						<div class="the-btn mr-auto mr-md-5 mt-3 mt-md-0">
							<?php echo get_simple_likes_button( get_the_ID() ); ?>
						</div>

						<?php
							$term_list = wp_get_post_terms($post->ID, 'category_gallery');
							foreach($term_list as $term_single) {
								if ($term_single->slug === 'destaque') :
									echo "<div class='featured fix' data-toggle='tooltip' data-placement='bottom' title='Destaque'><i class='fas fa-star'></i></div>";
								endif;
							}
						?>
					</div>
				</div>
			</div>
		</div>
							
		<div class="img-gallery">
			<div class="reading-content size-b">
				<img src="<?php echo the_post_thumbnail_url(); ?>">
			</div>
		</div>
		<div class="container">
			<div class="reading-content size-b">
				<div class="row">
					<div class="col-md-8 pr-md-3">
						<?php if ( !empty( get_the_content() ) ): ?>
							<div class="section-text">
							 <?php the_content(); ?>
							</div>
						<?php else: ?>
							<div class="no-hr"></div>
						<?php endif; ?>

						<?php comments_template(); ?>
					</div>
					<div class="col-md-4 pl-md-3">
						<div class="galery-infos">
							<?php echo get_the_term_list( $post->ID, 'tags_gallery', '<div class="mb-2"><i class="fas fa-tag fa-fw mr-2"></i>', ', ', '</div>' ); ?>
							<div class="mb-2">
								<i class="fas fa-eye fa-fw mr-2"></i> <?php echo getPostViews(get_the_ID()); ?>
							</div>
							<div class="mb-2 sl-info">
								<?php echo get_simple_likes_button( get_the_ID() ); ?> <span class="ml-1">curtidas</span>
							</div>
							<div>
								<i class="fas fa-calendar fa-fw mr-2"></i> <?php echo get_the_date(); ?>
							</div>
						</div>

						<hr>

						<?php $author_id = get_the_author_meta('ID'); ?>
						<?php
							$args = array(
								'posts_per_page' => 4,
								'post__not_in' => array($post->ID),
								'post_type' => 'galeria',
								'order' => 'DESC',
								'author' => $author_id,
							);

							$query = new WP_Query( $args );
						?>
						<?php if ( $query->have_posts() ) : ?>
						<h6 class="mb-3"><a class="text-inherit" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">Mais de <span data-toggle="tooltip" title="<?php echo get_the_author_meta('user_login'); ?>"><?php echo get_the_author(); ?></span></a></h6>

						<div class="row">
							<?php while ( $query->have_posts() ) : $query->the_post(); ?>
								<div class="col-6">
									<?php
										set_query_var('list', 'list');
										get_template_part( 'template-parts/card', 'gallery')
									?>
								</div>
							<?php endwhile; ?>
						</div>

						<?php endif; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endwhile; endif; ?>

<?php get_footer(); ?>