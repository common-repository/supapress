<?php

if ( ! defined( 'ABSPATH' ) ) exit;

require_once SUPAPRESS_PLUGIN_DIR . '/includes/functions.php';
require_once SUPAPRESS_PLUGIN_DIR . '/includes/widget-template.php';
require_once SUPAPRESS_PLUGIN_DIR . '/includes/widget.php';
require_once SUPAPRESS_PLUGIN_DIR . '/widgets.php';

if ( is_admin() ) {
	require_once SUPAPRESS_PLUGIN_DIR . '/admin/admin.php';
} else {
	require_once SUPAPRESS_PLUGIN_DIR . '/includes/controller.php';
}

add_action( 'init', 'supapress_init' );

function supapress_init() {
	supapress_register_post_types();
}

add_action( 'activate_' . SUPAPRESS_PLUGIN_BASENAME, 'supapress_install' );

function supapress_install() {
	if ( $opt = get_option( 'supapress' ) )
		return;

	if ( get_posts( array( 'post_type' => 'supapress_widget' ) ) )
		return;

	$widget = SupaPress_Widget::get_template( array(
		'title' => 'Example ISBN Lookup'
	) );

	$properties = $widget->get_properties();
	$properties['show_author'] = 'off';
	$properties['isbn_list'] = array(
		'9998887770001' => 'Alpha',
		'9998887770002' => 'Beta',
		'9998887770003' => 'Gamma',
		'9998887770004' => 'Delta',
		'9998887770005' => 'Epsilon',
		'9998887770006' => 'Zeta',
		'9998887770007' => 'Eta',
		'9998887770008' => 'Theta'
	);

	$widget->set_properties( $properties );
	$widget->save();
}


/**
 * Load translations for supapress
 */

add_action('plugins_loaded', 'supapress_translations_setup');

function supapress_translations_setup(){
	load_plugin_textdomain(
		'supapress',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
} 