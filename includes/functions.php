<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function supapress_register_post_types() {
	if ( class_exists( 'SupaPress_Widget' ) ) {
		SupaPress_Widget::register_post_type();

		return true;
	} else {
		return false;
	}
}

function supapress_locate_template( $template, $path = 'views/', $properties = array() ) {
	if ( ( $file = locate_template( 'plugins/supapress/' . $path . $template . '.php' ) ) !== '' ) {
		return $file;
	} else {
		return SUPAPRESS_PLUGIN_DIR . '/' . $path . $template . '.php';
	}
}

function supapress_is_object_caching() {
	return ! supapress_is_file_caching() && file_exists( WP_CONTENT_DIR . '/object-cache.php' );
}

function supapress_is_file_caching() {
	return defined( 'SUPAPRESS_FILE_BASED_CACHE' );
}

function supapress_create_cache_prefix() {
	$newPrefix = SUPAPRESS_CACHE_PREFIX . time() . '-';
	set_site_transient( SUPAPRESS_CACHE_PREFIX_KEY, $newPrefix, DAY_IN_SECONDS * 31 );

	return $newPrefix;
}

function supapress_get_cache_prefix() {
	$prefix = SUPAPRESS_CACHE_PREFIX;

	if ( supapress_is_object_caching() ) {
		$prefix = get_site_transient( SUPAPRESS_CACHE_PREFIX_KEY );
		if ( ! $prefix ) {
			$prefix = supapress_create_cache_prefix();
		}
	}

	if ( isset( $_GET['debug'] ) ) {
		echo ' - cache-prefix: ' . $prefix;
	}

	return $prefix;
}

function supapress_call_supafolio( $service, $params, $properties = array(), $cacheCalls = true ) {
	if ( (string) get_option( 'service_url' ) !== '' ) {
		$baseUrl = rtrim( get_option( 'service_url' ), '/' ) . '/';
	} else {
		$baseUrl = SUPAPRESS_DEFAULT_SERVICE_URL;
	}

	// Cater for a user not entering a protocol in their service URL - https to future-proof
	if ( ! preg_match( '/^http(s?):\/\//', $baseUrl ) ) {
		$baseUrl = 'http://' . $baseUrl;
	}

	if ( (string) get_option( 'api_key' ) !== '' ) {
		$api = trim( get_option( 'api_key' ) );
	} else {
		$api = SUPAPRESS_DEFAULT_SERVICE_API;

		// if no API key is entered then the v2 service should be used
		$baseUrl .= 'v2/';
	}

	$url = $baseUrl . $service;

	if ( count( $params ) > 0 ) {
		$url .= '?';

		foreach ( $params as $key => $value ) {
			if ( ! empty( $properties['widget_type'] ) && $properties['widget_type'] === 'isbn_lookup' &&
			     ( ( $key === 'isbns' && trim( $value ) === '' ) || ( $key === 'collection' && empty( $value ) ) )
			) {
				return (string) get_option( 'no_books' ) !== '' ? get_option( 'no_books' ) : SUPAPRESS_DEFAULT_NO_BOOKS_MESSAGE;
			}

            // stop arrays from being passed to the API call
            if (is_array($value) === true) {
                $value = null;
            }

			$value = urlencode( urldecode( $value ) );
			$url   .= "$key=$value&";
		}

		$url = rtrim( $url, '&' );
	}

	// Output the service call inline on the page - use "?debug"
	if ( isset( $_GET['debug'] ) ) {
		echo esc_url( $url );
	}

	if ( $cacheCalls ) {
		// Create the key used for the cache file: now includes $api key to cater for altering of the value in settings
		$cacheKey = supapress_get_cache_prefix() . md5( $url . $api );
		$cacheType = ! empty( $properties['widget_type'] ) ? $properties['widget_type'] : 'generic';
		//$startTime = microtime(true);
		// If cached content found, return that
		if ( $cachedContent = supapress_cache_get( $cacheKey, $cacheType ) ) {
			if ( isset( $_GET['debug'] ) ) {
				echo ' - retrieving cached file';
				//$cacheTime = (microtime(true) - $startTime) * 1000;
				//echo ' - cache retrieval ' . $cacheTime . 'ms';
			}
			return $cachedContent;
		}

		if ( isset( $_GET['debug'] ) ) {
			echo ' - no cached file';
		}
	} else if ( isset( $_GET['debug'] ) ) {
		echo ' - do not cache file';
	}

	// If not, fall through to getting the content, caching it for use later
	$response = wp_remote_post( $url, array(
		'method'      => 'GET',
		'timeout'     => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array( 'x-apikey' => $api ),
		'body'        => array(),
		'cookies'     => array()
	) );

	if ( is_wp_error( $response ) ) {
		return "Something went wrong: " . $response->get_error_message();
	} else {
		$result = json_decode( $response['body'] );

		if ( $result !== null ) {
			if ( $result->status !== 'success' ) {
				foreach ( $result->data->errors as $error ) {
					// Check to see if service returns a particular error message and show associated defined message:
					// - at current time can only check against message text rather than specific error code
					// - let all other errors fall through to page
					if ( isset( $properties['widget_type'] ) && $properties['widget_type'] === 'product_details' && $error->message === 'Product not in database' ) {
						return (string) get_option( 'no_book' ) !== '' ? get_option( 'no_book' ) : SUPAPRESS_DEFAULT_BOOK_NOT_FOUND_MESSAGE;
					} else {
						return "Something went wrong: " . $error->message;
					}
				}
			} else {
				if ( $cacheCalls ) {
					// Cache content
					supapress_cache_set( $cacheKey, $cacheType, $result->data );
				}

				return ( $result->data );
			}
		} else {
			return "Something went wrong";
		}
	}

	return "Something went wrong";
}

function supapress_cache_clear() {
	if ( supapress_is_object_caching() ) {
		supapress_create_cache_prefix();

		return 'All object';
	}

	if ( supapress_is_file_caching() ) {
		$total = 0;
		$dir   = rtrim( supapress_cache_get_file_path(), "/" );
		if ( ( $handle = opendir( $dir ) ) ) {
			while ( false !== ( $entry = readdir( $handle ) ) ) {
				if ( $entry === ".." || $entry === "." ) {
					continue;
				}
				unlink( "{$dir}/{$entry}" );
				$total ++;
			}

			closedir( $handle );
		}
	} else {
		global $wpdb;

		if ( is_multisite() ) {
			$key   = 'meta_key';
			$table = 'sitemeta';
		} else {
			$key   = 'option_name';
			$table = 'options';
		}

		// Keep and return total to be deleted for use by AJAX call's feedback message
		$total = $wpdb->query( "DELETE FROM {$wpdb->base_prefix}{$table} WHERE  {$key} LIKE ('_site_transient%_" . SUPAPRESS_CACHE_PREFIX . "%')" );
	}

	return $total;
}

function supapress_cache_get_lifetime( $widgetType ) {
	$lifeTimeConstant = 'SUPAPRESS_CACHE_LIFETIME_' . strtoupper( $widgetType );

	return defined( $lifeTimeConstant ) ? constant( $lifeTimeConstant ) : SUPAPRESS_CACHE_LIFETIME_DEFAULT;
}

function supapress_cache_get_file_path( $cacheKey = "" ) {
	$dir = WP_CONTENT_DIR . '/cache/supapress';
	if ( ! is_dir( $dir ) ) {
		mkdir( $dir, 0755, true );
	}

	return "{$dir}/{$cacheKey}";
}

function supapress_cache_set( $cacheKey, $widgetType, $data ) {
	if ( supapress_is_file_caching() ) {
		wp_cache_set( $cacheKey, $data, 'supapress' );
		$path = supapress_cache_get_file_path( $cacheKey );
		$return = file_put_contents( $path, gzdeflate( json_encode( $data ) ) ) !== false;
	} else {
		$return = set_site_transient( $cacheKey, $data, supapress_cache_get_lifetime( $widgetType ) );
	}

	return $return;
}

function supapress_cache_get( $cacheKey, $widgetType ) {
	if ( supapress_is_file_caching() ) {
		// try and get from in-flight cache
		$wp_cache = wp_cache_get( $cacheKey, 'supapress', false, $found );
		if ( $found ) {
			return $wp_cache;
		}

		$return = false;
		$path   = supapress_cache_get_file_path( $cacheKey );
		// check if the cache file exists
		if ( is_file( $path ) ) {
			$cacheLifetime = supapress_cache_get_lifetime( $widgetType );
			// if the file modified time plus the cache lifetime  is in the future then load the cache
			if ( ( filemtime( $path ) + $cacheLifetime ) > time() ) {
				$return = json_decode( gzinflate( file_get_contents( $path ) ) );
			}
		}
	} else {
		$return = get_site_transient( $cacheKey );
	}

	return $return;
}

function supapress_plugin_url( $path = '' ) {
	$url = untrailingslashit( SUPAPRESS_PLUGIN_URL );

	if ( ! empty( $path ) && is_string( $path ) && false === strpos( $path, '..' ) ) {
		$url .= '/' . ltrim( $path, '/' );
	}

	return $url;
}

function supapress_generate_widget_wrapper_id( $type, $properties ) {
	return $type . '-' . $properties['atts']['id'];
}

function supapress_render_isbn_lookup_grid( $result, $properties ) {
	$supapress = new SupaPress_Book( $result, $properties );
	$html      = "";
	$perRow    = "4";

	if ( isset( $properties['per_row'] ) && trim( $properties['per_row'] ) !== '' ) {
		$perRow = trim( $properties['per_row'] );
	}

	if ( $supapress ) {
		enqueue_plugin_styles_scripts();

		$html = "<div id='" . supapress_generate_widget_wrapper_id( 'isbn-grid', $properties ) . "' class='isbn-grid per-row-{$perRow}'>";

		ob_start();

		include supapress_locate_template( 'isbn-lookup-grid', 'views/', $properties );

		$html .= ob_get_contents();

		ob_end_clean();
		$html .= '</div>';
	}

	return $html;
}

function supapress_render_filters( $results, $properties, $params ) {
	$supapressFilters = new SupaPress_Filters( $results, $properties );

	ob_start();

	include supapress_locate_template( 'filters' );

	$html = ob_get_contents();

	ob_end_clean();

	return $html;
}

function supapress_render_isbn_lookup_list( $result, $properties ) {
	$supapress = new SupaPress_Book( $result, $properties );
	$html      = "";
	$perRow    = "1";

	if ( isset( $properties['per_row'] ) && trim( $properties['per_row'] ) !== '' ) {
		$perRow = trim( $properties['per_row'] );
	}

	if ( $supapress ) {
		enqueue_plugin_styles_scripts();

		$html = "<div id='" . supapress_generate_widget_wrapper_id( 'isbn-list', $properties ) . "' class='isbn-list per-row-{$perRow}'>";

		ob_start();

		include supapress_locate_template( 'isbn-lookup-list' );

		$html .= ob_get_contents();

		ob_end_clean();
		$html .= '</div>';
	}

	return $html;
}

function supapress_render_isbn_lookup_carousel( $result, $properties ) {
	$supapress = new SupaPress_Book( $result, $properties );
	$html      = "";

	if ( $supapress ) {
		enqueue_plugin_styles_scripts( true );

		$leftArrow           = supapress_array_text( $properties, 'carousel_settings', 'left_arrow_image' );
		$rightArrow          = supapress_array_text( $properties, 'carousel_settings', 'right_arrow_image' );
		$showDots            = supapress_array_checked( $properties, 'carousel_settings', 'show_dots' );
		$showArrows          = supapress_array_checked( $properties, 'carousel_settings', 'show_arrows' );
		$infiniteScroll      = supapress_array_checked( $properties, 'carousel_settings', 'infinite_scroll' );
		$autoPlay            = supapress_array_checked( $properties, 'carousel_settings', 'auto_play' );
		$autoPlaySpeed       = supapress_array_text( $properties, 'carousel_settings', 'auto_play_speed' );
		$speed               = supapress_array_text( $properties, 'carousel_settings', 'speed' );
		$slidesToShow        = supapress_array_text( $properties, 'carousel_settings', 'number_to_show' );
		$slidesToScroll      = supapress_array_text( $properties, 'carousel_settings', 'number_to_scroll' );
		$lazyLoad            = supapress_array_text( $properties, 'carousel_settings', 'lazy_load' );
		$lazyLoadPlaceholder = supapress_array_text( $properties, 'carousel_settings', 'lazy_load_placeholder' );
		if ( $lazyLoadPlaceholder === '' ) {
			$lazyLoadPlaceholder = supapress_default_lazyload_placeholder();
		}

		$carousel = array(
			'arrows'         => $showArrows === '' ? false : true,
			'dots'           => $showDots === '' ? false : true,
			'infinite'       => $infiniteScroll === '' ? false : true,
			'autoplay'       => $autoPlay === '' ? false : true,
			'autoplaySpeed'  => $autoPlaySpeed === '' ? 3000 : (int) $autoPlaySpeed,
			'speed'          => $speed === '' ? 700 : (int) $speed,
			'slidesToShow'   => $slidesToShow === '' ? 4 : (int) $slidesToShow,
			'slidesToScroll' => $slidesToScroll === '' ? 1 : (int) $slidesToScroll,
			'responsive'     => array(
				array(
					'breakpoint' => 720,
					'settings'   => array(
						'slidesToShow'   => 2,
						'slidesToScroll' => 2,
						'arrows'         => true,
						'dots'           => true,
						'infinite'       => true,
					)
				),
				array(
					'breakpoint' => 480,
					'settings'   => array(
						'slidesToShow'   => 1,
						'slidesToScroll' => 1,
						'arrows'         => false,
						'dots'           => true,
						'infinite'       => true,
					)
				)
			)
		);

		if ( $lazyLoad === 'on' ) {
			$carousel['lazyLoad']            = 'ondemand';
			$carousel['lazyLoadPlaceholder'] = $lazyLoadPlaceholder;
		}

		if ( $leftArrow !== '' ) {
			$carousel['prevArrow'] = '<img class="slick-prev" src="' . $leftArrow . '" alt="left arrow" />';
		}

		if ( $rightArrow !== '' ) {
			$carousel['nextArrow'] = '<img class="slick-next" src="' . $rightArrow . '" alt="right arrow" />';
		}

		$html = '<div id="' . supapress_generate_widget_wrapper_id( 'isbn-carousel', $properties ) . '" class="isbn-carousel" data-carousel-settings="' . htmlspecialchars( json_encode( $carousel ), ENT_QUOTES ) . '">';

		ob_start();

		include supapress_locate_template( 'isbn-lookup-carousel' );

		$html .= ob_get_contents();

		ob_end_clean();

		$html .= '</div>';
	}

	return $html;
}

function supapress_default_lazyload_placeholder() {
	return apply_filters( 'supapress_default_lazyload_placeholder', SUPAPRESS_PLUGIN_URL . '/admin/img/lazy-load-placeholder.jpg' );
}

function supapress_render_product_details( $result, $properties ) {
	$supapress = new SupaPress_Book( $result, $properties );
	$html      = "";

	if ( $supapress ) {
		enqueue_plugin_styles_scripts();

		$html = '<div id="' . supapress_generate_widget_wrapper_id( 'product-details', $properties ) . '" class="product-details">';

		ob_start();

		include supapress_locate_template( 'product-details' );

		$html .= ob_get_contents();

		ob_end_clean();

		$html .= '</div>';
	}

	return $html;
}

function supapress_render_search_results_grid( $result, $properties, $params ) {
	// TODO CREATE SUPAPRESS_SEARCH CLASS AND PASS PARAMS TO THAT
	$supapress = new SupaPress_Book( $result, $properties, $params );
	$html      = "";
	$perRow    = "4";

	if ( isset( $properties['per_row'] ) && trim( $properties['per_row'] ) !== '' ) {
		$perRow = trim( $properties['per_row'] );
	}

	if ( $supapress ) {
		enqueue_plugin_styles_scripts();

		$html = "<div id='" . supapress_generate_widget_wrapper_id( 'search-grid', $properties ) . "' class='search-grid search per-row-{$perRow}'>";

		ob_start();

		include supapress_locate_template( 'search-results-grid' );

		$html .= ob_get_contents();

		ob_end_clean();

		$html .= '</div>';
	}

	return $html;
}

function supapress_render_search_results_list( $result, $properties, $params ) {
	// TODO CREATE SUPAPRESS_SEARCH CLASS AND PASS PARAMS TO THAT
	$supapress = new SupaPress_Book( $result, $properties, $params );
	$html      = "";
	$perRow    = "1";

	if ( isset( $properties['per_row'] ) && trim( $properties['per_row'] ) !== '' ) {
		$perRow = trim( $properties['per_row'] );
	}

	if ( $supapress ) {
		enqueue_plugin_styles_scripts();

		$html = "<div id='" . supapress_generate_widget_wrapper_id( 'search-list', $properties ) . "' class='search-list search per-row-{$perRow}'>";

		ob_start();

		include supapress_locate_template( 'search-results-list' );

		$html .= ob_get_contents();

		ob_end_clean();

		$html .= '</div>';
	}

	return $html;
}

function supapress_render_custom_layout( $result, $properties, $params ) {
	$supapress = new SupaPress_Book( $result, $properties, $params );
	$html      = "";

	if ( $supapress ) {
		enqueue_plugin_styles_scripts();

		$type = $properties['widget_type'];

		if ( $type === 'search_results' ) {
			$type .= ' search';
		}

		// since we started storing relative paths to the custom templates we must fix any full paths
		$clf = $properties['custom_layout_file'];
		if ( strpos( $clf, '/' ) !== 0 ) { // we have a relative path
			$clf = ABSPATH . '/' . $clf;
		}

		$html = '<div id="' . supapress_generate_widget_wrapper_id( $properties['widget_type'], $properties ) . '" class="' . $type . '">';

		ob_start();

		if ( file_exists( $clf ) ) {
			include $clf;
		} else {
			echo _e( 'Custom template file not found. File: ', 'supapress' ) . basename( $properties['custom_layout_file'] );
		}

		$html .= ob_get_contents();

		ob_end_clean();

		$html .= '</div>';
	}

	return $html;
}

function supapress_property_value( $properties, $key ) {
	return isset( $properties[ $key ] ) ? esc_attr( $properties[ $key ] ) : '';
}

function supapress_text( $properties, $key ) {
	return supapress_property_value( $properties, $key );
}

function supapress_array_text( $properties, $array, $key ) {
	return isset( $properties[ $array ] ) && is_array( $properties[ $array ] ) && isset( $properties[ $array ][ $key ] ) ? esc_attr( $properties[ $array ][ $key ] ) : '';
}

function supapress_property_array( $properties, $array ) {
	return isset( $properties[ $array ] ) && is_array( $properties[ $array ] ) ? $properties[ $array ] : array();
}

function supapress_checked( $properties, $key, $default = false ) {
	if ( isset( $properties[ $key ] ) && $properties[ $key ] === 'on' ) {
		return ' checked="checked"';
	} elseif ( $default === true ) {
		return ' checked="checked"';
	} else {
		return '';
	}
}

function supapress_array_checked( $properties, $array, $key, $default = false ) {
	if ( isset( $properties[ $array ] ) && is_array( $properties[ $array ] ) && isset( $properties[ $array ][ $key ] ) && $properties[ $array ][ $key ] === 'on' ) {
		return ' checked="checked"';
	} elseif ( $default === true ) {
		return ' checked="checked"';
	} else {
		return '';
	}
}

function supapress_radio_checked( $properties, $key, $value, $default = false ) {
	if ( isset( $properties[ $key ] ) && $properties[ $key ] === (string) $value ) {
		return ' checked="checked"';
	} elseif ( $default === true ) {
		return ' checked="checked"';
	} else {
		return '';
	}
}

function supapress_selected( $properties, $key, $value, $default = false ) {
	if ( isset( $properties[ $key ] ) && $properties[ $key ] === (string) $value ) {
		return ' selected="selected"';
	} elseif ( $default === true ) {
		return ' selected="selected"';
	} else {
		return '';
	}
}

function supapress_array_selected( $properties, $array, $key, $value, $default = false ) {
	if ( isset( $properties[ $array ] ) && is_array( $properties[ $array ] ) && isset( $properties[ $array ][ $key ] ) && $properties[ $array ][ $key ] === (string) $value ) {
		return ' selected="selected"';
	} elseif ( $default === true ) {
		return ' selected="selected"';
	} else {
		return '';
	}
}

function supapress_multi_selected( $properties, $key, $value, $default = false ) {
	if ( isset( $properties[ $key ] ) && in_array( $value, $properties[ $key ] ) ) {
		return ' rel="option_' . array_search( $value, $properties[ $key ] ) . '" selected="selected"';
	} elseif ( $default === true ) {
		return ' rel="" selected="selected"';
	} else {
		return ' rel=""';
	}
}

function supapress_invalid_book_class( $value ) {
	return $value === 'ISBN not found' ? 'class="book-not-found" ' : '';
}

function supapress_invalid_filter_class( $value, $list ) {
	// TODO finish this
	return array_key_exists( $value, $list ) ? '' : 'class="filter-not-found" ';
}

function supapress_set_default( $action ) {
	return $action === 'edit' ? false : true;
}

function supapress_get_template_url( $urlId, SupaPress_Book $book = null ) {
	$linkPages    = get_option( 'widget_book_link_page' );
	$linkPatterns = get_option( 'widget_book_link_pattern' );

	// Page set to link not in use so skip
	if ( isset( $linkPages[ $urlId ] ) && $linkPages[ $urlId ] === "-1" ) {
		return false;
	}

	// Using external link
	if ( isset( $linkPages[ $urlId ] ) && $linkPages[ $urlId ] === "-2" ) {
		return supapress_translate_template_url( $linkPatterns[ $urlId ], $book );
	}

	if ( get_option( 'permalink_structure' ) === '' && isset( $linkPages[ $urlId ] ) ) {
		return SUPAPRESS_SITE_URL . '?page_id=' . $linkPages[ $urlId ] . '&supapress_isbn=' . $book->get_isbn13();
	} elseif ( isset( $linkPatterns[ $urlId ] ) ) {
		if ( function_exists( 'get_blog_details' ) ) {
			// add multisite prefix
			$currentSite = get_blog_details();
			$prefix      = rtrim( $currentSite->path, '/' );
		} else {
			$prefix = '';
		}

		return $prefix . supapress_translate_template_url( $linkPatterns[ $urlId ], $book );
	} else {
		return false;
	}
}

function supapress_translate_template_url( $url, SupaPress_Book $book = null ) {
	$url = str_replace( '%isbn13%', $book === null ? '9998887770001' : $book->get_isbn13(), $url );
	$url = str_replace( '%isbn10%', $book === null ? '8765432101' : $book->get_isbn10(), $url );
	$url = str_replace( '%title%', $book === null ? 'my-book' : $book->get_seo_title(), $url );
    $url = str_replace('%subtitle%', $book === null ? 'my-book-subtitle' : $book->get_subtitle_url_part(), $url );
    $url = str_replace( '%format%', $book === null ? 'my-format' : $book->get_format_url_part(), $url );
	$url = str_replace( '%text-title%', $book === null ? 'my-book' : $book->get_seo_title(), $url );
	$url = str_replace( '%description%', $book === null ? 'my description' : '', $url );
	$url = str_replace( '%author%', $book === null ? 'my-author' : $book->get_author_url_part(), $url );
	$url = str_replace( '%imprint%', $book === null ? 'my-imprint' : $book->get_imprint_url_part(), $url );
	$url = str_replace( '%publisher%', $book === null ? 'my-publisher' : $book->get_publisher_url_part(), $url );
	$url = str_replace( '%price_usd%', $book === null ? '0' : $book->get_price_url_part("amount", "USD"), $url );
	$url = str_replace( '%discount_usd%', $book === null ? '0' : $book->get_price_url_part("discount", "USD"), $url );
	$url = str_replace( '%price_gbp%', $book === null ? '0' : $book->get_price_url_part("amount", "GBP"), $url );
	$url = str_replace( '%discount_gbp%', $book === null ? '0' : $book->get_price_url_part("discount", "GBP"), $url );
	$url = str_replace( '%price_cad%', $book === null ? '0' : $book->get_price_url_part("amount", "CAD"), $url );
	$url = str_replace( '%discount_cad%', $book === null ? '0' : $book->get_price_url_part("discount", "CAD"), $url );

	return $url;
}

function supapress_translate_template_text( $text, SupaPress_Book $book = null ) {
	$text = str_replace( '%isbn13%', $book === null ? '9998887770001' : $book->get_isbn13(), $text );
	$text = str_replace( '%isbn10%', $book === null ? '8765432101' : $book->get_isbn10(), $text );
	$text = str_replace( '%title%', $book === null ? 'My Book' : $book->get_title(), $text );
    $text = str_replace( '%subtitle%', $book === null ? 'My Book Subtitle' : $book->get_subtitle(), $text );
    $text = str_replace( '%format%', $book === null ? 'My Format' : $book->get_format_text(), $text );
	$text = str_replace( '%text-title%', $book === null ? 'My Book' : $book->get_title(), $text );
	$text = str_replace( '%description%', $book === null ? 'My Description' : $book->get_seo_description(), $text );
	$text = str_replace( '%author%', $book === null ? 'My Author' : $book->get_author_text(), $text );
	$text = str_replace( '%imprint%', $book === null ? 'My Imprint' : $book->get_imprint_text(), $text );
	$text = str_replace( '%publisher%', $book === null ? 'My Publisher' : $book->get_publisher_text(), $text );
	$text = str_replace( '%price_usd%', $book === null ? '0' : $book->get_price_url_part("amount", "USD"), $text );
	$text = str_replace( '%discount_usd%', $book === null ? '0' : $book->get_price_url_part("discount", "USD"), $text );
	$text = str_replace( '%price_gbp%', $book === null ? '0' : $book->get_price_url_part("amount", "GBP"), $text );
	$text = str_replace( '%discount_gbp%', $book === null ? '0' : $book->get_price_url_part("discount", "GBP"), $text );
	$text = str_replace( '%price_cad%', $book === null ? '0' : $book->get_price_url_part("amount", "CAD"), $text );
	$text = str_replace( '%discount_cad%', $book === null ? '0' : $book->get_price_url_part("discount", "CAD"), $text );

	return $text;
}

function supapress_get_element_template( $label, $open = false ) {
	$html = '<div class="element supapress-accordion-header' . ( $open === true ? ' open' : '' ) . '">';
	$html .= '	<span class="element-config-icon">';
	$html .= '		<span class="svg-right-arrow' . ( $open === true ? ' open' : '' ) . '">';
	$html .= '			<svg class="svg-icon">';
	$html .= '				<use xlink:href="' . SUPAPRESS_PLUGIN_URL . '/admin/img/svg/sprite.svg#icon-right-arrow"></use>';
	$html .= '			</svg>';
	$html .= '		</span>';
	$html .= '		<span>';
	$html .= '		    ' . $label;
	$html .= '		</span>';
	$html .= '	</span>';
	$html .= '</div>';

	return $html;
}

function supapress_get_empty_element_template( $label, $name ) {
	$html = '<div class="hide tab-level ' . $name . ' supapress-accordion-wrapper sub-content-wrapper">';
	$html .= supapress_get_element_template( $label );
	$html .= '	<div class="supapress-accordion-content element-config hide">';
	$html .= '		<div class="supapress-accordion-content-inner element-config-inner">';
	$html .= '			<div class="supapress-field-wrapper">';
	$html .= '				<div class="supapress-paragraph">This element does not have custom settings at this time.</div>';
	$html .= '			</div>';
	$html .= '		</div>';
	$html .= '	</div>';
	$html .= '</div>';

	return $html;
}

function supapress_get_toggle_field( $label, $name, $properties, $class = '', $action = null ) {
	// Get value or default value if action is passed
	$value = $action === null ? supapress_checked( $properties, $name ) : supapress_checked( $properties, $name, supapress_set_default( $action ) );

	// Build HTML
	$html = '<div class="supapress-field-wrapper ' . ( trim( $class ) !== '' ? trim( $class ) : 'all-tabs' ) . '">';
	$html .= '	<label class="supapress-label" for="' . $name . '">' . $label . ':</label>';
	$html .= '	<div class="onoffswitch">';
	$html .= '		<input type="hidden" name="' . $name . '" value="off" />';
	$html .= '     <input type="checkbox" name="' . $name . '" class="onoffswitch-checkbox sub-content-toggle" data-sub-content="' . $name . '" id="' . $name . '"' . $value . ' />';
	$html .= '     <label class="onoffswitch-label" for="' . $name . '">';
	$html .= '			<span class="onoffswitch-inner"></span>';
	$html .= '			<span class="onoffswitch-switch"></span>';
	$html .= '		</label>';
	$html .= '	</div>';
	$html .= '</div>';

	return $html;
}

function supapress_get_retailer_link_object($index, $book) {
    $retailerNames = get_option( 'widget_retailer_name' );
	$retailerImages = get_option( 'retailer_image' );
	$retailerLinkPatterns = get_option( 'widget_retailer_link_pattern' );
	$retailerTrackingPatterns = get_option( 'widget_retailer_tracking_pattern' );
	$retailerLinkFormats = get_option( 'retailer_link_formats' );
	
	if( !$retailerNames[$index] ) {
    	return null;
	}
	
	return (object) array( 
        "name" => isset($retailerNames[$index]) ? $retailerNames[$index] : "",
        "link" => isset($retailerLinkPatterns[$index]) ? supapress_translate_template_url($retailerLinkPatterns[$index], $book) : "",
        "image" => isset($retailerImages[$index]) ? $retailerImages[$index] : "",
        "tracking" => isset($retailerTrackingPatterns[$index]) ? supapress_translate_template_url($retailerTrackingPatterns[$index], $book) : "",
        "format" => isset($retailerLinkFormats[$index]) ? $retailerLinkFormats[$index] : null
    );
}

/**
 * Get Yoast/Yoast Premium plugin details
 *
 * @return null|array If Yoast or Yoast Premium are not active return null
 */
function supapress_get_yoast_data() {

    $yoast_plugin = 'wordpress-seo/wp-seo.php';
    $yoast_premium_plugin = 'wordpress-seo-premium/wp-seo-premium.php';
    $yoast_plugin_details = null;
    if( is_plugin_active( $yoast_plugin ) ) {
        $yoast_plugin_details = get_plugin_data( ABSPATH . '/wp-content/plugins/' . $yoast_plugin );
    } elseif( is_plugin_active( $yoast_premium_plugin ) ) {
        $yoast_plugin_details = get_plugin_data( ABSPATH . '/wp-content/plugins/' . $yoast_premium_plugin );
    }

    return $yoast_plugin_details;

}

/** @type SupaPress_Book $supapress */
function supapress_run_seo_filters($result, $field = '') {

    $yoast_plugin_details = supapress_get_yoast_data();

    if ($yoast_plugin_details === null) {
        return;
    }

	$supapress = null;
	$book = null;
    if( !is_string( $result ) && isset( $result->book ) && count( $result->book ) > 0 ) {
        $supapress = new SupaPress_Book( $result, null );
        if( $supapress->has_book() ) {
            $supapress->the_book( false );
        }
	}

	// if book doesn't exist return before use of get_book method
	if( !is_object( $supapress ) || !method_exists( $supapress, 'get_book' ) ) {
		return;
	}

	// primary format checks and, if necessary, get primary format data from service
	$book = $supapress->get_book();
	$is_primary_format = isset( $book->primary_format ) && $book->primary_format === true ? true : false;
	$set_primary_format_canonical = get_option( 'product_details_seo_primary_format_canonical' ) === 'on' && !$is_primary_format;
	$primary_format_result = null;
	if( $set_primary_format_canonical ) {
		foreach ($supapress->_get('formats') as $format) {
			if( isset( $format->primary_format ) && $format->primary_format === true ) {
				$primary_format_result = supapress_call_supafolio( 'book/' . $format->isbn, array() );
				break;
			}
		}
	}

    $supapress_seo_override_title = function($field) use ($supapress) {

        if ($supapress === null) {
            return $field;
        }

        // get the string saved in the settings panel
        $text_template = get_option( 'product_details_seo_title' );

        if( $text_template ) {

            return supapress_translate_template_text( $text_template, $supapress );
        }

        return $field;

    };

    $supapress_seo_override_desc = function($field) use ($supapress) {

        if ($supapress === null) {
            return $field;
        }

        // get the string saved in the settings panel
        $text_template = get_option( 'product_details_seo_description' );
        if( $text_template ) {
            return supapress_translate_template_text( $text_template, $supapress );
        }

        return $field;

    };

    $supapress_seo_override_url = function($field) use ($supapress, $set_primary_format_canonical, $primary_format_result) {

		$text_template = get_option( 'product_details_seo_canonical' );

        if ($supapress === null || empty( $text_template )) {
            return get_permalink();
		}

		$supapress_instance = $supapress;

		// if $set_primary_format_canonical and current is not the primary format set $supapress_instance to primary format instance
		if( $set_primary_format_canonical && $primary_format_result !== null ) {
			$supapress_instance = new SupaPress_Book( $primary_format_result, array() );
			if( $supapress_instance->has_book() ) {
				$supapress_instance->the_book( false );
			}
		}

		$url = site_url() . supapress_translate_template_url( $text_template, $supapress_instance );

		if (substr($url, -1) !== '/') {
			$url .= '/'; // Append a slash if not present
		}

        return $url;
    };

    // Cover Override
    $supapress_seo_override_cover = function($field) use ($supapress) {

        if ($supapress === null) {
            return $field;

        }

        // there is no setting for the cover so we just get the book and return the cover for it
        return $supapress->get_cover();

    };

    if( $field !== '' ) {

        /**
         * the below logic is included to maintain old functionality:
         *
         * the function supapress_call_supafolio_by_isbn_full_details() can also be used within a Yoast Hook:
         *
         * For example:
         *
         * add_filter( 'wpseo_title', function ( $title ) {
         *  return supapress_call_supafolio_by_isbn_full_details( 'title', $title );
         * }, 15, 3 );
         *
         * however this will make a Supafolio call for each Yoast Hook that gets run
         *
         */

        if( $field === 'title' ) {
            return $supapress_seo_override_title( $field );
        } elseif( $field === 'description' ) {
            return $supapress_seo_override_desc( $field );
        } elseif( $field === 'canonical' ) {
            return $supapress_seo_override_url( $field );
        } elseif( $field === 'cover' ) {
            return $supapress_seo_override_cover( $field );
        }

	}	

    add_filter( 'wpseo_opengraph_url', $supapress_seo_override_url, 15, 3 );
	add_filter( 'wpseo_title', $supapress_seo_override_title, 15, 1 );
	add_filter( 'wpseo_twitter_title', $supapress_seo_override_title, 15, 1 );
    add_filter( 'wpseo_opengraph_title', $supapress_seo_override_title, 15, 1 );
    add_filter( 'wpseo_metadesc', $supapress_seo_override_desc, 15, 1 );
    add_filter( 'wpseo_opengraph_desc', $supapress_seo_override_desc, 15, 1 );
	add_filter( 'wpseo_canonical', $supapress_seo_override_url, 15, 3 );
    add_filter( 'wpseo_opengraph_image', $supapress_seo_override_cover, 15, 3 );
    add_filter( 'wpseo_twitter_image', $supapress_seo_override_cover, 15, 3 );

	supapress_run_canonical_redirect($supapress_seo_override_url( $field ));

}

function supapress_run_canonical_redirect(string $canonical_url) {

	if(empty($canonical_url)){
		return;
	}
	
	// if there is a QUERY_STRING don't run
    if (!empty($_SERVER['QUERY_STRING'])) {
        return;
    } 

	$current_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $canonical_url = apply_filters( 'wpseo_canonical', '' );

    if ($current_url !== $canonical_url) {
        wp_redirect($canonical_url, 301);
        exit();
    }
}


/**
 * @param $field
 * @param $value
 *
 * @return false|mixed|string|void|null
 *
 */

function supapress_override_seo_field( $field = '', $value = '' ) {

    // test whether product details override is set to on
    if( get_option( 'product_details_seo_override' ) === 'off' ) {
        return;
    }

    $book_details_pages_array = get_option( 'widget_book_link_page' );

    // test if book details page has been set, or if on full details page
    if( $book_details_pages_array === false || in_array( get_the_ID(), $book_details_pages_array ) === false ) {
        return;
    }

    // check that the ISBN is set
    if( preg_match( '/\d{13}$/', get_query_var( 'supapress_isbn' ) ) === '' ) {
        return;
    }

    $extractedParams = supapress_extract_params_for_seo( get_the_ID() );

    $result = supapress_call_supafolio( 'book/' . get_query_var( 'supapress_isbn' ), $extractedParams );

    return supapress_run_seo_filters($result, $field);

}

add_action('get_header', 'supapress_override_seo_field');


/**
 * @param $field
 * @param $value
 *
 * @return false|mixed|string|void|null
 *
 * the below function is depreciated. use the supapress_override_seo_field function run seo hooks
 *
 */

function supapress_call_supafolio_by_isbn_full_details( $field, $value ) {
    return supapress_override_seo_field( $field );
}


/**
 * Add a value to a HTML attribute if it is not already present
 *
 * @param $str
 * @param $attr
 * @param $value
 *
 * @return mixed
 */
function supapress_add_value_to_html_attribute( $str, $attr, $value ) {
	// split the first tag from the sting
	// if a whole first tag is not found then return the string
	if ( ! preg_match( '/.+>(?=(?:(?:(?:[^"\\\\]++|\\\\.)*+"){2})*+(?:[^"\\\\]++|\\\\.)*+\z)/U', $str, $matches ) ) {
		return $str;
	}

	// grab the matching tag
	$tag = $matches[0];

	// replace the existing attribute value with its current value and the new one appended
	$tag = preg_replace( '/(.*)' . preg_quote( $attr ) . '="((?:[^"\\\\]|\\\\.)*)"(.*)/i', '$1' . $attr . '="$2 ' . $value . '"$3', $tag, 1, $count );

	// check the number of replacements. if 0 then we know the attribute did not exist or the value we want to add is already present
	// check if the value already exists in the attribute
	if ( $count === 0 && ! preg_match( '/' . preg_quote( $attr ) . '="((?:[^"\\\\]|\\\\.)*)' . preg_quote( $value ) . '((?:[^"\\\\]|\\\\.)*)"/i', $tag ) ) {
		// at this point we know the attribute does not exist and it needs to be added before the closing tag
		$tag = preg_replace( '/(.*)>/', '$1 ' . $attr . '="' . $value . '">', $tag, 1, $count );
	}

	// return the update HTML
	return str_replace( $matches[0], $tag, $str );
}

function supapress_extract_params_for_seo($id) {
    $extractedParams = array();
    $post = get_post( $id );
    if( isset( $post->post_content ) ) {

        preg_match( '/\[supapress\s(.*)\]/', $post->post_content, $res );
        if( !isset( $res[0] ) ) {
            return $extractedParams;
        }

        preg_match_all( '/[a-z_]+="[a-zA-Z0-9 ]+"/', $res[0], $paramMatches );

        $params = array();
        if( isset( $paramMatches[0] ) ) {
            foreach( $paramMatches[0] as $match ) {
                $kb = explode( "=", $match );
                if( isset( $kb[0] ) && isset( $kb[1] ) ) {
                    $params[$kb[0]] = str_replace( '"', '', $kb[1] );
                }
            }
        }

        $extractedParams = SupaPress_Widget::setAdditionalParams( $extractedParams, $params );
    }

    return $extractedParams;
}