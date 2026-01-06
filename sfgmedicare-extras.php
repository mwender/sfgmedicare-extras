<?php
/**
 * Plugin Name:     SFG Medicare Extras
 * Plugin URI:      https://github.com/mwender/sfgmedicare-extras
 * Description:     Various extensions for the SFG Medicare website
 * Author:          TheWebist
 * Author URI:      https://mwender.com
 * Text Domain:     sfgmedicare-extras
 * Domain Path:     /languages
 * Version:         1.1.0
 *
 * @package         Sfgmedicare_Extras
 */

// Your code starts here.
$css_dir = ( stristr( site_url(), '.local' ) || SCRIPT_DEBUG )? 'css' : 'dist' ;
define( 'SFG_CSS_DIR', $css_dir );
define( 'SFG_DEV_ENV', stristr( site_url(), '.local' ) );
define( 'SFG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SFG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load Composer dependencies.
$plugin_autoload = SFG_PLUGIN_PATH . 'vendor/autoload.php';
$root_autoload   = dirname( SFG_PLUGIN_PATH, 4 ) . '/vendor/autoload.php';

if ( file_exists( $plugin_autoload ) ) {
  require_once $plugin_autoload;
} elseif ( file_exists( $root_autoload ) ) {
  require_once $root_autoload;
} else {
  add_action(
    'admin_notices',
    static function () {
      $class   = 'notice notice-error';
      $message = __( 'Missing required Composer libraries. Run `composer install` from the Bedrock project root.', 'sfgmedicare-extras' );

      printf(
        '<div class="%1$s"><p>%2$s</p></div>',
        esc_attr( $class ),
        esc_html( $message )
      );
    }
  );
}

// Load required files
require_once( SFG_PLUGIN_PATH . 'lib/fns/acf-json-save-point.php' );
require_once( SFG_PLUGIN_PATH . 'lib/fns/admin-custom-columns.php' );
require_once( SFG_PLUGIN_PATH . 'lib/fns/enqueues.php' );
require_once( SFG_PLUGIN_PATH . 'lib/fns/search.php' );
require_once( SFG_PLUGIN_PATH . 'lib/fns/shortcodes.php' );
require_once( SFG_PLUGIN_PATH . 'lib/fns/templates.php' );
require_once( SFG_PLUGIN_PATH . 'lib/fns/utilities.php' );

/**
 * Enhanced logging.
 *
 * @param      string  $message  The log message
 */
if( ! function_exists( 'uber_log' ) ){
  function uber_log( $message = null ){
    static $counter = 1;

    $bt = debug_backtrace();
    $caller = array_shift( $bt );

    if( 1 == $counter )
      error_log( "\n\n" . str_repeat('-', 25 ) . ' STARTING DEBUG [' . date('h:i:sa', current_time('timestamp') ) . '] ' . str_repeat('-', 25 ) . "\n\n" );
    error_log( "\n" . $counter . '. ' . basename( $caller['file'] ) . '::' . $caller['line'] . "\n" . $message . "\n---\n" );
    $counter++;
  }
}

