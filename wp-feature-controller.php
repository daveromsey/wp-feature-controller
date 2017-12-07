<?php
/*
Plugin Name: WP Feature Controller
Plugin URI: 
Description: Customize various functionality bundled with WordPress.
Version: 0.1.0
Author: Dave Romsey
Author URI:
License: GPL
*/

//
// Front end
//

add_action( 'init', 'wpfc_init' );
function wpfc_init() {
	// Remove category feeds
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	
	// Remove post and comment feeds
	remove_action( 'wp_head', 'feed_links', 2 );
	
	// Remove EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	
	// Remove Windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );

	// Remove links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	
	// Remove WP version
	remove_action( 'wp_head', 'wp_generator' );
}

//
// Admin and WP Toolbar
//

/**
 * Disable automatic emptying of the trash.
 */
add_action( 'init', 'wpfc_remove_schedule_delete' );
function wpfc_remove_schedule_delete() {
	remove_action( 'wp_scheduled_delete', 'wpfc_remove_schedule_delete' );
}

/**
 * Remove WordPress node from WP Toolbar.
 *
 * @param WP_Admin_Bar $wp_admin_bar instance passed by reference
 */
add_action( 'admin_bar_menu', 'wpfc_toolbar_wordpress', 100 );
function wpfc_toolbar_wordpress( $wp_admin_bar ) {
	if ( ! $wp_admin_bar->get_node( 'wp-logo' ) ) {
		return;
	}
	
	$wp_admin_bar->remove_node( 'wp-logo' );
}

/**
 * Replace the "Howdy, {current user}" text for the my-account
 * node on the WP Toolbar.
 *
 * @param WP_Admin_Bar $wp_admin_bar instance passed by reference
 */
add_action( 'admin_bar_menu', 'wpfc_toolbar_my_account' );
function wpfc_toolbar_my_account( $wp_admin_bar ) {
	if ( ! $wp_admin_bar->get_node( 'my-account' ) ) {
		return;
	}
	
	$avatar = get_avatar( get_current_user_id(), 16 );
	
	$wp_admin_bar->add_node( [
		'id'    => 'my-account',
		'title' =>  wp_get_current_user()->display_name . $avatar, // Display name + Avatar
		//'title' =>  $avatar, // Avatar only
		//'title' => sprintf( '%s %s', __( 'Logged in as', 'wpfc' ), wp_get_current_user()->display_name . $avatar ), // Logged in as {display_name} + Avatar
	] );
}

/**
 * Customize admin footer text
 *
 * @param string $text "Thank you for creating with WordPress." text displayed in admin footer.
 * @return string
 */ 
add_filter( 'admin_footer_text', 'wpfc_admin_footer_text' );
function wpfc_admin_footer_text( $text ) {
	printf( ' %1$s &mdash; &copy; %2$s', get_bloginfo( 'name' ), date( 'Y' ) );
}

/**
 * Hide WordPress version shown in footer for non admins
 *
 * @param string $content Update/version text in admin footer.
 * @return string 
 */
add_filter( 'update_footer', 'wpfc_admin_footer_version', 20 ); // Fire this after the default priority of 10
function wpfc_admin_footer_version( $content ) {
	return ( ! current_user_can( 'manage_options' ) )	? '' : $content;
}
