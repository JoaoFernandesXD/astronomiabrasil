<?php

/**
 * Template Name: Recuperar senha
 */

if(!is_user_logged_in()){
	wp_redirect(home_url());
}

get_header();
?>

<section>
	<div class="container pt-3">
		<div class="row">
			<div class="col-md-4 offset-md-4">
				<?php if(have_posts()): while(have_posts()): the_post(); ?>
					<h3 class="mb-4"><?php the_title(); ?></h3>
					<?php the_content(); ?>
					
				<?php endwhile; endif; ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>