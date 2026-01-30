<?php
/**
 * Plugin Name: Double Label Menu
 * Description: Enhances the WordPress Navigation block with stacked labels (primary + secondary) and a CSS-driven submenu chevron.
 * Version: 1.0.0
 * Author: Zeeshan Ejaz
 */

defined( 'ABSPATH' ) || exit;

define( 'DLM_VERSION', '1.0.0' );
define( 'DLM_PATH', plugin_dir_path( __FILE__ ) );
define( 'DLM_URL', plugin_dir_url( __FILE__ ) );

/**
 * Enqueue frontend + editor styles
 */
function dlm_enqueue_assets() {

    $options = get_option( 'dlm_settings', [] );

    $scope = ! empty( $options['scope_selector'] )
        ? esc_attr( $options['scope_selector'] )
        : '.wp-block-navigation';

    wp_enqueue_style(
        'double-label-menu',
        DLM_URL . 'assets/css/double-label-menu.css',
        [],
        DLM_VERSION
    );

    /* Pass design + scope via CSS variables */
    $inline_css = "
        :root {
            --dlm-scope: {$scope};
            --dlm-chevron-color: " . ( $options['chevron_color'] ?? '#2E0F1E' ) . ";
            --dlm-chevron-size: " . ( $options['chevron_size'] ?? '8px' ) . ";
            --dlm-chevron-offset: " . ( $options['chevron_offset'] ?? '25px' ) . ";
            --dlm-breakpoint: " . ( $options['breakpoint'] ?? '1280px' ) . ";
        }
    ";

    wp_add_inline_style( 'double-label-menu', $inline_css );
}
add_action( 'enqueue_block_assets', 'dlm_enqueue_assets' );

/**
 * Admin settings
 */
require_once DLM_PATH . 'includes/settings.php';
