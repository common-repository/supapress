<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function supapress_delete_plugin() {
	global $wpdb;

	delete_option( 'api_key' );
	delete_option( 'no_books' );
	delete_option( 'no_book' );
	delete_option( 'service_url' );
	delete_option( 'widget_book_link_page' );
	delete_option( 'widget_book_link_pattern' );

	$posts = get_posts( array(
		'posts_per_page' => -1,
		'post_type' => 'supapress_widget',
		'post_status' => 'any'
	) );

	foreach ( $posts as $post )
		wp_delete_post( $post->ID, true );

	$table_name = $wpdb->prefix . "supapress";

	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}

supapress_delete_plugin();