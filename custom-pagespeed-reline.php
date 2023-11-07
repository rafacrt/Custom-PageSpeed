<?php
/**
 * Plugin Name: Custom PageSpeed Reline
 * Description: Plugin Personalizado para Reline, Customização de velocidade
 * Version: 1.0
 * Author: Rafael Medeiros
 */

function wpassist_remove_block_library_css() {
    wp_dequeue_style('wp-block-library');
}

function wpassist_load_custom_scripts_styles() {
    // Carrega os scripts/styles no footer
    wp_enqueue_style('custom-color-overrides', get_template_directory_uri() . '/assets/css/custom-color-overrides.css', array(), '1.0', 'all');
    wp_enqueue_style('custom-twenttwentyone-style', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('elementor-icons', WP_PLUGIN_URL . '/elementor/assets/lib/eicons/css/elementor-icons.min.css', array(), '1.0', 'all');
}

add_action('wp_enqueue_scripts', 'wpassist_remove_block_library_css', 100);
add_action('wp_enqueue_scripts', 'wpassist_load_custom_scripts_styles', 200);
