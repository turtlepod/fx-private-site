<?php
/**
 * Plugin Name: f(x) Private Site
 * Plugin URI: http://genbu.me/plugins/fx-private-site/
 * Description: Set your site to member only. All visitor will need to login to view site.
 * Version: 1.0.1
 * Author: David Chandra Purnama
 * Author URI: http://shellcreeper.com/
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @author David Chandra Purnama <david@genbu.me>
 * @copyright Copyright (c) 2016, Genbu Media
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
**/

/* Do not access this file directly */
if ( ! defined( 'WPINC' ) ) { die; }

/* Constants
------------------------------------------ */

/* Set the version constant. */
define( 'FX_PRIVATE_SITE_VERSION', '1.0.1' );

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

