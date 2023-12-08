<div class="card news post-<?php the_ID(); ?>">
	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="cover">
		<?php if ( has_post_thumbnail() ) : ?>
			<img src="<?php echo the_post_thumbnail_url(); ?>">
		<?php else : ?>
			<img src="<?php bloginfo('template_directory'); ?>/assets/image/default-cover.png" alt="<?php the_title(); ?>" />
		<?php endif; ?>

		<?php
		$include_alert = get_post_meta(get_the_ID(), '_include_alert', true);

		if ($include_alert === 'on') { ?>
			<div class="live >">Em Alta</div>
		<?php } ?>

		<?php $category = get_the_category(); ?>
		<div class="cat <?php echo $category[0]->slug; ?>" data-toggle="tooltip" data-html="true" title="<?php echo $category[0]->name; ?>">
			<?php echo $category[0]->name; ?>
		</div>
	</a>

	<div class="card-body">
		<?php
		$cg_name = get_post_meta( get_the_ID(), 'cg_name', true );
		$cg_url = get_post_meta( get_the_ID(), 'cg_url', true );
		$cg_available = get_post_meta( get_the_ID(), 'cg_available', true );
		if( !empty( $cg_name) ) : ?>
			<div class="box <?php if($cg_available == 'nao') { echo "disabled"; } ?>" data-toggle="tooltip" data-html="true" title="<strong><?php echo esc_attr( get_post_meta( get_the_ID(), 'cg_name', true ) ); ?></strong>">
				<?php if( !empty( $cg_url) ) : ?>
					<img src="<?php echo esc_attr( get_post_meta( get_the_ID(), 'cg_url', true ) ); ?>">
				<?php else: ?>
					<img src="<?php echo get_template_directory_uri()?>/assets/image/what.png">
				<?php endif; ?>
			</div>
		<?php endif; ?>
		
		<h5 class="card-title mb-4"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php $texto = get_the_title(); echo custom_limited_title($texto, 45); ?></a></h5>
		<div class="card-text">
			<div class="user-avatar sm mr-2">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 24 ); ?>
			</div> 
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" data-toggle="tooltip" title="<?php echo get_the_author_meta('user_login'); ?>"><?php echo get_the_author(); ?></a> 
			<span class="ml-auto text-muted"> 
				<?php if (is_sticky()) { echo '<i class="fas fa-thumbtack fa-rotate-45 text-primary" data-toggle="tooltip" title="Fixo"></i>'; } ?>
				<a href="<?php the_permalink(); ?>#comments"><i class="fas fa-comment-alt ml-3 mr-1"></i> 
				<?php echo get_comments_number(); ?></a>
			</span>
		</div>
	</div>
</div>
