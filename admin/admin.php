<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once SUPAPRESS_PLUGIN_DIR . '/admin/functions.php';

require_once SUPAPRESS_PLUGIN_DIR . '/admin/includes/help-tabs.php';

require_once SUPAPRESS_PLUGIN_DIR . '/includes/filters.php';

add_action( 'admin_menu', 'supapress_add_menu' );

add_action( 'admin_init', 'supapress_register_settings' );

add_action( 'supapress_admin_notices', 'supapress_admin_updated_message' );

add_filter( 'plugin_action_links_' . SUPAPRESS_PLUGIN_BASENAME, 'supapress_add_action_links' );

function supapress_add_menu() {
	// Control access to Supapress menu/sub-menus depending on SUPAPRESS_CAPABILITES
	add_menu_page( 'Supafolio', 'Supafolio', SUPAPRESS_CAPABILITIES, 'supapress', 'supapress_list', SUPAPRESS_PLUGIN_URL . '/admin/img/icon.png' );

	add_action( 'load-' . add_submenu_page( 'supapress', 'Edit Supafolio module', 'Modules', SUPAPRESS_CAPABILITIES, 'supapress', 'supapress_list' ), 'supapress_add_page_load' );

	add_action( 'load-' . add_submenu_page( 'supapress', 'Add New Supafolio module', 'Add New', SUPAPRESS_CAPABILITIES, 'supapress-new', 'supapress_add_new_widget' ), 'supapress_add_page_load' );

	add_submenu_page( 'supapress', 'Supafolio Settings', 'Settings', SUPAPRESS_CAPABILITIES, 'supapress-settings', 'supapress_settings' );
}

add_filter( 'set-screen-option', 'supapress_set_screen_options', 10, 3 );

function supapress_set_screen_options( $result, $option, $value ) {
	$supapress_screens = array( 'supapress_widgets_per_page' );

	if ( in_array( $option, $supapress_screens ) ) {
		$result = $value;
	}

	return $result;
}

add_action( 'admin_enqueue_scripts', 'supapress_admin_enqueue_scripts' );

function supapress_admin_enqueue_scripts( $hook ) {
	wp_enqueue_style( 'supapress-admin-overrides', supapress_plugin_url( 'admin/css/admin-overrides.min.css' ), array(), SUPAPRESS_VERSION, 'all' );

	// add the media shortcode button to all post type edit pages
	wp_enqueue_style( 'supapress-admin-select2', supapress_plugin_url( 'admin/css/select2.min.css' ), array(), SUPAPRESS_VERSION, 'all' );
	wp_enqueue_script( 'supapress-admin-select2', supapress_plugin_url( 'admin/js/select2.min.js' ), array( 'jquery' ), SUPAPRESS_VERSION, true );
	wp_enqueue_script( 'supapress-admin-add-shortcode', supapress_plugin_url( 'admin/js/add-shortcode.min.js' ), array(), SUPAPRESS_VERSION, true );
	wp_enqueue_style( 'supapress-admin-add-shortcode', supapress_plugin_url( 'admin/css/add-shortcode.min.css' ), array(), SUPAPRESS_VERSION, 'all' );

	// Only add scripts to Supapress pages
	if ( ! preg_match( '/supapress/i', $hook ) ) {
		return;
	}

	if ( $hook !== 'toplevel_page_supapress' ) {
		add_filter( 'screen_options_show_screen', '__return_false' );
	}

	wp_enqueue_style( 'supapress-admin-asm', supapress_plugin_url( 'admin/css/jquery.asmselect.css' ), array(), SUPAPRESS_VERSION, 'all' );
	wp_enqueue_style( 'supapress-admin', supapress_plugin_url( 'admin/css/styles.min.css' ), array(), SUPAPRESS_VERSION, 'all' );

	wp_enqueue_style( 'thickbox' );
	wp_enqueue_script( 'media-upload' );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_script( 'jquery' );

	wp_enqueue_script( 'supapress-admin-svg4everybody', supapress_plugin_url( 'admin/js/svg4everybody.min.js' ), array(), SUPAPRESS_VERSION, true );
	wp_enqueue_script( 'supapress-admin-placeholder', supapress_plugin_url( 'admin/js/jquery.placeholder.min.js' ), array( 'jquery' ), SUPAPRESS_VERSION, true );
	wp_enqueue_script( 'supapress-admin-asm', supapress_plugin_url( 'admin/js/jquery.asmselect.js' ), array(
		'jquery',
		'jquery-ui-draggable',
		'jquery-ui-droppable',
		'jquery-ui-sortable'
	), SUPAPRESS_VERSION, true );
	wp_enqueue_script( 'supapress-admin', supapress_plugin_url( 'admin/js/scripts.min.js' ), array(
		'jquery',
		'jquery-ui-tooltip',
		'supapress-admin-placeholder',
		'supapress-admin-asm',
		'jquery-ui-autocomplete'
	), SUPAPRESS_VERSION, true );
}

add_action( 'admin_enqueue_scripts', 'supapress_admin_enqueue_widget_scripts' );

function supapress_admin_enqueue_widget_scripts( $hook ) {
	if ( $hook === 'widgets.php' ) {
		wp_enqueue_script( 'widget', supapress_plugin_url( 'admin/js/widgets.min.js' ), array(), SUPAPRESS_VERSION, true );
	}
}

function supapress_list() {
	if ( ( $post = supapress_get_current_contact_form() ) ) {
		$custom_templates = supapress_get_custom_templates_for_module();
		list( $search_restriction_publishers, $search_restriction_imprints, $search_restriction_series, $search_restriction_categories ) = supapress_get_search_restriction_options( array(
			'publisher',
			'imprint',
			'series',
			'category'
		) );
		include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/add-edit-config.php';
	} else {
		$list_table = new SupaPress_Widget_List_Table();
		$list_table->prepare_items();

		include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/list.php';
	}
}

function supapress_add_new_widget() {
	$custom_templates = supapress_get_custom_templates_for_module();
	list( $search_restriction_publishers, $search_restriction_imprints, $search_restriction_series, $search_restriction_categories ) = supapress_get_search_restriction_options( array(
		'publisher',
		'imprint',
		'series',
		'category'
	) );
	include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/add-edit-config.php';
}

function supapress_settings() {
	$clearCacheAllowed = apply_filters('supapress_filters_set_clear_cache_perms', true);
	$pageList = '<option value="-1">Link not in use</option><option value="-2">External link</option>';

	// Get a list of WP pages containing a Supapress shortcode
	foreach ( get_pages() as $p ) {
		$pageList .= '<option value="' . $p->ID . '">' . $p->post_title . '</option>';
	}

	include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/settings.php';
}

function supapress_register_settings() {
	register_setting( 'supapress-settings', 'api_key' );
	register_setting( 'supapress-settings', 'no_books' );
	register_setting( 'supapress-settings', 'no_book' );
	register_setting( 'supapress-settings', 'service_url' );
	register_setting( 'supapress-settings', 'widget_book_link_page' );
	register_setting( 'supapress-settings', 'widget_book_link_pattern' );
	register_setting( 'supapress-settings', 'widget_retailer_name' );
	register_setting( 'supapress-settings', 'retailer_image' );
	register_setting( 'supapress-settings', 'widget_retailer_link_pattern' );
	register_setting( 'supapress-settings', 'widget_retailer_tracking_pattern' );
	register_setting( 'supapress-settings', 'retailer_link_formats' );
	register_setting( 'supapress-settings', 'product_details_cache_lifetime' );
	register_setting( 'supapress-settings', 'search_results_cache_lifetime' );
	register_setting( 'supapress-settings', 'isbn_lookups_cache_lifetime' );
	register_setting( 'supapress-settings', 'product_details_seo_override' );
	register_setting( 'supapress-settings', 'product_details_seo_title' );
	register_setting( 'supapress-settings', 'product_details_seo_description' );
	register_setting( 'supapress-settings', 'product_details_seo_canonical' );
	register_setting( 'supapress-settings', 'product_details_seo_primary_format_canonical' );
	register_setting( 'supapress-settings', 'angularjs_support' );
}

function supapress_admin_updated_message() {
	$updated_message = '';
	$class           = 'hide';

	if ( ! empty( $_REQUEST['message'] ) || ! empty( $_REQUEST['settings-updated'] ) ) {
		if ( ! empty( $_REQUEST['settings-updated'] ) ) {
			$updated_message = esc_html( 'Supafolio settings updated.' );
			$class           = 'success';
		} elseif ( 'created' == $_REQUEST['message'] ) {
			$updated_message = esc_html( 'Supafolio module created.' );
			$class           = 'success';
		} elseif ( 'saved' == $_REQUEST['message'] ) {
			$updated_message = esc_html( 'Supafolio module updated.' );
			$class           = 'success';
		} elseif ( 'deleted' == $_REQUEST['message'] ) {
			$updated_message = esc_html( 'Supafolio module deleted.' );
			$class           = 'success';
		}

		if ( $updated_message !== '' ) {
			if ( isset( $_REQUEST['post'] ) && $post_id = (int) $_REQUEST['post'] ) {
				$post = get_post( $post_id );
				if ( ! empty( $post->post_title ) ) {
					$updated_message .= ' <input readonly="readonly" onfocus="this.select();" class="wp-ui-text-highlight supapress-shortcode code" value="[supapress id=&quot;' . $post_id . '&quot; title=&quot;' . $post->post_title . '&quot;]"/>';
				}
			}
		}
	}

	echo "<div id='message' class='{$class} inline'><p>{$updated_message}</p></div>";
}

function supapress_add_action_links( $links ) {
	$supapress_links = array(
		'<a href="' . admin_url( 'admin.php?page=supapress' ) . '">Supafolio</a>',
		'<a href="' . admin_url( 'admin.php?page=supapress-settings' ) . '">Settings</a>'
	);

	return array_merge( $supapress_links, $links );
}

function supapress_add_page_load() {
	global $plugin_page;

	$action         = supapress_current_action();
	$current_screen = get_current_screen();
	$help_tabs      = new SupaPress_Help_Tabs( $current_screen );
	$post           = null;

	$_GET['post'] = isset( $_GET['post'] ) ? $_GET['post'] : '';

	if ( $action === 'add' ) {
		$id = supapress_save_widget();

		$query = array(
			'message' => 'created',
			'post'    => $id
		);

		$redirect_to = add_query_arg( $query, menu_page_url( 'supapress', false ) );

		wp_safe_redirect( $redirect_to );
	} elseif ( $action === 'edit' && ! empty( $_POST ) ) {
		$id = supapress_save_widget( $_POST['postId'] );

		$query = array(
			'message' => 'saved',
			'post'    => $id
		);

		$redirect_to = add_query_arg( $query, menu_page_url( 'supapress', false ) );

		wp_safe_redirect( $redirect_to );
	} elseif ( 'copy' == $action ) {
		$id = absint( $_GET['post'] );

		check_admin_referer( 'supapress-copy-widget_' . $id );

		if ( ! current_user_can( 'publish_pages', $id ) ) {
			wp_die( 'You are not allowed to edit this item.' );
		}

		$query = array();

		if ( $widget = supapress_widget( $id ) ) {
			$new_widget = $widget->copy();
			$new_widget->save();

			$query['post']    = $new_widget->id();
			$query['message'] = 'created';
		}

		$redirect_to = add_query_arg( $query, menu_page_url( 'supapress', false ) );

		wp_safe_redirect( $redirect_to );
	} elseif ( 'delete' == $action ) {
		if ( ! empty( $_POST['postId'] ) ) {
			check_admin_referer( 'supapress-delete-widget_' . $_POST['postId'] );
		} elseif ( ! is_array( $_REQUEST['post'] ) ) {
			check_admin_referer( 'supapress-delete-widget_' . $_REQUEST['post'] );
		} else {
			check_admin_referer( 'bulk-posts' );
		}

		$posts = empty( $_POST['postId'] )
			? (array) $_REQUEST['post']
			: (array) $_POST['postId'];

		$deleted = 0;

		foreach ( $posts as $post ) {
			/** @type SupaPress_Widget $post */
			$post = SupaPress_Widget::get_instance( $post );

			if ( empty( $post ) ) {
				continue;
			}

			if ( ! current_user_can( 'publish_pages', $post->id() ) ) {
				wp_die( 'You are not allowed to delete this item.' );
			}

			if ( ! $post->delete() ) {
				wp_die( 'Error in deleting.' );
			}

			$deleted += 1;
		}

		$query = array();

		if ( ! empty( $deleted ) ) {
			$query['message'] = 'deleted';
		}

		$redirect_to = add_query_arg( $query, menu_page_url( 'supapress', false ) );

		wp_safe_redirect( $redirect_to );
	} else {
		if ( ! empty( $_GET['post'] ) ) {
			$post = SupaPress_Widget::get_instance( $_GET['post'] );
		}

		if ( $post ) {
			$help_tabs->set_help_tabs( 'edit' );
		} elseif ( $plugin_page === 'supapress-new' ) {
			$help_tabs->set_help_tabs( 'add' );
		} else {
			$help_tabs->set_help_tabs( 'list' );
		}

		if ( ! class_exists( 'SupaPress_Widget_List_Table' ) ) {
			require_once SUPAPRESS_PLUGIN_DIR . '/admin/includes/widget-list-table.php';
		}

		add_filter( 'manage_' . $current_screen->id . '_columns',
			array( 'SupaPress_Widget_List_Table', 'define_columns' ) );

		add_screen_option( 'per_page', array(
			'label'   => 'Modules',
			'default' => 20,
			'option'  => 'supapress_widgets_per_page'
		) );


		// filter for listing
		add_filter( 'parse_query', 'supapress_filter_widget_type' );
		function supapress_filter_widget_type( $query ) {
			global $pagenow;
			$qv = &$query->query_vars;

			if ( $pagenow == 'admin.php' &&
			     isset( $_GET['page'] ) && $_GET['page'] === 'supapress' &&
			     ! empty( $_GET['supapress_widget_type'] )
			) {
				$qv['meta_query'] = array(
					array(
						'key'   => '_widget_type',
						'value' => $_GET['supapress_widget_type']
					)
				);
			}
		}
	}
}

// function named with _ajax to differentiate from the set of cache functions as these won't output JSON
function supapress_cache_clear_ajax() {
	header( 'Content-Type: application/json' );
	die( json_encode( array( 'filesRemoved' => supapress_cache_clear() ) ) );
}

// look up ISBNs to check which ones to add to list
function supapress_bulk_isbn_lookup_ajax() {
	$ISBNs = esc_html( $_POST['ISBNs'] );

	/** @type object $results */
	$result = supapress_call_supafolio( 'search', array(
		'isbns'  => $ISBNs,
		'amount' => 100
	), array(), false );

	// loop through the ISBNs passed and set all to not found (-1), and if not an ISBN throw away
	$returnResults = array();
	foreach ( explode( ',', $ISBNs ) as $isbn ) {
		if ( preg_match( '/^([0-9]{13})$/', $isbn ) ) {
			$returnResults[ $isbn ] = array( 'title' => 'ISBN not found', 'found' => - 1 );
		}
	}

	if ( isset( $result->search ) ) {
		foreach ( $result->search as $book ) {
			$returnResults[ $book->isbn13 ] = array( 'title' => $book->title, 'found' => 1 );
		}
	}

	header( 'Content-Type: application/json' );
	die( json_encode( array( 'ISBNs' => $returnResults ) ) );
}

// get list of all modules for module list in BB / Widgets
function supapress_get_module_list_ajax() {
	$params = array(
		'posts_per_page' => - 1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_type'      => 'supapress_widget',
	);

	if ( ! empty( $_POST['module_type'] ) ) {
		$params['meta_query'] = array(
			array(
				'key'   => '_widget_type',
				'value' => $_POST['module_type']
			)
		);
	}
	// get all the posts
	$posts = get_posts( $params );

	wp_send_json( $posts );
	wp_die();
}

add_action( 'wp_ajax_supapress_predictive', 'supapress_predictive' );
add_action( 'wp_ajax_nopriv_supapress_predictive', 'supapress_predictive' );
add_action( 'wp_ajax_supapress_cache_clear', 'supapress_cache_clear_ajax' );
add_action( 'wp_ajax_supapress_bulk_isbn_lookup', 'supapress_bulk_isbn_lookup_ajax' );
add_action( 'wp_ajax_supapress_get_module_list', 'supapress_get_module_list_ajax' );

function supapress_predictive() {
	/** @type object $results */
	$results = supapress_call_supafolio( 'predictive', array(
		'keyword' => esc_html( $_REQUEST['term'] ),
		'amount'  => 10,
		'type'    => 'Products'
	), array(), false );

	header( 'Content-Type: application/json' );
	die( json_encode( $results ) );
}

add_action( 'wp_ajax_supapress_isbn_lookup', 'supapress_isbn_lookup' );
add_action( 'wp_ajax_nopriv_supapress_isbn_lookup', 'supapress_isbn_lookup' );

function supapress_isbn_lookup() {
	/** @type object $results */
	$results = supapress_call_supafolio( 'search', array(
		'isbns'  => esc_html( $_REQUEST['isbn'] ),
		'amount' => 1
	), array(), false );

	header( 'Content-Type: application/json' );
	die( json_encode( $results ) );
}

add_action( 'wp_ajax_supapress_collections', 'supapress_collections' );
add_action( 'wp_ajax_nopriv_supapress_collections', 'supapress_collections' );

function supapress_collections() {
    /** @type object $results */
    $collections = supapress_call_supafolio( 'searchfilter', array(
        'filters' => 'collection'
    ) );

    header( 'Content-Type: application/json' );
    die( json_encode( $collections ) );
}

add_action( 'wp_ajax_supapress_filters', 'supapress_filters' );
add_action( 'wp_ajax_nopriv_supapress_filters', 'supapress_filters' );

function supapress_filters() {
	// Set params and filter list
	$params = isset( $_REQUEST['params'] ) ? $_REQUEST['params'] : array();

	// remove params not relevant to filters so that we get more cache hits
	unset( $params['order'] );
	unset( $params['amount'] );
	unset( $params['page'] );

    // filter for adding custom parameters to the supapress filters call
    $params = apply_filters('supapress_filters_modify_filter_params', $params);

	$properties                = array();
    $properties['showText']    = isset( $_REQUEST['showText'] ) ? htmlspecialchars( (string) $_REQUEST['showText'] ) : '';
    $properties['hideText']    = isset( $_REQUEST['hideText'] ) ? htmlspecialchars( (string) $_REQUEST['hideText'] ) : '';
    $properties['clearText']   = isset( $_REQUEST['clearText'] ) ? htmlspecialchars( (string) $_REQUEST['clearText'] ) : '';
	$properties['limits']      = isset( $_REQUEST['limits'] ) ? $_REQUEST['limits'] : array();
	$properties['filtersList'] = isset( $_REQUEST['filters'] ) ? $_REQUEST['filters'] : array();
	$properties['params']      = $params;

	if ( ! empty( $properties['filtersList'] ) ) {
		/** @type object $results */
		$results = supapress_call_supafolio( 'searchfilter', $params );

		if ( isset( $results->filters ) ) {
			$filter = supapress_render_filters( $results->filters, $properties, $params );
		} else {
			$filter = "No filters found for your data.";
		}
	} else {
		$filter = "No filters selected in your module configuration.";
	}


	die( $filter );
}

add_action( 'wp_ajax_supapress_new_retailer_link', 'supapress_new_retailer_link' );
add_action( 'wp_ajax_nopriv_supapress_new_retailer_link', 'supapress_new_retailer_link' );

function supapress_new_retailer_link() {
	die( supapress_get_retailer_link( true, esc_html( $_POST['count'] ) ) );
}

add_action( 'edit_form_after_editor', 'supapress_add_shortcode_panel', 100 );
/**
 * Panel for the add shortcode media button.
 *
 * Prints the panel for choosing a SupaFolio for WordPress widget to insert as a shortcode in a page or post.
 *
 * @since 3.0.0
 */
function supapress_add_shortcode_panel() {
	$args    = array(
		'posts_per_page' => '-1',
		'orderby'        => 'title',
		'order'          => 'ASC'
	);
	$modules = SupaPress_Widget::find( $args );
	include SUPAPRESS_PLUGIN_DIR . '/admin/views/add-module-shortcode-panel.php';
}

/**
 * Look for and return a list of custom templates that have been added in the theme
 *
 * @return array|bool
 */
function supapress_get_custom_templates_for_module() {
	// main theme files
	$dir   = get_template_directory() . '/plugins/supapress/views/templates/';
	$files = glob( $dir . '{product-details,isbn-lookup,search-results}-*.php', GLOB_BRACE );
	if ( get_template_directory() !== get_stylesheet_directory() ) {
		// child theme files
		$dir    = get_stylesheet_directory() . '/plugins/supapress/views/templates/';
		$files2 = glob( $dir . '{product-details,isbn-lookup,search-results}-*.php', GLOB_BRACE );
		// merge files from both folders
		if ( is_array( $files ) && is_array( $files2 ) ) {
			$files = array_merge( $files, $files2 );
		}
	}

	$templateKeys = array(
		'name' => 'Template Name'
	);

	$theme      = get_template();
	$childTheme = $theme !== get_stylesheet() ? get_stylesheet() : get_template();
	$list       = array();

	foreach ( $files as $file ) {
		$template = get_file_data( $file, $templateKeys );
		preg_match( '/^(product-details|isbn-lookup|search-results)/', basename( $file ), $matches );
		$type = str_replace( '-', '_', $matches[1] );
		if ( empty( $template['name'] ) ) {
			$template['name'] = basename( $file );
		}

		$template['path'] = str_replace( ABSPATH, '', $file );

		if ( isset( $list[ $type ][ $template['name'] ] ) ) {
			if ( ! isset( $list[ $type ][ $template['name'] ]['name_fixed'] ) ) {
				$list[ $type ][ $template['name'] ]['name']       .= ' (' . supapress_get_custom_template_theme_name( $list[ $type ][ $template['name'] ]['path'], $theme, $childTheme ) . basename( $list[ $type ][ $template['name'] ]['path'] ) . ')';
				$list[ $type ][ $template['name'] ]['name_fixed'] = true;
			}
			$template['name'] .= ' (' . supapress_get_custom_template_theme_name( $file, $theme, $childTheme ) . basename( $template['path'] ) . ')';
		}

		$list[ $type ][ $template['name'] ] = $template;
	}

	// fix the order as the names may have changed
	foreach ( $list as &$type ) {
		$type_list = array();
		foreach ( $type as $template ) {
			$type_list[ $template['name'] ] = $template;
		}
		ksort( $type_list );
		$type = $type_list;
	}

	return empty( $list ) ? false : $list;
}

function supapress_get_custom_template_theme_name( $file, $theme, $childTheme ) {
	// if it is child theme
	if ( $theme !== $childTheme ) {
		if ( str_replace( get_stylesheet_directory() . '/', '', $file ) !== $file ) {
			$themeName = $childTheme;
		} else {
			$themeName = $theme;
		}

		$themeName .= ': ';
	} else {
		$themeName = '';
	}

	return $themeName;
}

function supapress_get_search_restriction_options( $filters = array() ) {
	$results = supapress_call_supafolio( 'searchfilter', array( 'filters' => implode( ',', $filters ) ), false );
	$lists   = array_flip( $filters );

	foreach ( $lists as $filter => &$values ) {
		if ( ! empty( $results->filters->{$filter}->values ) ) {
			$values = $results->filters->{$filter}->values;
		}
	}

	return array_values( $lists );
}
