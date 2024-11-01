<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** @type string $action */ ?>
<div class="restrictions nav-tab-content">
    <div class="supapress-field-wrapper">
        <p class="green-heading supapress-tooltip-wrapper">
            <span><?php _e( 'Restrict search results by the selected parameters:', 'supapress' ); ?></span>
            <span class="supapress-tooltip-icon"
                  title="Only books/products that match the selected parameters will be displayed in search results by this module. If no parameters are selected then all books/products in the catalog can be displayed.">
                <svg class="svg-icon">
                    <use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-tooltip"></use>
                </svg>
            </span>
        </p>
    </div>
    <div class="supapress-field-wrapper">
        <label for="search_restriction_imprint" class="supapress-label"><?php _e( 'Imprint:', 'supapress' ); ?></label>
        <select name="search_restriction_imprint[]" id="search_restriction_imprint"
                class="supapress-search-restriction-list"
                data-svg-url="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-three-squares"
                multiple="multiple" title="Click to select Imprint">
			<?php if ( ! empty( $search_restriction_imprints ) ) : ?>
				<?php foreach ( $search_restriction_imprints as $imprint ) : ?>
                    <option value="<?php echo $imprint->seo_name; ?>"<?php echo supapress_multi_selected( $properties, 'search_restriction_imprint', $imprint->seo_name ); ?>><?php echo $imprint->name; ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
        </select>
    </div>
    <div class="supapress-field-wrapper">
        <label for="search_restriction_publisher" class="supapress-label"><?php _e( 'Publisher:', 'supapress' ); ?></label>
        <select name="search_restriction_publisher[]" id="search_restriction_publisher"
                class="supapress-search-restriction-list"
                data-svg-url="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-three-squares"
                multiple="multiple" title="Click to select Publisher">
			<?php if ( ! empty( $search_restriction_publishers ) ) : ?>
				<?php foreach ( $search_restriction_publishers as $publisher ) : ?>
                    <option value="<?php echo $publisher->seo_name; ?>"<?php echo supapress_multi_selected( $properties, 'search_restriction_publisher', $publisher->seo_name ); ?>><?php echo $publisher->name; ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
        </select>
    </div>

    <div class="supapress-field-wrapper">
        <label for="search_restriction_series" class="supapress-label"><?php _e( 'Series:', 'supapress' ); ?></label>
        <select name="search_restriction_series[]" id="search_restriction_series"
                class="supapress-search-restriction-list"
                data-svg-url="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-three-squares"
                multiple="multiple" title="Click to select Series">
			<?php if ( ! empty( $search_restriction_series ) ) : ?>
				<?php foreach ( $search_restriction_series as $series ) : ?>
                    <option value="<?php echo $series->seo_name; ?>"<?php echo supapress_multi_selected( $properties, 'search_restriction_series', $series->seo_name ); ?>><?php echo $series->name; ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
        </select>
    </div>
    <div class="supapress-field-wrapper">
        <label for="search_restriction_categories" class="supapress-label"><?php _e( 'Category:', 'supapress' ); ?></label>
        <select name="search_restriction_categories[]" id="search_restriction_categories"
                class="supapress-search-restriction-list"
                data-svg-url="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-three-squares"
                multiple="multiple" title="Click to select Category">
			<?php if ( ! empty( $search_restriction_categories ) ) : ?>
				<?php 
                    foreach ( $search_restriction_categories as $category ) : 
                        if( !isset( $category->seo_name ) && !isset( $category->name ) ):
                            continue;
                        endif;
                        ?>
                        <option value="<?php echo $category->seo_name; ?>"<?php echo supapress_multi_selected( $properties, 'search_restriction_categories', $category->seo_name ); ?>><?php echo $category->name; ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
        </select>
    </div>
</div>