<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once SUPAPRESS_PLUGIN_DIR . '/includes/book.php';
require_once SUPAPRESS_PLUGIN_DIR . '/includes/filters.php';

add_action( 'plugins_loaded', 'supapress_add_shortcodes' );

function supapress_add_shortcodes() {
	add_shortcode( 'supapress', 'supapress_widget_short_tag' );
}

function supapress_widget_short_tag( $atts, $content = null, $code = '' ) {
	if ( is_feed() ) {
		return '[supapress]';
	}

	if ( 'supapress' == $code ) {
		$defaults = array(
			'id'      => 0,
			'title'   => null,
			'content' => $content
		);

		foreach ( $defaults as $key => $value ) {
			if ( ! isset( $atts[ $key ] ) ) {
				$atts[ $key ] = $value;
			}
		}

		$id    = (int) $atts['id'];
		$title = trim( $atts['title'] );

		if ( ! $widget = supapress_widget( $id ) ) {
			$widget = supapress_get_contact_form_by_title( $title );
		}
	} else {
		$widget = false;
	}

	if ( ! $widget ) {
		return '[supapress 404 "Not Found"]';
	}

	// If the title is not set on the shortcode attributes then set it to the saved title
	if ( $atts['title'] === null ) {
		$atts['title'] = $widget->title();
	}

	// Store atts for custom updates
	$widget->set_properties( array( 'atts' => $atts ) );

	return $widget->render();
}

add_action( 'wp_enqueue_scripts', 'supapress_do_enqueue_scripts' );

function supapress_do_enqueue_scripts() {



	// register our slick styles
	wp_register_style( 'supapress-slick', supapress_plugin_url( 'includes/css/slick.css' ), array(), SUPAPRESS_VERSION, 'all' );
	wp_register_style( 'supapress-slick-theme', supapress_plugin_url( 'includes/css/slick-theme.css' ), array(), SUPAPRESS_VERSION, 'all' );
	// register slick JS
	wp_register_script( 'supapress-slick', supapress_plugin_url( 'includes/js/slick.min.js' ), array( 'jquery' ), SUPAPRESS_VERSION, false );
	// enqueue plugin style
	wp_enqueue_style( 'supapress', supapress_plugin_url( 'includes/css/styles.min.css' ), array(), SUPAPRESS_VERSION, 'all' );
	// register plugin JS
	wp_register_script( 'supapress', supapress_plugin_url( 'includes/js/scripts.min.js' ), array( 'jquery' ), SUPAPRESS_VERSION, true );
	
	$angular_data = array(
		'angularjs_support' => (string) get_option( 'angularjs_support' ) === 'on' ? 'true' : 'false'
	);
	
	//only call this function if it's availeble
	if(function_exists('get_blog_details')){
	    $blog_details = get_blog_details();
	    $angular_data['blog_details'] = array('site_url' => $blog_details->siteurl);
	}
	
	// output whether or not AngularJS is enabled from settings panel
    wp_localize_script( 'supapress', 'supapress_config', $angular_data );
    
	// if angular support is needed we need to enqueue the script whether there is a shortcode on page for not
	if ( (string) get_option( 'angularjs_support' ) === 'on' ) {
		wp_enqueue_script( 'supapress' );
	}
}

add_filter( 'rewrite_rules_array', 'supapress_add_rewrite_rules' );
add_filter( 'query_vars', 'supapress_add_query_vars' );
add_action( 'wp_loaded', 'supapress_add_flush_rules' );

function supapress_add_flush_rules() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

function supapress_add_rewrite_rules( $rules ) {
	// New rules will be added to this array to merge into $rules
	$newRules = array();

	// Array of generic placeholders which can simply be replaced with ([^\/]+) in the regex
	$genericPlaceholders = array( '%title%', '%format%', '%author%', '%imprint%', '%publisher%', '%isbn10%' );

	// Get saved book urls
	$linkPages    = get_option( 'widget_book_link_page' );
	$linkPatterns = get_option( 'widget_book_link_pattern' );

	// If not empty process urls
	if ( ! empty( $linkPages ) ) {
		$total = count( $linkPages );

		// Loop through each book url and build rewrite rule
		for ( $i = 0; $i < $total; $i ++ ) {
			// Set current values
			$linkPage = isset( $linkPages[ $i ] ) ? (int) $linkPages[ $i ] : '';
			// remove trailing slashes from end of legacy book patterns
			$linkPattern = isset( $linkPatterns[ $i ] ) ? rtrim( (string) $linkPatterns[ $i ], '/' ) : '';

			// WordPress book page not set so continue
			if ( $linkPage === '' || $linkPage === - 1 ) {
				continue;
			}

			// Add rules only if the page and pattern is found and the pattern is either a relative url or the domain is the site domain
			if ( $linkPage !== '' && $linkPattern !== '' && ( strpos( $linkPattern, SUPAPRESS_SITE_URL ) === 0 || strpos( $linkPattern, 'http' ) > 0 || strpos( $linkPattern, 'http' ) === false ) ) {
				// If the site domain is found strip is off to get the URI
				if ( strpos( $linkPattern, SUPAPRESS_SITE_URL ) === 0 ) {
					$linkPattern = substr( $linkPattern, strlen( SUPAPRESS_SITE_URL ) );
				}

				// Trim the start slash is found and explode on the slash to get the parts of the url
				$parts      = ltrim( $linkPattern, '/' );
				$parts      = explode( '/', $parts );
				$partsTotal = count( $parts );
				$newRule    = '';

				// Loop each part and replace relevant placeholders to build up new rule
				for ( $j = 0; $j < $partsTotal; $j ++ ) {
					$part = $parts[ $j ];

					if ( $part === '%isbn13%' ) {
						$part = '(\d{13})';
					} elseif ( in_array( $part, $genericPlaceholders ) ) {
						$part = '([^\/]+)';
					} else {
						$part = str_replace( '%isbn13%', '(\d{13})', $part );
						$part = str_replace( $genericPlaceholders, '([^\/]+)', $part );
					}

					$newRule .= "{$part}/";
				}

				// Build temp URL from new rule so we can check where the ISBN match will be
				$tempUrl = str_replace( '(\d{13})', '9998887770001', $newRule );
				$tempUrl = str_replace( '([^\/]+)', 'temp', $tempUrl );

				// Add the last part of the rule and create a temp url escaping the @ as it is used as the preg delimiter
				$newRule  .= "?$";
				$tempRule = str_replace( '@', '\@', $newRule );

				// Use check temp rule to match against temp url, if there is a match we can add the rule
				if ( preg_match( "@$tempRule@", $tempUrl, $matches ) ) {

					// Only add the rule if the ISBN is found in the temp url passing the match position through to the rewrite rule.
					if ( ( $matchIndex = array_search( '9998887770001', $matches ) ) !== false ) {
						$newRules[ $newRule ] = 'index.php?page_id=' . $linkPage . '&supapress_isbn=$matches[' . $matchIndex . ']';
					}
				}
			}
		}
	}

	// Return merged rules
	return $newRules + $rules;
}

function supapress_add_query_vars( $vars ) {
	array_push( $vars, 'supapress_isbn' );

	return $vars;
}

function enqueue_plugin_styles_scripts( $slick = false ) {
	if ( $slick ) {
		// de-queue supapress to fix the order
		wp_dequeue_script( 'supapress' );
		// enqueue slick scripts
		wp_enqueue_style( 'supapress-slick' );
		wp_enqueue_style( 'supapress-slick-theme' );
		wp_enqueue_script( 'supapress-slick' );
	}

	wp_enqueue_style( 'supapress' );
	wp_enqueue_script( 'supapress' );
}