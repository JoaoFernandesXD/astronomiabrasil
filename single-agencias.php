<?php
// Pega o cabeçalho do site
get_header();

// Inicia o Loop do WordPress
while ( have_posts() ) : the_post();

// Supondo que você tenha definido campos personalizados para esses valores
$ceo = get_post_meta(get_the_ID(), 'ceo', true);
$successful_launches = get_post_meta(get_the_ID(), 'successful_launches', true);
// Continue para os outros campos conforme necessário

?>
<div class="agencia-detalhes">
    <header class="agencia-header">
        <h1 class="agencia-title"><?php the_title(); ?></h1>
        <div class="agencia-meta">
            <span class="ceo">CEO: <?php echo esc_html($ceo); ?></span>
            <!-- Outros metadados da agência -->
        </div>
    </header>
    <div class="agencia-content">
        <?php the_content(); ?>
    </div>
    <!-- Adicione mais seções conforme necessário -->
</div>
<?php
endwhile; // Fim do Loop

// Pega o rodapé do site
get_footer();
?>
