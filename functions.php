<?php

define( 'TT_FUNCTIONS', get_template_directory() . '/inc' );

// Limitador de titulo
function custom_limited_title($text, $limit = 45) {
    // Verifica se o texto excede o limite
    if (mb_strlen($text) > $limit) {
        // Limita o texto e adiciona reticências
        $text = mb_substr($text, 0, $limit) . '...';
    }

    // Retorna o texto limitado
    return esc_html($text);
}

require_once(TT_FUNCTIONS . '/init.php');