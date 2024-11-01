<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function supapress_current_action() {
	// when dealing with bulk actions, wp_list_tables selects output -1 if nothing selected
	// should fall back to action2 (bottom bulk action) if action is empty, otherwise nothing set
	if ( isset( $_POST['action'] ) && $_POST['action'] !== '-1' ) {
		return $_POST['action'];
	} elseif ( isset( $_GET['action'] ) && $_GET['action'] !== '-1' ) {
		return $_GET['action'];
	} elseif ( isset( $_POST['action2'] ) && $_POST['action2'] !== '-1' ) {
		return $_POST['action2'];
	} elseif ( isset( $_GET['action2'] ) && $_GET['action2'] !== '-1' ) {
		return $_GET['action2'];
	}

	return false;
}

function supapress_current_page() {
	return isset( $_GET['page'] ) && $_GET['page'] === 'supapress-new' ? 'add' : 'edit';
}

function supapress_get_book_urls(
	/** @noinspection used in admin/views/settings-partials/book-url.php PhpUnusedParameterInspection */
	$pageList
) {
	$linkPages    = get_option( 'widget_book_link_page' );
	$linkPatterns = get_option( 'widget_book_link_pattern' );
	$total        = empty( $linkPages ) ? 2 : count( $linkPages );

	for ( $i = 0; $i < $total; $i ++ ) {
		/** @noinspection used in admin/views/settings-partials/book-url.php PhpUnusedLocalVariableInspection */
		$linkPage = isset( $linkPages[ $i ] ) ? $linkPages[ $i ] : '';
		/** @noinspection used in admin/views/settings-partials/book-url.php PhpUnusedLocalVariableInspection */
		$linkPattern = isset( $linkPatterns[ $i ] ) ? $linkPatterns[ $i ] : '';
		/** @noinspection used in admin/views/settings-partials/book-url.php PhpUnusedLocalVariableInspection */
		$current = $i + 1;
		include SUPAPRESS_PLUGIN_DIR . '/admin/views/settings-partials/book-url.php';
	}
}

function supapress_get_retailer_link($new = false, $count = 0) {
	$result = supapress_call_supafolio( 'searchfilter', array( 'filters' => 'format' ), array(), false );
	$formats = isset( $result->filters ) && isset( $result->filters->format ) && isset( $result->filters->format->values ) ? $result->filters->format->values : array();
	
    if( $new ) {
        $retailerName = $retailerImage = $retailerLinkPattern = $retailerTrackingPattern = "";
        $retailerLinkFormats = array();
		$current = $count + 1;
		include SUPAPRESS_PLUGIN_DIR . '/admin/views/settings-partials/retailer-link.php';
		return;
    }
    
    $retailerNames = get_option( 'widget_retailer_name' );
	$retailerImages = get_option( 'retailer_image' );
	$retailerLinkPatterns = get_option( 'widget_retailer_link_pattern' );
	$retailerTrackingPatterns = get_option( 'widget_retailer_tracking_pattern' );
	$retailerLinkFormats = get_option( 'retailer_link_formats' );
	$totalRetailers = is_array($retailerNames) ? count( $retailerNames ) : 0;
    
	for ( $i = 0; $i < $totalRetailers; $i ++ ) {
		$retailerName = isset( $retailerNames[ $i ] ) ? $retailerNames[ $i ] : "";
		$retailerImage = isset( $retailerImages[ $i ] ) ? $retailerImages[ $i ] : '';
		$retailerLinkPattern = isset( $retailerLinkPatterns[ $i ] ) ? $retailerLinkPatterns[ $i ] : '';
		$retailerTrackingPattern = isset( $retailerTrackingPatterns[ $i ] ) ? $retailerTrackingPatterns[ $i ] : '';
		$current = $i + 1;
		include SUPAPRESS_PLUGIN_DIR . '/admin/views/settings-partials/retailer-link.php';
    	
    }
}

function supapress_get_book_pattern_list() {
	$linkPages    = get_option( 'widget_book_link_page' );
	$linkPatterns = get_option( 'widget_book_link_pattern' );
	$options      = array( '<option value="-1">Do not add a link</option>' );
	$redirect_to  = add_query_arg( array(), menu_page_url( 'supapress-settings', false ) );

	if ( empty( $linkPatterns ) ) {
		return "<span class='supapress-paragraph'>No links found, please first set up your links on the <a href='{$redirect_to}'>settings page</a>.</span>";
	} else {
		foreach ( $linkPatterns as $key => $value ) {
			// Page set to link not in use so skip
			if ( isset( $linkPages[ $key ] ) && $linkPages[ $key ] === "-1" ) {
				continue;
			}

			$options[] = '<option value="' . $key . '">' . supapress_translate_template_url( $value ) . '</option>';
		}
	}

	return $options;
}

function supapress_url_pattern_label($for, $label) {
    include SUPAPRESS_PLUGIN_DIR . '/admin/views/url-pattern-label.php';
}

function supapress_save_widget( $post_id = - 1 ) {
	if ( - 1 != $post_id ) {
		$widget = supapress_widget( $post_id );
	}

	if ( empty( $widget ) ) {
		$widget = SupaPress_Widget::get_template();
	}

	if ( isset( $_POST['title'] ) ) {
		$widget->set_title( sanitize_text_field( $_POST['title'] ) );
	}

	if ( isset( $_POST['widget_layout'] ) && $_POST['widget_layout'] === 'carousel' ) {
		$_POST['carousel_settings'] = array();

		if ( isset( $_POST['left_arrow_image'] ) ) {
			$_POST['carousel_settings']['left_arrow_image'] = sanitize_text_field( $_POST['left_arrow_image'] );
		}

		if ( isset( $_POST['right_arrow_image'] ) ) {
			$_POST['carousel_settings']['right_arrow_image'] = sanitize_text_field( $_POST['right_arrow_image'] );
		}

		if ( isset( $_POST['show_dots'] ) ) {
			$_POST['carousel_settings']['show_dots'] = $_POST['show_dots'];
		}

		if ( isset( $_POST['show_arrows'] ) ) {
			$_POST['carousel_settings']['show_arrows'] = $_POST['show_arrows'];
		}

		if ( isset( $_POST['infinite_scroll'] ) ) {
			$_POST['carousel_settings']['infinite_scroll'] = $_POST['infinite_scroll'];
		}

		if ( isset( $_POST['auto_play'] ) ) {
			$_POST['carousel_settings']['auto_play'] = $_POST['auto_play'];
		}

		if ( isset( $_POST['auto_play_speed'] ) ) {
			$_POST['carousel_settings']['auto_play_speed'] = $_POST['auto_play_speed'];
		}

		if ( isset( $_POST['speed'] ) ) {
			$_POST['carousel_settings']['speed'] = $_POST['speed'];
		}

		if ( isset( $_POST['number_to_show'] ) ) {
			$_POST['carousel_settings']['number_to_show'] = $_POST['number_to_show'];
		}

		if ( isset( $_POST['number_to_scroll'] ) ) {
			$_POST['carousel_settings']['number_to_scroll'] = $_POST['number_to_scroll'];
		}

		if ( isset( $_POST['lazy_load'] ) ) {
			$_POST['carousel_settings']['lazy_load'] = $_POST['lazy_load'];
		}

		if ( isset( $_POST['lazy_load_placeholder'] ) ) {
			$_POST['carousel_settings']['lazy_load_placeholder'] = sanitize_text_field( $_POST['lazy_load_placeholder'] );
		}
	}

	if ( ! isset( $_POST['price'] ) ) {
		$_POST['price'] = array();
	}

	if ( ! isset( $_POST['filters'] ) ) {
		$_POST['filters'] = array();
	}

	if ( ! isset( $_POST['sort_by'] ) ) {
		$_POST['sort_by'] = array();
	}

	if ( ! isset( $_POST['per_page'] ) ) {
		$_POST['per_page'] = array();
	}

	if ( $_POST['widget_type'] === 'isbn_lookup' ) {
		$isbnList = array();

		if ( isset( $_POST['isbn_list'] ) && is_array( $_POST['isbn_list'] ) ) {
			foreach ( $_POST['isbn_list'] as $isbn ) {
				$temp                 = explode( '|||', $isbn );
				$isbnList[ $temp[0] ] = ( ! empty( $temp[1] ) ? $temp[1] : $temp[0] );
			}
		}

		$_POST['isbn_list'] = $isbnList;
	}

	if ( ! isset( $_POST['search_restriction_publisher'] ) ) {
		$_POST['search_restriction_publisher'] = array();
	}

	if ( ! isset( $_POST['search_restriction_imprint'] ) ) {
		$_POST['search_restriction_imprint'] = array();
	}
	
	if ( ! isset( $_POST['search_restriction_series'] ) ) {
		$_POST['search_restriction_series'] = array();
	}

	if ( ! isset( $_POST['search_restriction_categories'] ) ) {
		$_POST['search_restriction_categories'] = array();
	}
	
	if ( ! isset( $_POST['retailer_links'] ) ) {
		$_POST['retailer_links'] = array();
	}
	

	$properties = $widget->get_properties();

	foreach ( $properties as $key => $value ) {
		if ( isset( $_POST[ $key ] ) ) {
			if ( is_array( $_POST[ $key ] ) ) {
				$properties[ $key ] = $_POST[ $key ];
			} else {
				$properties[ $key ] = trim( $_POST[ $key ] );
			}
		}
	}

	$widget->set_properties( $properties );

	return $widget->save();
}