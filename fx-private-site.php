<?php
/**
 * Plugin Name: f(x) Private Site
 * Plugin URI: http://genbumedia.com/plugins/fx-private-site/
 * Description: Set your site to member only. All visitor will need to login to view site.
 * Version: 1.2.1
 * Author: David Chandra Purnama
 * Author URI: http://shellcreeper.com/
 * License: GPLv2 or later
 * Text Domain: fx-private-site
 * Domain Path: /languages/
**/

/* Do not access this file directly */
if ( ! defined( 'WPINC' ) ) { die; }

/* Constants
------------------------------------------ */

/* Set the version constant. */
define( 'FX_PRIVATE_SITE_VERSION', '1.2.1' );

/* Set the constant path to the plugin path. */
define( 'FX_PRIVATE_SITE_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

/* Set the constant path to the plugin directory URI. */
define( 'FX_PRIVATE_SITE_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );


/* Plugins Loaded
------------------------------------------ */

/* Load plugins file */
add_action( 'plugins_loaded', 'fx_private_site_plugins_loaded' );

/**
 * Load plugins file
 * @since 0.1.0
 */
function fx_private_site_plugins_loaded(){

	/* Language */
	load_plugin_textdomain( 'fx-private-site', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	/* Load Functions */
	require_once( FX_PRIVATE_SITE_PATH . 'includes/functions.php' );

	/* Load Settings */
	if( is_admin() ){
		require_once( FX_PRIVATE_SITE_PATH . 'includes/settings.php' );
		$fx_private_site_settings = new fx_Private_Site_Settings();
	}
}


/* Activation and Uninstall
------------------------------------------ */

/* Register activation hook. */
register_activation_hook( __FILE__, 'fx_private_site_activation' );


/**
 * Runs only when the plugin is activated.
 * @since 0.1.0
 */
function fx_private_site_activation() {

	set_transient( 'fx_private_site_activation_notice', "1", 5 );

	/* Uninstall plugin hook */
	register_uninstall_hook( __FILE__, 'fx_private_site_uninstall' );
}

	/* Add admin notice */
	add_action( 'admin_notices', 'fx_private_site_admin_notice' );

	/**
	 * Admin Notice on Activation.
	 * @since 0.1.0
	 */
	function fx_private_site_admin_notice(){
		$transient = get_transient( 'fx_private_site_activation_notice' );
		if( "1" === $transient ){
			?>
			<div class="updated notice is-dismissible">
				<p><?php echo sprintf( __( 'Navigate to <a href="%s">Reading Settings</a> to activate Private Site feature.', 'fx-private-site' ), admin_url( 'options-reading.php' ) . '#fx-private-site' ); ?></p>
			</div>
			<?php
			delete_transient( 'fx_private_site_activation_notice' );
		}
	}

/**
 * Uninstall Hook: Delete settings.
 */
function fx_private_site_uninstall(){
	delete_option( 'fx-private-site' );
}