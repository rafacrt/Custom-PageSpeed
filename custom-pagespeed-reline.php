<?php
/**
 * Plugin Name: Custom PageSpeed
 * Description: Plugin Personalizado focado em performance e otimizações.
 * Version: 1.8
 * Author: Rafael Medeiros
 */

// Remove CSS desnecessário do Gutenberg
function wpassist_remove_block_library_css() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'wpassist_remove_block_library_css', 100);

// Remove emojis
function wpassist_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
}
add_action('init', 'wpassist_disable_emojis');

// Remove o script de embed
function wpassist_disable_wp_embeds() {
    wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'wpassist_disable_wp_embeds');

// Remove jQuery se não for usado
function wpassist_remove_jquery() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'wpassist_remove_jquery', 99);

// Adiciona preload para fontes
function wpassist_preload_fonts() {
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/fonts/Inter-Regular.woff2" as="font" type="font/woff2" crossorigin>';
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/fonts/Inter-Bold.woff2" as="font" type="font/woff2" crossorigin>';
}
add_action('wp_head', 'wpassist_preload_fonts', 1);

// Lazy loading com JS para imagens antigas (caso não use loading="lazy")
function wpassist_lazyload_script() {
    ?>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const images = document.querySelectorAll("img:not([loading])");
        images.forEach(img => {
          img.setAttribute("loading", "lazy");
        });
      });
    </script>
    <?php
}
add_action('wp_footer', 'wpassist_lazyload_script');

// Carrega estilos e scripts no footer com defer
function wpassist_load_custom_scripts_styles() {
    wp_enqueue_style('custom-color-overrides', get_template_directory_uri() . '/assets/css/custom-color-overrides.css', array(), '1.0', 'all');
    wp_enqueue_style('custom-twentytwentyone-style', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('elementor-icons', WP_PLUGIN_URL . '/elementor/assets/lib/eicons/css/elementor-icons.min.css', array(), '1.0', 'all');

    wp_register_script('custom-scripts', get_template_directory_uri() . '/assets/js/custom-scripts.js', array(), '1.0', true);
    wp_enqueue_script('custom-scripts');
}
add_action('wp_enqueue_scripts', 'wpassist_load_custom_scripts_styles', 200);

// Adiciona defer em scripts selecionados
function wpassist_add_defer_to_scripts($tag, $handle, $src) {
    $defer_scripts = array('custom-scripts');

    if (in_array($handle, $defer_scripts)) {
        return '<script src="' . esc_url($src) . '" defer></script>';
    }

    return $tag;
}
add_filter('script_loader_tag', 'wpassist_add_defer_to_scripts', 10, 3);
