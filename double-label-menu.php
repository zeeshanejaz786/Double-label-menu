<?php
/**
 * Plugin Name: Double Label Menu
 * Description: Adds a secondary label to Navigation menu items using the native description field.
 * Version: 1.0.3
 * Author: Zeeshan Qureshi
 * Requires at least: 6.2
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'DLM_VERSION', '1.0.0' );
define( 'DLM_PATH', plugin_dir_path( __FILE__ ) );
define( 'DLM_URL', plugin_dir_url( __FILE__ ) );

/**
 * Enqueue frontend styles
 */
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'double-label-menu',
        DLM_URL . 'assets/css/double-label-menu.css',
        [],
        DLM_VERSION
    );
});

/**
 * Enhance navigation link rendering
 */
add_filter( 'render_block', function ( $block_content, $block ) {

    if ( empty( $block['blockName'] ) || $block['blockName'] !== 'core/navigation-link' ) {
        return $block_content;
    }

    $label       = $block['attrs']['label'] ?? '';
    $description = $block['attrs']['description'] ?? '';

    // Only modify items that actually have a description
    if ( $description === '' ) {
        return $block_content;
    }

    // Build stacked label markup
    $stack = sprintf(
        '<span class="dlm-stack">
            <span class="dlm-primary">%s</span>
            <span class="dlm-secondary">%s</span>
        </span>',
        esc_html( $label ),
        esc_html( $description )
    );

    /**
     * Replace label text inside the anchor
     * This is safe because navigation-link output is predictable
     */
    $block_content = preg_replace(
        '/(<a[^>]*class="[^"]*wp-block-navigation-item__content[^"]*"[^>]*>)(.*?)(<\/a>)/s',
        '$1' . $stack . '$3',
        $block_content,
        1
    );

    // Add helper class to <li>
    $block_content = preg_replace(
        '/class="([^"]*wp-block-navigation-item[^"]*)"/',
        'class="$1 dlm-item has-description"',
        $block_content,
        1
    );

    return $block_content;

}, 10, 2 );
