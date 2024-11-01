<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SupaPress_WidgetTemplate {
	private static $defaults = array(
		'atts'                         => array(),
		'widget_type'                  => 'isbn_lookup',
		'widget_layout'                => 'grid',
		'custom_layout_file'           => '',
		'lookup_source'                => 'manual',
		'seo_title'                    => '',
		'isbn_list'                    => array(),
		'lookup_collection'            => array(),
		'per_row'                      => 4,
		'order'                        => 'as-entered',
		'price'                        => array(),
		'cover_right'                  => 'off',
		'show_filters'                 => 'off',
		'filters'                      => array(),
		'show_sort_by'                 => 'off',
		'sort_by'                      => array(),
		'show_per_page'                => 'off',
		'per_page'                     => array( 5, 10, 15, 20, 25, 50, 75, 100 ),
		'per_page_default'             => '10',
		'show_pagination'              => 'off',
		'show_result_count'            => 'off',
		'result_count_text'            => 'Showing results %pagestart%-%pageend% of %total%',
		'show_search_term'             => 'off',
		'search_term_text'             => 'You have searched for "%term%"',
		'show_cover'                   => 'on',
		'cover_link'                   => '',
		'cover_link_target'            => 'off',
		'hide_1_page_pagination'       => 'off',
		'show_title'                   => 'on',
		'title_link'                   => '',
		'title_link_target'            => 'off',
		'show_subtitle'                => 'off',
		'show_author'                  => 'on',
		'show_author_bio'              => 'off',
		'show_edition'				   => 'off',
		'show_format'                  => 'off',
		'show_pubdate'                 => 'off',
		'pub_date_format'              => 'Y-m-d',
		'show_sales_date'              => 'off',
		'sales_date_format'            => 'Y-m-d',
		'show_summary'                 => 'off',
		'show_description'             => 'off',
		'show_price'                   => 'off',
		'show_series'                  => 'off',
		'show_imprint'                 => 'off',
		'show_publisher'               => 'off',
		'show_isbn13'                  => 'off',
		'show_trimsize'                => 'off',
		'show_weight'                  => 'off',
		'show_awards'                  => 'off',
		'show_reviews'                 => 'off',
		'show_pages'                   => 'off',
		'show_retailers'               => 'off',
		'carousel_settings'            => array(),
		'widget_settings'              => array(),
		'lazy_load'                    => 'off',
		'angular_support'              => 'off',
		'search_restriction_publisher' => array(),
		'search_restriction_imprint'   => array(),
		'search_restriction_series'   => array(),
		'search_restriction_categories' => array(),
		'retailer_links'   => array()
	);

	public static function get_default( $prop = null ) {
		if ( isset( self::$defaults[ $prop ] ) ) {
			$template = self::$defaults[ $prop ];
		} else {
			$template = $prop;
		}

		return apply_filters( 'supapress_default_template', $template, $prop );
	}
}