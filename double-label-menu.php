<?php
/**
 * Plugin Name: Double Label Menu
 * Description: Extends the core Navigation block to support stacked labels and controlled submenu chevrons.
 * Version: 1.0.0
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue frontend styles (safe hook).
 */
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'double-label-menu',
        plugin_dir_url( __FILE__ ) . 'style.css',
        [],
        '1.0.0'
    );
});

/**
 * Modify navigation link rendering.
 */
add_filter(
    'render_block_core/navigation-link',
    'dlm_render_navigation_link',
    10,
    2
);

function dlm_render_navigation_link( $block_content, $block ) {

    // Ensure attributes exist
    $attrs = isset( $block['attrs'] ) ? $block['attrs'] : [];

    $label       = isset( $attrs['label'] ) ? $attrs['label'] : '';
    $description = isset( $attrs['description'] ) ? $attrs['description'] : '';

    // If no description, do nothing
    if ( empty( $description ) ) {
        return $block_content;
    }

    /**
     * Build stacked label markup
     */
    $stacked_label = sprintf(
        '<span class="dlm-label-stack">
            <span class="dlm-label-primary">%s</span>
            <span class="dlm-label-secondary">%s</span>
        </span>',
        esc_html( $label ),
        esc_html( $description )
    );

    /**
     * Replace the label text inside the anchor/button
     */
    $block_content = preg_replace(
        '/<span class="wp-block-navigation-item__label">.*?<\/span>/s',
        '<span class="wp-block-navigation-item__label">' . $stacked_label . '</span>',
        $block_content,
        1
    );

    /**
     * Remove native submenu icon (we will inject our own)
     */
    $block_content = preg_replace(
        '/<span class="wp-block-navigation__submenu-icon">.*?<\/span>/s',
        '',
        $block_content
    );

    /**
     * Inject chevron INSIDE the clickable label
     */
    if ( strpos( $block_content, 'has-child' ) !== false ) {

        $chevron = '<span class="dlm-chevron" aria-hidden="true"></span>';

        $block_content = preg_replace(
            '/(<span class="dlm-label-stack">.*?<\/span>)/s',
            '$1' . $chevron,
            $block_content,
            1
        );
    }

    /**
     * Force mobile submenus closed by default
     */
    $block_content = str_replace(
        'aria-expanded="true"',
        'aria-expanded="false"',
        $block_content
    );

    /**
     * Add root marker class
     */
    if ( strpos( $block_content, 'dlm-item' ) === false ) {
        $block_content = preg_replace(
            '/wp-block-navigation-item/',
            'wp-block-navigation-item dlm-item',
            $block_content,
            1
        );
    }

    return $block_content;
}
add_action( 'enqueue_block_editor_assets', function () {
    wp_enqueue_script(
        'double-label-menu-editor',
        plugin_dir_url( __FILE__ ) . 'editor.js',
        [ 'wp-blocks', 'wp-element', 'wp-components', 'wp-compose', 'wp-editor', 'wp-hooks' ],
        '1.0.0',
        true
    );
});
wp_enqueue_style(
    'double-label-menu',
    plugin_dir_url( __FILE__ ) . 'assets/css/double-label-menu.css',
    [],
    '1.0.0'
);

