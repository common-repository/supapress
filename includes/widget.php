<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SupaPress_Widget {

	const post_type = 'supapress_widget';

	private static $found_items = 0;
	private static $current = null;

	private $id;
	private $name;
	private $title;
	private $properties = array();

	private function __construct( $post = null ) {
		$post = get_post( $post );

		if ( $post && self::post_type == get_post_type( $post ) ) {
			$this->id    = $post->ID;
			$this->name  = $post->post_name;
			$this->title = $post->post_title;

			$properties = $this->get_properties();

			foreach ( $properties as $key => $value ) {
				if ( metadata_exists( 'post', $post->ID, '_' . $key ) ) {
					$properties[ $key ] = get_post_meta( $post->ID, '_' . $key, true );
				} elseif ( metadata_exists( 'post', $post->ID, $key ) ) {
					$properties[ $key ] = get_post_meta( $post->ID, $key, true );
				}
			}

			$this->properties = $properties;
		}

		do_action( 'supapress_widget', $this );
	}

	public function get_properties() {
		$properties = (array) $this->properties;

		$properties = wp_parse_args( $properties, array(
			'atts'                         => array(),
			'widget_type'                  => array(),
			'widget_layout'                => array(),
			'custom_layout_file'           => array(),
			'lookup_source'                => array(),
			'isbn_list'                    => array(),
			'seo_title'                    => '',
			'lookup_collection'            => array(),
			'per_row'                      => array(),
			'order'                        => array(),
			'price'                        => array(),
			'cover_right'                  => array(),
			'show_filters'                 => array(),
			'filters'                      => array(),
			'show_sort_by'                 => array(),
			'sort_by'                      => array(),
			'per_page'                     => array( 5, 10, 15, 20, 25, 50, 75, 100 ),
			'per_page_default'             => '10',
			'show_cover'                   => array(),
			'show_per_page'                => array(),
			'show_pagination'              => array(),
			'show_result_count'            => 'off',
			'result_count_text'            => '',
			'show_search_term'             => 'off',
			'search_term_text'             => '',
			'cover_link'                   => '',
			'cover_link_target'            => '',
			'hide_1_page_pagination'       => 'on',
			'show_title'                   => array(),
			'title_link'                   => '',
			'title_link_target'            => '',
			'show_subtitle'                => array(),
			'show_format'                  => array(),
			'show_author'                  => array(),
			'show_author_bio'              => array(),
			'show_edition'                 => array(),
			'show_pubdate'                 => array(),
			'pub_date_format'              => 'Y-m-d',
			'show_sales_date'              => 'off',
			'sales_date_format'            => 'Y-m-d',
			'show_summary'                 => array(),
			'show_description'             => array(),
			'show_price'                   => array(),
			'show_series'                  => array(),
			'show_imprint'                 => array(),
			'show_publisher'               => array(),
			'show_isbn13'                  => array(),
			'show_trimsize'                => array(),
			'show_weight'                  => array(),
			'show_awards'                  => array(),
			'show_reviews'                 => array(),
			'show_pages'                   => array(),
			'show_retailers'               => array(),
			'carousel_settings'            => array(),
			'lazy_load'                    => 'off',
			'angular_support'              => '',
			'search_restriction_publisher' => array(),
			'search_restriction_imprint'   => array(),
			'search_restriction_series'   => array(),
			'search_restriction_categories' => array(),
			'retailer_links'   => array()
		) );

		$properties = (array) apply_filters( 'supapress_widget_properties', $properties, $this );

		return $properties;
	}

	public function set_properties( $properties ) {
		$defaults = $this->get_properties();

		$properties = wp_parse_args( $properties, $defaults );
		$properties = array_intersect_key( $properties, $defaults );

		$this->properties = $properties;
	}

	public static function count() {
		return self::$found_items;
	}

	public static function get_current() {
		return self::$current;
	}

	public static function register_post_type() {
		register_post_type( self::post_type, array(
			'labels' => array(
				'name'          => 'SupaPress Widgets',
				'singular_name' => 'SupaPress Widget'
			)
		) );
	}

	public static function find( $args = '' ) {
		$defaults = array(
			'post_status'    => 'any',
			'posts_per_page' => - 1,
			'offset'         => 0,
			'orderby'        => 'ID',
			'order'          => 'ASC'
		);

		$args              = wp_parse_args( $args, $defaults );
		$args['post_type'] = self::post_type;

		// Previously, changed the order field to be meta_value to go with this
		if ( $args['orderby'] === 'meta_value' ) {
			$args['meta_key'] = '_widget_type';
		}

		$q     = new WP_Query();
		$posts = $q->query( $args );

		self::$found_items = $q->found_posts;

		$objs = array();

		foreach ( (array) $posts as $post ) {
			$objs[] = new self( $post );
		}

		return $objs;
	}

	public static function get_template( $args = '' ) {
		$defaults = array( 'title' => '' );
		$args     = wp_parse_args( $args, $defaults );

		$title = $args['title'];

		self::$current = $widget = new self;

		$widget->title = $title ? $title : 'Untitled';

		$properties = $widget->get_properties();

		foreach ( $properties as $key => $value ) {
			$properties[ $key ] = SupaPress_WidgetTemplate::get_default( $key );
		}

		$widget->properties = $properties;

		$widget = apply_filters( 'supapress_widget_default_pack', $widget, $args );

		return $widget;
	}

	public static function get_instance( $post ) {
		$post = get_post( $post );

		if ( ! $post || self::post_type != get_post_type( $post ) ) {
			return false;
		}

		self::$current = $widget = new self( $post );

		return $widget;
	}

	public function __get( $name ) {
		$message = __( '<code>%1$s</code> property of a <code>SupaPress_Widget</code> object is <strong>no longer accessible</strong>. Use <code>%2$s</code> method instead.', 'supapress' );

		if ( 'id' == $name ) {
			if ( WP_DEBUG ) {
				trigger_error( sprintf( $message, 'id', 'id()' ) );
			}

			return $this->id;
		} elseif ( 'title' == $name ) {
			if ( WP_DEBUG ) {
				trigger_error( sprintf( $message, 'title', 'title()' ) );
			}

			return $this->title;
		} elseif ( $prop = $this->prop( $name ) ) {
			if ( WP_DEBUG ) {
				trigger_error(
					sprintf( $message, $name, 'prop(\'' . $name . '\')' ) );
			}

			return $prop;
		}

		return $name;
	}

	public function prop( $name ) {
		$props = $this->get_properties();

		return isset( $props[ $name ] ) ? $props[ $name ] : null;
	}

	public function id() {
		return $this->id;
	}

	public function name() {
		return $this->name;
	}

	public function title() {
		return $this->title;
	}

	public function set_title( $title ) {
		$title = trim( $title );

		if ( '' === $title ) {
			$title = 'Untitled';
		}

		$this->title = $title;
	}

	public function save() {
		$props = $this->get_properties();

		if ( $this->initial() ) {
			$post_id = wp_insert_post( array(
				'post_type'    => self::post_type,
				'post_status'  => 'publish',
				'post_title'   => $this->title,
				'post_content' => ''
			) );
		} else {
			$post_id = wp_update_post( array(
				'ID'           => (int) $this->id,
				'post_status'  => 'publish',
				'post_title'   => $this->title,
				'post_content' => ''
			) );
		}

		if ( $post_id ) {
			foreach ( $props as $prop => $value ) {
				update_post_meta( $post_id, '_' . $prop, $value );
			}

			if ( $this->initial() ) {
				$this->id = $post_id;
			}
		}

		return $this->id;
	}

	public function initial() {
		return empty( $this->id );
	}

	public function copy() {
		$new             = new self;
		$new->title      = $this->title . '_copy';
		$new->properties = $this->properties;

		return apply_filters( 'supapress_copy', $new, $this );
	}

	public function delete() {
		if ( $this->initial() ) {
			return false;
		}

		if ( wp_delete_post( $this->id, true ) ) {
			$this->id = 0;

			return true;
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function render() {
		$result  = null;
		$service = null;
		$params  = array();

		if ( $this->properties['widget_type'] === 'isbn_lookup' || $this->properties['widget_type'] === 'search_results' ) {
			$service = 'search';
			$params  = array();

			if ( $this->properties['widget_type'] === 'isbn_lookup' ) {
				$params['order'] = $this->properties['order'];

				// Set amount attribute if it has been added to the shortcode
				if ( isset( $this->properties['atts']['amount'] ) ) {
					$params['amount'] = $this->properties['atts']['amount'];
				}

				// Set source of lookup
				if ( isset( $this->properties['lookup_source'] ) && $this->properties['lookup_source'] === 'collection' ) {
					$params['collection'] = $this->properties['lookup_collection'];
				} else {
					$params['isbns'] = implode( ',', array_keys( $this->properties['isbn_list'] ) );
				}
			} else if ( $this->properties['widget_type'] === 'search_results' ) {
				if ( !empty( $_GET['keyword'] ) ) {
					$keyword = str_replace('\\', '', $_GET['keyword']);
					$params['keyword'] = sanitize_text_field( $keyword );
				}

				if ( isset( $this->properties['atts']['category'] ) ) {
					$params['category'] = $this->properties['atts']['category'];
				}

				$params = $this->setSearchFilterParams( $params, $this->properties );

				$params = $this->setSearchOrderParams( $params );

				$params = $this->setSearchPerPageParams( $params, $this->properties );

				$params = $this->setSearchPageParams( $params );
			}
		} elseif ( $this->properties['widget_type'] === 'product_details' ) {
			if ( preg_match( '/\d{13}/', get_query_var( 'supapress_isbn' ) ) ) {
				$service = 'book/' . get_query_var( 'supapress_isbn' );
				$params  = array();
			} else {
				$result = (string) get_option( 'no_book' ) !== '' ? get_option( 'no_book' ) : SUPAPRESS_DEFAULT_BOOK_NOT_FOUND_MESSAGE;
			}
		}

		if ( $result === null ) {
			// Set additional params if they've been added as attributes of the shortcode
			$params = $this->setAdditionalParams( $params, $this->properties );
			// Call Supafolio
			$result = $service !== null ? supapress_call_supafolio( $service, $params, $this->properties ) : "Something went wrong";
		}

		if ( is_string( $result ) && strtolower( $result ) !== 'something went wrong: no results' ) {
			return "<p>$result</p>";
		} else {
			$html = '<div class="supapress" data-widget-params="' . htmlspecialchars( json_encode( $params ), ENT_QUOTES ) . '" data-ajax-url="' . admin_url( 'admin-ajax.php' ) . '">';

			if ( isset( $this->properties['widget_type'] ) ) {
				if ( $this->properties['widget_layout'] === 'custom' && ( ! empty( $this->properties['custom_layout_file'] ) && $this->properties['custom_layout_file'] !== 'default' ) ) {
					$html .= supapress_render_custom_layout( $result, $this->properties, $params );
				} elseif ( $this->properties['widget_type'] === 'isbn_lookup' || $this->properties['widget_type'] === 'search_results' ) {
					if ( isset( $this->properties['widget_layout'] ) ) {
						if ( $this->properties['widget_type'] === 'isbn_lookup' ) {
							if ( $this->properties['widget_layout'] === 'list' ) {
								$html .= supapress_render_isbn_lookup_list( $result, $this->properties );
							} else if ( $this->properties['widget_layout'] === 'carousel' ) {
								$html .= supapress_render_isbn_lookup_carousel( $result, $this->properties );
							} else {
								$html .= supapress_render_isbn_lookup_grid( $result, $this->properties );
							}
						} else if ( $this->properties['widget_type'] === 'search_results' ) {
							if ( $this->properties['widget_layout'] === 'list' ) {
								$html .= supapress_render_search_results_list( $result, $this->properties, $params );
							} else {
								$html .= supapress_render_search_results_grid( $result, $this->properties, $params );
							}
						}
					}
				} elseif ( $this->properties['widget_type'] === 'product_details' ) {
					$html .= supapress_render_product_details( $result, $this->properties );
				}
			}

			$html .= '</div>';

			return $html;
		}
	}

	private function setSearchFilterParams( $params, $properties = array() ) {
		$filters = array(
			'format',
			'prices',
			'collection',
			'guides',
			'series',
			'imprint',
			'award',
			'category',
			'publisher',
			'type',
			'age',
			'contributor',
			'title',
			'isbns',
			'from_date',
			'to_date',
			'award_winner',
			'starts_with',
			'exclude_imprint',
			'exclude_category',
			'from',
			'to',
			'distinct',
			'primary_format'
		);

		if ( ! empty( $properties['search_restriction_imprint'] ) ) {
			$params['imprint'] = implode( ',', $properties['search_restriction_imprint'] );
		}

		if ( ! empty( $properties['search_restriction_publisher'] ) ) {
			$params['publisher'] = implode( ',', $properties['search_restriction_publisher'] );
		}

		if ( ! empty( $properties['search_restriction_series'] ) ) {
			$params['series'] = implode( ',', $properties['search_restriction_series'] );
		}

		if ( ! empty( $properties['search_restriction_categories'] ) ) {
			$params['category'] = implode( ',', $properties['search_restriction_categories'] );
		}

		foreach ( $filters as $filter ) {
			if ( isset( $_GET[ $filter ] ) ) {
				if ( in_array( $filter, array( 'from', 'to' ) ) && $params['order'] !== 'relevance') {
					continue;
				}

				$params[ $filter ] = sanitize_text_field($_GET[ $filter ]);
			}
		}

		return $params;
	}

	private function setSearchOrderParams( $params ) {
		if ( isset( $_GET['supapress_order'] ) ) {
			$params['order'] = $_GET['supapress_order'];
		} elseif ( isset( $_GET['order'] ) ) {
			$params['order'] = $_GET['order'];
		} elseif ( isset( $this->properties['show_sort_by'] ) && $this->properties['show_sort_by'] === 'on' && isset( $this->properties['sort_by'] ) && ! empty( $this->properties['sort_by'] ) ) {
			$params['order'] = $this->properties['sort_by'][0];
		}

		return $params;
	}

	private function setSearchPerPageParams( $params, $properties ) {
		if ( isset( $_GET['amount'] ) ) {
			$params['amount'] = (int) $_GET['amount'];
		} elseif ( isset( $properties['atts']['amount'] ) ) {
			$params['amount'] = $properties['atts']['amount'];
		} elseif ( isset( $this->properties['per_page_default'] ) && $this->properties['per_page_default'] != '' ) {
			$params['amount'] = (int) $this->properties['per_page_default'];
		} else {
			$params['amount'] = 10;
		}

		return $params;
	}

	private function setSearchPageParams( $params ) {
		if ( isset( $_GET['page_number'] ) ) {
			$params['page'] = $_GET['page_number'];
		} else {
			$params['page'] = 1;
		}

		return $params;
	}

	/**
	 * @param array $params
	 * @param array $atts
	 *
	 * @return array
	 *
	 * Checks attributes for known params and adds to service URL
	 */
	public static function setAdditionalParams( $params, $properties ) {

		$atts = $properties['atts'];

		if( isset( $properties['show_series'] ) && $properties['show_series'] === 'on' ) {
			$params['series_data'] = '1';
		}

		// Include price for other formats
		if ( isset( $atts['include_price'] ) ) {
			$params['include_price'] = 1;
		}

		// Include price promo price
		if ( isset( $atts['include_promo_price'] ) ) {
			$params['include_promo_price'] = 1;
		}

		// Set search type i.e. author search
		if ( isset( $atts['search_type'] ) ) {
			$params['search_type'] = $atts['search_type'];
		}

		// Ignore primary format rule and return all rules
		if ( isset( $atts['ignore_primary_format'] ) ) {
			$params['ignore_primary_format'] = 1;
		}

		// Bring back series data for search results
		if ( isset( $atts['series_data'] ) ) {
			$params['series_data'] = 1;
		}
                
		// Bring back imprint data for search results
		if ( isset( $atts['imprint_data'] ) ) {
			$params['imprint_data'] = 1;
		}

		// Bring back publisher data for search results
		if ( isset( $atts['publisher_data'] ) ) {
			$params['publisher_data'] = 1;
		}


		// Bring back category data for search results
		if ( isset( $atts['category_data'] ) ) {
			$params['category_data'] = 1;
		}

		// Set the locale
		if ( isset( $atts['locale'] ) ) {
			$params['locale'] = $atts['locale'];
		}

		// Bring back books where specific author role is found for search results
		if ( isset( $atts['role'] ) ) {
			$params['role'] = $atts['role'];
		}

        // Filter by format set on attribute
        if ( isset( $atts['format'] ) ) {
            $params['format'] = $atts['format'];
        }

		// Set the locale
		if ( isset( $atts['locale'] ) ) {
			$params['locale'] = $atts['locale'];
		}

		// Set the from date
		if ( isset( $atts['from_date'] ) ) {
			$params['from_date'] = date( "Y-m-d", strtotime( $atts['from_date'] ) );
		}

		// Set the to date
		if ( isset( $atts['to_date'] ) ) {
			$params['to_date'] = date( "Y-m-d", strtotime( $atts['to_date'] ) );
		}

		// Set the from date - only if we are on relevance order
		if ( isset( $atts['from'] ) && ( ! isset( $params['order'] ) || $params['order'] === 'relevance' ) ) {
			$params['from'] = date( "Y-m-d", strtotime( $atts['from'] ) );
		}

		// Set the to date - only if we are on relevance order
		if ( isset( $atts['to'] ) && ( ! isset( $params['order'] ) || $params['order'] === 'relevance' ) ) {
			$params['to'] = date( "Y-m-d", strtotime( $atts['to'] ) );
		}

		// Filter by collection set on attribute
		if ( isset( $atts['collection'] ) ) {
			$params['collection'] = $atts['collection'];
		}

		// Filter by series set on attribute
		if ( isset( $atts['series'] ) ) {
			$params['series'] = $atts['series'];
		}

        // Filter by imprint set on attribute
        if ( isset( $atts['imprint'] ) ) {
            $params['imprint'] = $atts['imprint'];
        }

        // Filter by publisher set on attribute
        if ( isset( $atts['publisher'] ) ) {
            $params['publisher'] = $atts['publisher'];
        }

		// Get category tree for category filters
		if ( isset( $atts['get_parent_tree'] ) ) {
			$params['get_parent_tree'] = $atts['get_parent_tree'];
		}

        // Filter by isbn set on attribute
        if( isset( $atts['order'] ) ) {
            $params['order'] = $atts['order'];
        }

		return $params;
	}
}

function supapress_widget( $id ) {
	return SupaPress_Widget::get_instance( $id );
}

function supapress_get_contact_form_by_title( $title ) {
	$page = get_page_by_title( $title, OBJECT, SupaPress_Widget::post_type );

	if ( $page ) {
		return supapress_widget( $page->ID );
	}

	return null;
}

function supapress_get_current_contact_form() {
	if ( $current = SupaPress_Widget::get_current() ) {
		return $current;
	}

	return null;
}
