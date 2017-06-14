<?php
/*
Plugin Name: WP Feature Controller
Plugin URI: 
Description: Allows various WordPress core functionality to be configured.
Version: 0.0.1
Author: Dave Romsey
Author URI:
License: GPL
*/


/**
 * Disable various functionality bundled with WordPress.
 */
 
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


/**
 * Disable automatic emptying of the trash.
 * 
 */
add_action( 'init', 'wpfc_remove_schedule_delete' );
function wpfc_remove_schedule_delete() {
	remove_action( 'wp_scheduled_delete', 'wpfc_remove_schedule_delete' );
}
