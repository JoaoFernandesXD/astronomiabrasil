<style>
    .blur-container {
    filter: blur(5px); /* Ajuste o valor de acordo com o nível de desfoque desejado */
}

</style>

<div id="custom_widget_coisas-2" class="widget widget_custom_widget_coisas mb-4 ">
    <div class="section-title">
        <h3>Emblemas</h3>
    </div>
    <div class="row">
    <?php
        $emblemas_query = new WP_Query(array(
            'post_type' => 'emblemas',
            'posts_per_page' => -1,
        ));

        while ($emblemas_query->have_posts()) : $emblemas_query->the_post();
            $preco_emblema = get_post_meta(get_the_ID(), '_preco_emblema', true);
            $status = get_post_meta(get_the_ID(), '_disponibilidade_emblema', true);
            $pontos = get_post_meta(get_the_ID(), '_pontos_necessarios_emblema', true);
            $imagem_emblema = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
            ?>
    
    <div class="col-4 col-sm-3 col-lg-2">
        <div class="card free <?php echo ($status === 'indisponivel') ? 'disabled' : ''; ?> post-<?php the_ID(); ?>">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <div class="box pixel" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong><?php echo esc_html(get_the_title()); ?></strong>">
                    <img src="<?php echo esc_url($imagem_emblema); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                </div>
            </a>
            <br>
            <center>
                <?php if ($status === 'disponivel' && $preco_emblema == 0 && $pontos == 0) : ?>
                    <input type="submit" value="Resgatar" class="btn btn-primary">
                <?php else : ?>
                    <input type="submit" value="Comprar" class="btn btn-success">
                <?php endif; ?>
            </center>
            <br>
        </div>
    </div>
    <?php
endwhile;

wp_reset_postdata();
?>

    </div>
</div>
