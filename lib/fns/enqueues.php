<?php

namespace sfgmedicare\enqueues;

function enqueue_scripts(){
  // Our custom styles
  $css_filename = SFG_PLUGIN_PATH . 'lib/' . SFG_CSS_DIR . '/main.css';
  if( file_exists( $css_filename ) )
    wp_enqueue_style( 'sfgmedicare', SFG_PLUGIN_URL . 'lib/' . SFG_CSS_DIR . '/main.css', ['hello-elementor','elementor-frontend'], filemtime( $css_filename ) );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );

/**
 * Custom styles for the WP Admin
 */
function custom_admin_styles(){
  wp_enqueue_style( 'myndyou-admin-styles', SFG_PLUGIN_URL . 'lib/dist/admin.css', null, filemtime( SFG_PLUGIN_PATH . 'lib/dist/admin.css' ) );
}
add_action( 'admin_head', __NAMESPACE__ . '\\custom_admin_styles' );