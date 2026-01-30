<?php
defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function () {
    add_options_page(
        'Double Label Menu',
        'Double Label Menu',
        'manage_options',
        'double-label-menu',
        'dlm_settings_page'
    );
});

add_action( 'admin_init', function () {
    register_setting( 'dlm_settings_group', 'dlm_settings' );

    add_settings_section(
        'dlm_main',
        'Menu Configuration',
        '__return_null',
        'double-label-menu'
    );

    $fields = [
        'scope_selector' => 'Navigation Scope Selector (CSS)',
        'chevron_color'  => 'Chevron Color',
        'chevron_size'   => 'Chevron Size (px)',
        'chevron_offset' => 'Chevron Offset (px)',
        'breakpoint'     => 'Mobile Breakpoint (px)',
    ];

    foreach ( $fields as $key => $label ) {
        add_settings_field(
            $key,
            $label,
            'dlm_render_field',
            'double-label-menu',
            'dlm_main',
            [ 'key' => $key ]
        );
    }
});

function dlm_render_field( $args ) {
    $opts = get_option( 'dlm_settings', [] );
    $key  = $args['key'];

    echo '<input type="text" name="dlm_settings[' . esc_attr( $key ) . ']" value="' . esc_attr( $opts[ $key ] ?? '' ) . '" class="regular-text">';
}

function dlm_settings_page() {
    ?>
    <div class="wrap">
        <h1>Double Label Menu</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields( 'dlm_settings_group' );
                do_settings_sections( 'double-label-menu' );
                submit_button();
            ?>
        </form>
        <p>
            <strong>Scope example:</strong><br>
            <code>.site-header-nav</code> or <code>.wp-block-navigation</code>
        </p>
    </div>
    <?php
}
