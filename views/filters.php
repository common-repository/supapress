<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/** @type SupaPress_Filters $supapressFilters */

if ( $supapressFilters->is_search_filtered() ) : ?>
	<span class="filter-block">
        <ul>
	        <li>
		        <?php echo $supapressFilters->the_clear_all_filters_link(); ?>
	        </li>
        </ul>
    </span>
	<?php
endif;

while ( $supapressFilters->has_filter_groups() ) :

	$supapressFilters->the_filter_block( array(
		// wrapper for each set of filters
		'filter_block_before'       => '<span>',
		'filter_block_after'        => '</span>',
		// wrapper for titles on each set of filters
		'filter_heading_before'     => '<h3>',
		'filter_heading_after'      => '</h3>',
		// wrapper for list of filters
		'filter_list_before'        => '<ul>',
		'filter_list_after'         => '</ul>',
		// wrapper for each filter item
		'filter_list_item_before'   => '<li>',
		'filter_list_item_after'    => '</li>',
		// wrapper for hidden filters
		'additional_filters_before' => '<span>',
		'additional_filters_after'  => '</span>'
	) );

endwhile;
