<?php

/**
 * Template Name: Lista Agencias
 */

get_header();
?>

<div class="jumbotron jumbotron-fluid orange">
	<div class="container">
		<h1 class="mb-0"><?php echo the_title() ?></h1>
	</div>
</div>
<section>
<div class="container">
        <div class="launch-list-agencias">
        <?php
            $args = array(
                'posts_per_page' => 999,
                'post_type' => 'agencias',
                'order' => 'DESC',
            );

            $query = new WP_Query($args);

            if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
                $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full') ?: 'URL_da_imagem_padrao_para_capa.jpg';
                $logo_id = get_post_meta(get_the_ID(), 'logo_image_id', true);
                $logo_url = wp_get_attachment_url($logo_id) ?: 'URL_da_imagem_padrao_para_logo.jpg';
                $category = get_post_meta(get_the_ID(), 'agency_type', true) ?: 'Categoria Padrão';
                $country = get_post_meta(get_the_ID(), 'country_code', true) ?: 'País Padrão';
                $description = get_the_content() ?: 'Descrição padrão da agência.';
            ?>

            <div class="launch-card-agencias">
                <div class="launch-cover">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>" />
                    <div class="logo-container">
                        <img src="<?php echo esc_url($logo_url); ?>" alt="Logo da Agência" />
                    </div>
                    <div class="launch-category"><?php echo esc_html($category); ?></div>
                    <div class="launch-status"><?php echo esc_html($country); ?></div>
                </div>
                <div class="launch-body">
                    <div class="agency-description">
                        <p><?php echo esc_html($description); ?></p>
                    </div>
                    <div class="read-more-button">
                        <a href="<?php the_permalink(); ?>" class="btn">Ler Mais</a>
                    </div>
                </div>
            </div>

            <?php endwhile; else: ?>
            <div class="card">
                <div class="card-body text-center text-muted">
                    <strong>Nenhuma Agência encontrada!</strong>
                </div>
            </div>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>


</section>



<?php get_footer(); ?>