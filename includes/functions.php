<?php
/**
 * f(x) Private Site Functions
 * @since 0.1.0
**/

/* Do not access this file directly */
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Get Option helper function
 * @since 0.1.0
 */
function fx_private_site_get_option( $option, $default = '', $option_name = 'fx-private-site' ) {

	/* Bail early if no option defined */
	if ( !$option ){
		return false;
	}

	/* Get database and sanitize it */
	$get_option = get_option( $option_name );

	/* if the data is not array, return false */
	if( !is_array( $get_option ) ){
		return $default;
	}

	/* Get data if it's set */
	if( isset( $get_option[ $option ] ) ){
		return $get_option[ $option ];
	}
	/* Data is not set */
	else{
		return $default;
	}
}

/**
 * Utility: Sanitize RSS Feed Error Message
 * @since 0.1.0
 */
function fx_private_site_sanitize_rss_error( $input ){
	return stripslashes( wp_filter_post_kses( addslashes( $input ) ) );
}

/* === SET SITE TO PRIVATE === */

# Redirects users to the login page.
add_action( 'template_redirect', 'fx_private_site_please_log_in', 0 );

# Disable content in feeds if the feed should be private.
add_filter( 'the_content_feed', 'fx_private_site_feed_content', 95 );
add_filter( 'the_excerpt_rss',  'fx_private_site_feed_content', 95 );
add_filter( 'comment_text_rss', 'fx_private_site_feed_content', 95 );


/**
 * Redirects users that are not logged in to the 'wp-login.php' page.
 * This function is taken from Private Site Feature in "Members" Plugin.
 *
 * @since  0.1.0
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2009 - 2016, Justin Tadlock
 */
function fx_private_site_please_log_in() {

	/* Check if the private site feature is active and if the user is not logged in. */
	if ( true === fx_private_site_get_option( 'enable', false ) && ! is_user_logged_in() ) {

		/* If using BuddyPress and on the register page, don't do anything. */
		if ( function_exists( 'bp_is_current_component' ) && bp_is_current_component( 'register' ) ){
			return;
		}

		/* Redirect to the login page. */
		auth_redirect();
		exit;
	}
}

/**
 * Blocks feed items if the user has selected the private feed feature.
 *
 * @since  0.1.0
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2009 - 2016, Justin Tadlock
 */
function fx_private_site_feed_content( $content ) {
	if ( true === fx_private_site_get_option( 'enable', false ) ) {
		return fx_private_site_get_option( 'rss_error', _x( 'You must be logged into the site to view this content.', 'default RSS Feed error message', 'fx-private-site' ) );
	}
	return $content;
}
