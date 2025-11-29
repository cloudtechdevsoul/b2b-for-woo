<?php

if ( ! defined('ABSPATH') ) { exit; }

// Wrapped to run on admin_init to ensure Settings API is available
add_action('admin_init', function(){
    if ( ! function_exists('add_settings_section') ) {
        // Load admin template functions if not loaded yet
        if ( defined('ABSPATH') && file_exists( ABSPATH . 'wp-admin/includes/template.php' ) ) {
            require_once ABSPATH . 'wp-admin/includes/template.php';
        }
    }


});
