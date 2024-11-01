<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_List_Table' ) )
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class SupaPress_Widget_List_Table extends WP_List_Table {

	public static function define_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title', 'supapress'),
			'type' => __('Type', 'supapress'),
			'shortcode' => __('Shortcode', 'supapress'),
			'author' => __('Author', 'supapress'),
			'date' => __('Date', 'supapress')
		);

		return $columns;
	}
	function __construct() {
		parent::__construct( array(
			'singular' => 'post',
			'plural' => 'posts',
			'ajax' => false
		) );
	}

	function prepare_items() {
		$per_page = $this->get_items_per_page( 'supapress_widgets_per_page' );

		$this->_column_headers = $this->get_column_info();

		$args = array(
			'posts_per_page' => $per_page,
			'orderby' => 'title',
			'order' => 'ASC',
			'offset' => ( $this->get_pagenum() - 1 ) * $per_page
		);

		if ( ! empty( $_REQUEST['s'] ) )
			$args['s'] = $_REQUEST['s'];

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			if ( 'title' == $_REQUEST['orderby'] )
				$args['orderby'] = 'title';
			elseif ( 'author' == $_REQUEST['orderby'] )
				$args['orderby'] = 'author';
			elseif ( 'date' == $_REQUEST['orderby'] )
				$args['orderby'] = 'date';
			elseif ( 'type' == $_REQUEST['orderby'] )
				$args['orderby'] = 'meta_value';
		}

		if ( ! empty( $_REQUEST['order'] ) ) {
			if ( 'asc' == strtolower( $_REQUEST['order'] ) )
				$args['order'] = 'ASC';
			elseif ( 'desc' == strtolower( $_REQUEST['order'] ) )
				$args['order'] = 'DESC';
		}

		$this->items = SupaPress_Widget::find( $args );
		$total_items = SupaPress_Widget::count();
		$total_pages = ceil( $total_items / $per_page );

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'total_pages' => $total_pages,
			'per_page' => $per_page
		) );
	}

	function get_columns() {
		return get_column_headers( get_current_screen() );
	}

	function get_sortable_columns() {
		$columns = array(
			'title' => array( 'title', true ),
			'author' => array( 'author', false ),
			'date' => array( 'date', false ),
			'type' => array( 'type', false )
		);

		return $columns;
	}

	function get_bulk_actions() {
		return array( 'delete' => 'Delete' );
	}

	function column_cb( $item ) {
		/** @type SupaPress_Widget $item  */
		return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->id() );
	}

	function column_title( $item ) {
		/** @type SupaPress_Widget $item  */
		$url = admin_url( 'admin.php?page=supapress&post=' . absint( $item->id() ) );
		$edit_link = add_query_arg( array( 'action' => 'edit' ), $url );

		$actions = array( 'edit' => '<a href="' . $edit_link . '">' . __('Edit', 'supapress') . '</a>' );

		if ( current_user_can( 'publish_pages', $item->id() ) ) {
			$copy_link = wp_nonce_url( add_query_arg( array( 'action' => 'copy' ), $url ), 'supapress-copy-widget_' . absint( $item->id() ) );
			$delete_link = wp_nonce_url( add_query_arg( array( 'action' => 'delete' ), $url ), 'supapress-delete-widget_' . absint( $item->id() ) );
			$actions = array_merge( $actions, array(
				'copy' => '<a href="' . $copy_link . '">' . __('Duplicate', 'supapress') . '</a>',
				'delete' => '<a href="' . $delete_link . '">' . __('Delete', 'supapress') . '</a>'
			) );
		}

		$a = sprintf( '<a class="row-title" href="%1$s" title="%2$s">%3$s</a>',	$edit_link,	esc_attr( sprintf( 'Edit &#8220;%s&#8221;',	$item->title() ) ),	esc_html( $item->title() ) );

		return '<strong>' . $a . '</strong> ' . $this->row_actions( $actions );
    }

	function column_author( $item ) {
		/** @type SupaPress_Widget $item  */
		$post = get_post( $item->id() );

		if ( ! $post )
			return false;

		$author = get_userdata( $post->post_author );

		return esc_html( $author->display_name );
    }

	function column_type( $item ) {
		/** @type SupaPress_Widget $item  */
		$widgetType =  get_post_meta ( $item->id(), '_widget_type', true );
		$widgetType = ucwords( str_replace( '_', ' ', $widgetType ) );
		$widgetType = str_replace( "Isbn", "ISBN", $widgetType );
		
		return $widgetType;
	}

	function column_shortcode( $item ) {
		/** @type SupaPress_Widget $item  */
		$shortcodes = array( sprintf( '[supapress id="%1$d" title="%2$s"]', $item->id(), $item->title() ) );
		$output = '';

		foreach ( $shortcodes as $shortcode ) {
			$output .= "\n" . '<input type="text"'
				. ' onfocus="this.select();" readonly="readonly"'
				. ' value="' . esc_attr( $shortcode ) . '"'
				. ' class="shortcode-in-list-table wp-ui-text-highlight code" />';
		}

		return trim( $output );
	}

	function column_date( $item ) {
		/** @type SupaPress_Widget $item  */
		$post = get_post( $item->id() );

		if ( ! $post )
			return false;

		$t_time = mysql2date( 'Y/m/d g:i:s A', $post->post_date, true );
		$m_time = $post->post_date;
		$time = mysql2date( 'G', $post->post_date ) - get_option( 'gmt_offset' ) * 3600;

		$time_diff = time() - $time;

		if ( $time_diff > 0 && $time_diff < 24 * 60 * 60 ) {
			$h_time = sprintf( '%s ago', human_time_diff( $time ) );
		} else {
			$h_time = mysql2date( 'Y/m/d', $m_time );
		}

		return '<abbr title="' . $t_time . '">' . $h_time . '</abbr>';
    }

	/**
	 * Display the search box.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @param string $text The search button text
	 * @param string $input_id The search input id
	 */
	public function search_box( $text, $input_id ) {
		if ( empty( $_REQUEST['s'] ) && !$this->has_items() )
			return;

		$input_id = $input_id . '-search-input';

		if ( ! empty( $_REQUEST['orderby'] ) )
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
		if ( ! empty( $_REQUEST['order'] ) )
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
		if ( ! empty( $_REQUEST['post_mime_type'] ) )
			echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
		if ( ! empty( $_REQUEST['detached'] ) )
			echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
		?>
		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
			<input type="search" class="supapress-input" placeholder="<?php echo $text; ?>" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
			<?php submit_button( __( 'Search', 'supapress'), 'button', false, false, array('id' => 'search-submit') ); ?>
		</p>
		<?php
	}

	protected function extra_tablenav( $which ) {
	    if($which === 'top') {
		    $this->type_filter( 'Type', 'widget-type-selector-' . $which );
	    }
    }

	/**
	 * Display the type filter
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @param string $text The search button text
	 * @param string $input_id The search input id
	 */
	public function type_filter( $text, $input_id ) {
		$value = !empty( $_REQUEST['supapress_widget_type'] ) ? $_REQUEST['supapress_widget_type'] : '';

		?>
		<div class="alignleft actions widgettype">
            <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
            <select name="supapress_widget_type" id="<?php echo $input_id; ?>">
                <option value>All</option>
                <option value="isbn_lookup"<?php echo $value === 'isbn_lookup' ? ' selected="selected"' : ''; ?>>
					<?php _e("ISBN Lookup", "supapress" );?>
				</option>
                <option value="search_results"<?php echo $value === 'search_results' ? ' selected="selected"' : ''; ?>>
					<?php _e("Search Results", "supapress" );?>
				</option>
                <option value="product_details"<?php echo $value === 'product_details' ? ' selected="selected"' : ''; ?>>
					<?php _e("Product Details", "supapress" );?>
				</option>
            </select>
			<?php submit_button( __( 'Apply', 'supapress'), 'button action', false, false, array('id' => $input_id . '-submit') ); ?>
		</div>
		<?php
	}
}