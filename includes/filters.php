<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SupaPress_Filters {
	private $show_text;
	private $hide_text;
	private $clear_text;

	private $limits;
	private $limit;
	private $default_limit = 10;

	private $filter_groups = array();
	private $filter_group;
	private $filter_group_name;
	private $filter_group_count;
	private $filter;
	private $filter_counter;
	private $original_filter_groups;

	private $additional_filters_wrapper_open = false;
	private $override_clear=false;

	public function __construct( $filters, $properties ) {
		$filtersList = isset( $properties['filtersList'] ) ? $properties['filtersList'] : array();

		foreach ( $filtersList as $filterLabel ) {
			if ( isset( $filters->{$filterLabel} ) ) {
				if ( isset( $filters->{$filterLabel}->values ) ) {
					$this->filter_groups[ $filterLabel ] = (array) $filters->{$filterLabel}->values;
				}
			}
		}

		$this->original_filter_groups = $this->filter_groups;

		$this->params     = isset( $properties['params'] ) ? $properties['params'] : array();
		$this->limits     = isset( $properties['limits'] ) ? $properties['limits'] : array();
		$this->show_text  = isset( $properties['showText'] ) ? $properties['showText'] : 'Show';
		$this->hide_text  = isset( $properties['hideText'] ) ? $properties['hideText'] : 'Hide';
		$this->clear_text = isset( $properties['clearText'] ) ? $properties['clearText'] : 'Hide';
	}

	/**
	 * Get the list of filter groups as an array
	 * @return array
	 */
	public function get_filter_groups() {
		return $this->original_filter_groups;
	}

	/**
	 * Check how many filter groups are left after/during loop
	 * @return bool
	 */
	public function has_filter_groups() {
		return count( $this->filter_groups ) > 0;
	}

	/**
	 * Return the current filter group
	 * @return mixed
	 */
	public function get_filter_group() {
		return $this->filter_group;
	}

	/**
	 * Get the filter group name as HTML
	 *
	 * @param string $before
	 * @param string $after
	 * @param bool $echo
	 *
	 * @return string
	 */
	public function the_filter_group_name( $before = "<h3>", $after = "</h3>", $echo = true ) {
		$html = $before .  __(ucwords( $this->get_filter_group_name() ), 'supapress') . $after;

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}

	/**
	 * Get the raw filter group name
	 * @return mixed
	 */
	public function get_filter_group_name() {
		return __($this->filter_group_name, 'supapress');
	}

	/**
	 * Get the whole filter group as a block of HTML
	 *
	 * @param array $args
	 * @param bool $echo
	 *
	 * @return string
	 */
	public function the_filter_block( $args = array(), $echo = true ) {
		$visible = $hidden = array();
		$html    = '';
		$this->the_filter_group();
		$args = $this->validate_filter_block_args( $args );

		while ( $this->has_filters() ) : $this->the_filter();

			if ( apply_filters( 'supapress_skip_filter_selected', $this->filter, $this->filter_group_name ) === true ) {
				continue;
			}

			if ( ! empty( $this->filter->selected ) ) {
				$visible[] = $this->the_filter_link( $args['filter_list_item_before'], $args['filter_list_item_after'], false );
				break;
			}

			if ( apply_filters( 'supapress_skip_filter', $this->filter, $this->filter_group_name ) === true ) {
				continue;
			}

			$newFilter = $this->the_filter_link( $args['filter_list_item_before'], $args['filter_list_item_after'], false );

			if ( $this->should_show_filters() ) {
				$visible[] = $newFilter;
			} else {
				$hidden[] = $newFilter;
			}

		endwhile;

		if ( ! empty( $visible ) || ! empty( $hidden ) ) {
			$html .= supapress_add_value_to_html_attribute( $args['filter_block_before'], 'class', 'filter-block' );
			$html .= apply_filters( 'supapress_filter_heading_before', $args['filter_heading_before'], $this->get_filter_group_name() );
			$html .= apply_filters( 'supapress_filter_heading', __(ucwords( $this->get_filter_group_name() ), 'supapress') );
			$html .= apply_filters( 'supapress_filter_heading_after', $args['filter_heading_after'], $this->get_filter_group_name() );
			$html .= $args['filter_list_before'];
			if ( ! empty( $visible ) ) {
				$html .= implode( '', $visible );
			}
		}

		if ( ! empty( $hidden ) ) {
			$html .= supapress_add_value_to_html_attribute( $args['additional_filters_before'], 'class', 'additional-filters' );
			$html .= implode( '', $hidden );
			$html .= $args['additional_filters_after'];
			$html .= $this->the_show_more_filters_link( '', '', false );
		}

		if ( ! empty( $visible ) || ! empty( $hidden ) ) {
			$html .= $args['filter_list_after'];
			$html .= $args['filter_block_after'];
		}

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}

	/**
	 * Shift the filter group out of the list and reset all internal flags and counters
	 */
	public function the_filter_group() {
		$names                    = array_keys( $this->filter_groups );
		$this->filter_group_name  = reset( $names );
		$this->filter_group       = apply_filters( 'supapress_filter_group', array_shift( $this->filter_groups ), $this->filter_group_name );
		$this->filter_group_count = count( $this->filter_group );
		$this->filter_counter     = 0;
        $this->limit = apply_filters( 'supapress_filter_limit', isset( $this->limits[$this->filter_group_name] ) ? $this->limits[$this->filter_group_name] : $this->default_limit, $this->filter_group_name );
	}

	/**
	 * Validates the arguments passed to the_filter_block and adds defaults where necessary
	 *
	 * @param $args
	 *
	 * @return array
	 */
	protected function validate_filter_block_args( $args ) {
		return array(
			'filter_block_before'       => isset( $args['filter_block_before'] ) ? $args['filter_block_before'] : '<span>',
			'filter_block_after'        => isset( $args['filter_block_after'] ) ? $args['filter_block_after'] : '</span>',
			'filter_heading_before'     => isset( $args['filter_heading_before'] ) ? $args['filter_heading_before'] : '<h3>',
			'filter_heading_after'      => isset( $args['filter_heading_after'] ) ? $args['filter_heading_after'] : '</h3>',
			'filter_list_before'        => isset( $args['filter_list_before'] ) ? $args['filter_list_before'] : '<ul>',
			'filter_list_after'         => isset( $args['filter_list_after'] ) ? $args['filter_list_after'] : '</ul>',
			'filter_list_item_before'   => isset( $args['filter_list_item_before'] ) ? $args['filter_list_item_before'] : '<li>',
			'filter_list_item_after'    => isset( $args['filter_list_item_after'] ) ? $args['filter_list_item_after'] : '</li>',
			'additional_filters_before' => isset( $args['additional_filters_before'] ) ? $args['additional_filters_before'] : '<span>',
			'additional_filters_after'  => isset( $args['additional_filters_after'] ) ? $args['additional_filters_after'] : '</span>'
		);
	}

	/**
	 * Check how many filters are left in the filter group
	 * @return bool
	 */
	public function has_filters() {
		return count( $this->filter_group ) > 0;
	}

	/**
	 * Set the current filter
	 */
	public function the_filter() {

		$skip_header_grouping = apply_filters( 'supapress_skip_header_grouping', $this->filter_group_name  );
		$this->override_clear = false;

		// see if there is a selected option for this filter group
		if ( isset( $this->params[ $this->filter_group_name ] ) && $skip_header_grouping !== true ) {
			foreach ( $this->filter_group as $filter ) {
				if ( $filter->seo_name === $this->params[ $this->filter_group_name ] ) {
					$this->filter_group     = array();
					$this->filter           = $filter;
					$this->filter->selected = true;
					return;
				}
			}
		} elseif( isset( $this->params[ $this->filter_group_name ] ) && $skip_header_grouping === true ) {
			$filter=null;
			$key = key($this->filter_group);

			if(isset($this->filter_group)){
				$filter = $this->filter_group[$key];
				if(isset($filter->seo_name)&&$this->params[ $this->filter_group_name ]===$filter->seo_name){
					$this->override_clear = true;
				} else {
					$this->override_clear = false;
				}
			}
		}

		$this->filter = array_shift( $this->filter_group );
	}

	/**
	 * Get the link for the current filter as HTML
	 *
	 * @param string $before
	 * @param string $after
	 * @param bool $echo
	 *
	 * @return string
	 */
	public function the_filter_link( $before = "<li>", $after = "</li>", $echo = true ) {
        if ( ! empty( $this->filter->selected ) || ! empty( $this->override_clear ) ) {
			$html = supapress_add_value_to_html_attribute( $before, 'class', 'filter-option active' );
			$html .= __($this->filter->name, 'supapress');
			$html .= " (<a class=\"clear-filter\" href=\"javascript:void(0)\" data-name=\"";
			$html .= __($this->filter_group_name, 'supapress');
			$html .= "\">clear</a>)";
		} else {
			$html = supapress_add_value_to_html_attribute( $before, 'class', 'filter-option' );
			$html .= "<a href=\"javascript:void(0)\" data-name=\"{$this->filter_group_name}\" data-seo=\"{$this->filter->seo_name}\">";
			$html .= __($this->filter->name, 'supapress');
			$html .= "</a>";
		}

		$html .= $after;

		$this->filter_counter ++;

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}

	/**
	 * Check if we have printed filters less than current limit
	 * @return bool
	 */
	public function should_show_filters() {
		return $this->filter_counter <= $this->limit;
	}

	/**
	 * Get the link to show hidden filters
	 *
	 * @param string $before
	 * @param string $after
	 * @param bool $echo
	 *
	 * @return string
	 */
	public function the_show_more_filters_link( $before = '', $after = '', $echo = true ) {
		$html = $before . "<a class=\"additional-filters-toggle\" data-show-label=\"{$this->show_text}\" data-hide-label=\"{$this->hide_text}\" href=\"javascript:void(0)\">";
		$html .= __($this->show_text, 'supapress');
		$html .= "</a>" . $after;

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}

	/**
	 * Get the current filter
	 * @return mixed
	 */
	public function get_filter() {
		return $this->filter;
	}

	/**
	 * Get the link to clear all the filters
	 *
	 * @param string $before
	 * @param string $after
	 * @param bool $echo
	 *
	 * @return string
	 */
	public function the_clear_all_filters_link( $before = '', $after = '', $echo = true ) {
		$html = $before . "<a class=\"clear-filters\" href=\"javascript:void(0)\">";
		$html .= __($this->clear_text, 'supapress');
		$html .= "</a>" . $after;

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}

	/**
	 * Check if the current search has active filters
	 * @return bool
	 */
	public function is_search_filtered() {
		foreach ( $this->filter_groups as $name => $group ) {
			if ( isset( $this->params[ $name ] ) ) {
				return true;
			}
		}

		if ( ! empty( $this->params['keyword'] ) ) {
			return true;
		}

		return false;
	}
}


