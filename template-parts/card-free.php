<style>
#efeito-embreve {
    position: relative;
    filter: blur(5px); /* Aplica o desfoque ao elemento pai */
}

#efeito-embreve::before {
    content: "EM BREVE";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(255, 0, 0, 0.7); /* Cor de fundo do texto */
    color: #fff; /* Cor do texto */
    padding: 10px 20px; /* Espaçamento interno do texto */
    font-size: 24px; /* Tamanho da fonte do texto */
    border-radius: 5px; /* Bordas arredondadas */
    z-index: 999; /* Certifica-se de que o texto está na parte superior do elemento */
}

</style>
<div id="custom_widget_coisas-2 efeito-embreve" class="widget widget_custom_widget_coisas mb-4" style="filter: blur(5px);">
    <div class="section-title">
        <h3>Emblemas</h3>
    </div>
    <div class="row">
    <?php
       $emblemas_disponiveis = obter_emblemas_disponiveis();
    ?>
    
     <!-- Lista de Emblemas -->
     <?php foreach ($emblemas_disponiveis as $emblema) : ?>
                <div class="col-4 col-sm-3 col-lg-2">
    <div class="card free <?php echo ($emblema['status'] === 'indisponivel') ? 'disabled' : ''; ?> post-<?php echo $emblema['ID']; ?>">
        <a href="<?php echo get_permalink($emblema['ID']); ?>" title="<?php echo esc_html($emblema['titulo']); ?>">
            <div class="box pixel" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong><?php echo esc_html($emblema['titulo']); ?></strong>">
                <img src="<?php echo esc_url($emblema['imagem']); ?>" alt="<?php echo esc_attr($emblema['titulo']); ?>">
            </div>
        </a>
        <br>
        <center>
            <?php if ($emblema['status'] === 'disponivel') : ?>
                <div id="dropnew" class="btn btn-sm btn-warning">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
								<path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z"></path>
								<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"></path>
								<path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12"></path>
					</svg>
                    <?php echo ($emblema['pontos'] > 0) ? $emblema['pontos'] : 'Grátis'; ?>
                </div>
                <br>
				<br>
                <?php if ($emblema['status'] === 'disponivel' && $emblema['pontos'] == 0) : ?>
                    <input type="submit" value="Resgatar" class="btn btn-primary">
                <?php else : ?>
                    <input type="submit" value="Comprar" class="btn btn-success">
                <?php endif; ?>
            <?php else : ?>
                <p>Indisponível</p>
            <?php endif; ?>
        </center>
        <br>
    </div>
</div>


            <?php endforeach; 
wp_reset_postdata();
?>

    </div>
</div>
