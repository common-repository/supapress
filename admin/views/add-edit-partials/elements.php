<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** @type string $action */
/** @type bool|array $bookUrls */
?>
<div class="hide elements nav-tab-content">
    <div class="supapress-field-wrapper">
        <p class="green-heading supapress-tooltip-wrapper">
            <span><?php _e( 'Select which fields to display', 'supapress' ) ?>:</span>
            <span class="supapress-tooltip-icon"
                  title="Use the toggles on the right to add elements to your module.<br />---<br />Once added you can control the settings of that element using the dropdowns on the left.">
				<svg class="svg-icon">
					<use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-tooltip"></use>
				</svg>
			</span>
        </p>
    </div>
    <div class="config">
        <div class="hide tab-level show_filters supapress-accordion-wrapper sub-content-wrapper">
			<?php echo supapress_get_element_template( __('Search filters', 'supapress' ) ); ?>
            <div class="supapress-accordion-content element-config hide">
                <div class="supapress-accordion-content-inner element-config-inner">
                    <div class="supapress-field-wrapper ">
                        <label class="supapress-label" for="filters"><?php _e( 'Filters', 'supapress' ) ?>:</label>
                        <select name="filters[]" id="filters" multiple="multiple" title="Select your filter options">
                            <option value="format"<?php echo supapress_multi_selected( $properties, 'filters', 'format' ); ?>>
                                <?php _e( 'Format', 'supapress' ) ?>
                            </option>
                            <option value="prices"<?php echo supapress_multi_selected( $properties, 'filters', 'prices' ); ?>>
                                <?php _e( 'Price', 'supapress' ) ?>
                            </option>
                            <option value="collection"<?php echo supapress_multi_selected( $properties, 'filters', 'collection' ); ?>>
                                <?php _e( 'Collection', 'supapress' ) ?>
                            </option>
                            <option value="guides"<?php echo supapress_multi_selected( $properties, 'filters', 'guides' ); ?>>
                                <?php _e( 'Guides', 'supapress' ) ?>
                            </option>
                            <option value="series"<?php echo supapress_multi_selected( $properties, 'filters', 'series' ); ?>>
                                <?php _e( 'Series', 'supapress' ) ?>
                            </option>
                            <option value="imprint"<?php echo supapress_multi_selected( $properties, 'filters', 'imprint' ); ?>>
                                <?php _e( 'Imprint', 'supapress' ) ?>
                            </option>
                            <option value="award"<?php echo supapress_multi_selected( $properties, 'filters', 'award' ); ?>>
                                <?php _e( 'Award', 'supapress' ) ?>
                            </option>
                            <option value="category"<?php echo supapress_multi_selected( $properties, 'filters', 'category' ); ?>>
                                <?php _e( 'Category', 'supapress' ) ?>
                            </option>
                            <option value="publisher"<?php echo supapress_multi_selected( $properties, 'filters', 'publisher' ); ?>>
                                <?php _e( 'Publisher', 'supapress' ) ?>
                            </option>
                            <option value="type"<?php echo supapress_multi_selected( $properties, 'filters', 'type' ); ?>>
                                <?php _e( 'Type', 'supapress' ) ?>
                            </option>
                            <option value="age"<?php echo supapress_multi_selected( $properties, 'filters', 'age' ); ?>>
                                <?php _e( 'Age', 'supapress' ) ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="hide tab-level show_sort_by supapress-accordion-wrapper sub-content-wrapper">
			<?php echo supapress_get_element_template( __('Search sort by', 'supapress' ) ); ?>
            <div class="supapress-accordion-content element-config hide">
                <div class="supapress-accordion-content-inner element-config-inner">
                    <div class="supapress-field-wrapper">
                        <label class="supapress-label supapress-tooltip-wrapper" for="sort_by">
                            <span><?php _e( 'Sort by', 'supapress' ) ?>:</span>
                            <span class="supapress-tooltip-icon"
                                  title="Select which sorting options you would like available for your search.<br />---<br />You can re-order the options by dragging them, the default option should be at the top.">
								<svg class="svg-icon">
									<use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-tooltip"></use>
								</svg>
							</span>
                        </label>
                        <select name="sort_by[]" id="sort_by" multiple="multiple" title="Select your sort by options">
                            <option value="relevance"<?php echo supapress_multi_selected( $properties, 'sort_by', 'relevance', supapress_set_default( $action ) ); ?>>
                                <?php _e( 'Relevance', 'supapress' ) ?>
                            </option>
                            <option value="publishdate-desc"<?php echo supapress_multi_selected( $properties, 'sort_by', 'publishdate-desc' ); ?>>
                                <?php _e( 'Newest to Oldest', 'supapress' ) ?>
                            </option>
                            <option value="publishdate-asc"<?php echo supapress_multi_selected( $properties, 'sort_by', 'publishdate-asc' ); ?>>
                                <?php _e( 'Oldest to Newest', 'supapress' ) ?>
                            </option>
                            <option value="title-az"<?php echo supapress_multi_selected( $properties, 'sort_by', 'title-az' ); ?>>
                                <?php _e( 'Title - A to Z', 'supapress' ) ?>
                            </option>
                            <option value="title-za"<?php echo supapress_multi_selected( $properties, 'sort_by', 'title-za' ); ?>>
                                <?php _e( 'Title - Z to A', 'supapress' ) ?>
                            </option>
                            <option value="price-asc"<?php echo supapress_multi_selected( $properties, 'sort_by', 'price-asc' ); ?>>
                                <?php _e( 'Price - Low to High', 'supapress' ) ?>
                            </option>
                            <option value="price-desc"<?php echo supapress_multi_selected( $properties, 'sort_by', 'price-desc' ); ?>>
                                <?php _e( 'Price - High to Low', 'supapress' ) ?>
                            </option>
                            <option value="contributor-az"<?php echo supapress_multi_selected( $properties, 'sort_by', 'contributor-az' ); ?>>
                                <?php _e( 'Author - A to Z', 'supapress' ) ?>
                            </option>
                            <option value="contributor-za"<?php echo supapress_multi_selected( $properties, 'sort_by', 'contributor-za' ); ?>>
                                <?php _e( 'Author - Z to A', 'supapress' ) ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="hide tab-level show_per_page supapress-accordion-wrapper sub-content-wrapper">
			<?php
			echo supapress_get_element_template( __('Search per page', 'supapress' ) );
			$perPageOptions = supapress_property_array( $properties, 'per_page' );
			if ( empty( $perPageOptions ) && $action === 'add' ) {
				$perPageOptions = array( 5, 10, 15, 20, 25, 50, 75, 100 );
			}
			$perPageDefault = supapress_property_value( $properties, 'per_page_default' );
			if ( $perPageDefault === '' && $action === 'add' ) {
				$perPageDefault = '10';
			}
			?>
            <div class="supapress-accordion-content element-config hide">
                <div class="supapress-accordion-content-inner element-config-inner">
                    <div class="supapress-field-wrapper">
                        <label class="supapress-label supapress-tooltip-wrapper" for="per_page_number_input">
                            <span><?php _e( 'Per page number', 'supapress' ) ?>:</span>
                            <span class="supapress-tooltip-icon"
                                  title="Select the number of results per page.<br />---<br />You can re-order the options by dragging them, using the default dropdown to choose which one to make the default for the site.">
								<svg class="svg-icon">
									<use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-tooltip"></use>
								</svg>
							</span>
                        </label>
                        <input placeholder="Enter page number" type="text" id="per_page_input" name="per_page_input"
                               class="supapress-input numbers-only"/>
                        <button id="per_page_input_btn" href="#" class="upload-button" type="button">Add</button>
                        <select name="per_page[]" id="per_page" multiple="multiple"
                                title="Select your per page options">
							<?php
							foreach ( $perPageOptions as $perPageOption ):?>
                                <option value="<?php echo $perPageOption; ?>"
                                        selected="selected"><?php echo $perPageOption; ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>
                    <div class="supapress-field-wrapper">
                        <label class="supapress-label supapress-tooltip-wrapper" for="per_page_default">
                            <?php _e( 'Per page default', 'supapress' ) ?>:
                        </label>
                        <select name="per_page_default" id="per_page_default" title="Select your sort by default"
                                class="supapress-dropdown">
                            <option value=""><?php _e( 'No default set', 'supapress' ) ?></option>
							<?php
							sort( $perPageOptions );
							foreach ( $perPageOptions as $perPageOption ):?>
                                <option value="<?php echo $perPageOption ?>"<?php echo $perPageDefault == $perPageOption ? 'selected="selected"' : ''; ?>><?php echo $perPageOption; ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="hide tab-level show_pagination supapress-accordion-wrapper sub-content-wrapper">
			<?php echo supapress_get_element_template( __('Search pagination', 'supapress' ) ); ?>
            <div class="supapress-accordion-content element-config hide">
                <div class="supapress-accordion-content-inner element-config-inner">
                    <div class="supapress-field-wrapper">
                        <label class="supapress-label supapress-tooltip-wrapper" for="hide_1_page_pagination"><?php _e( 'Hide when 1 page of results', 'supapress' ) ?>:</label>
                        <div class="onoffswitch">
                            <input type="hidden" name="hide_1_page_pagination" value="off"/>
                            <input type="checkbox" name="hide_1_page_pagination" class="onoffswitch-checkbox"
                                   id="hide_1_page_pagination"<?php echo supapress_checked( $properties, 'hide_1_page_pagination' ); ?> />
                            <label class="onoffswitch-label" for="hide_1_page_pagination">
                                <span class="onoffswitch-inner" data-label-before="Yes" data-label-after="No"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hide tab-level show_result_count supapress-accordion-wrapper sub-content-wrapper">
			<?php echo supapress_get_element_template( __('Pagination message', 'supapress' ) ); ?>
            <div class="supapress-accordion-content element-config hide">
                <div class="supapress-accordion-content-inner element-config-inner">
                    <div class="supapress-field-wrapper">
                        <p class="supapress-tooltip-wrapper">
                            <label class="supapress-label" for="result_count_text">
                                <?php _e( 'Text template', 'supapress' ) ?>:
                                <span class="supapress-tooltip-icon"
                                      title="The text you would like to show with this element.<br />---<br />Special placeholder %total% will be replaced with the total number of results.<br />---<br />Special placeholder %pagestart% will be replaced with the number of the first result on the current page.<br />---<br />Special placeholder %pageend% will be replaced with the number of the last result on the current page.">
									<svg class="svg-icon">
										<use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-tooltip"></use>
									</svg>
								</span>
                            </label>
							<?php
							$value = supapress_property_value( $properties, 'result_count_text' );
							if ( ! $value ) :
								$value = htmlentities( SupaPress_WidgetTemplate::get_default( 'result_count_text' ) );
							endif;
							?>
                            <input type="text" id="supapress-result-count-text"
                                   data-default="<?php echo htmlentities( SupaPress_WidgetTemplate::get_default( 'result_count_text' ) ); ?>"
                                   name="result_count_text" class="supapress-input" value="<?php echo $value ?>"/>
                        </p>
                    </div>
                </div>
            </div>
        </div>


        <div class="hide tab-level show_search_term supapress-accordion-wrapper sub-content-wrapper">
			<?php echo supapress_get_element_template( __('Search term message', 'supapress' ) ); ?>
            <div class="supapress-accordion-content element-config hide">
                <div class="supapress-accordion-content-inner element-config-inner">
                    <div class="supapress-field-wrapper">
                        <p class="supapress-tooltip-wrapper">
                            <label class="supapress-label" for="search_term_text">
                                <?php _e( 'Text template', 'supapress' ) ?>:
                                <span class="supapress-tooltip-icon"
                                      title="The text you would like to show with this element.<br />---<br />Special placeholder %term% will be replaced with the search term entered by the user.">
								<svg class="svg-icon">
									<use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-tooltip"></use>
								</svg>
							</span>
                            </label>
							<?php
							$value = supapress_property_value( $properties, 'search_term_text' );
							if ( ! $value ) :
								$value = htmlentities( SupaPress_WidgetTemplate::get_default( 'search_term_text' ) );
							endif;
							?>
                            <input type="text" id="supapress-search-term-text"
                                   data-default="<?php echo htmlentities( SupaPress_WidgetTemplate::get_default( 'search_term_text' ) ); ?>"
                                   name="search_term_text" class="supapress-input" value="<?php echo $value ?>"/>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="hide tab-level show_cover supapress-accordion-wrapper sub-content-wrapper">
			<?php echo supapress_get_element_template( __('Book cover', 'supapress' ) ); ?>
            <div class="supapress-accordion-content element-config hide">
                <div class="supapress-accordion-content-inner element-config-inner">
                    <div class="supapress-field-wrapper">
                        <label class="supapress-label" for="cover_link"><?php _e( 'Book cover link', 'supapress' ) ?>:</label>
						<?php if ( is_string( $bookUrls ) ) : ?>
							<?php echo $bookUrls; ?>
						<?php else : ?>
                            <select class="supapress-dropdown" name="cover_link" id="cover_link"
                                    data-value="<?php echo supapress_property_value( $properties, 'cover_link' ); ?>">
								<?php echo implode( '', $bookUrls ); ?>
                            </select>
						<?php endif; ?>
                    </div>
                    <div class="supapress-field-wrapper">
                        <label class="supapress-label" for="cover_link_target"><?php _e( 'Open link in new tab', 'supapress' ) ?>:</label>
                        <div class="onoffswitch">
                            <input type="hidden" name="cover_link_target" value="off"/>
                            <input type="checkbox" name="cover_link_target" class="onoffswitch-checkbox"
                                   id="cover_link_target"<?php echo supapress_checked( $properties, 'cover_link_target' ); ?> />
                            <label class="onoffswitch-label" for="cover_link_target">
                                <span class="onoffswitch-inner" data-label-before="Yes" data-label-after="No"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hide tab-level show_title supapress-accordion-wrapper sub-content-wrapper">
			<?php echo supapress_get_element_template( __('Title', 'supapress' ) ); ?>
            <div class="supapress-accordion-content element-config hide">
                <div class="supapress-accordion-content-inner element-config-inner">
                    <div class="supapress-field-wrapper">
                        <label class="supapress-label" for="title_link"><?php _e( 'Title link', 'supapress' ) ?>:</label>
						<?php if ( is_string( $bookUrls ) ) : ?>
							<?php echo $bookUrls; ?>
						<?php else: ?>
                            <select class="supapress-dropdown" name="title_link" id="title_link"
                                    data-value="<?php echo supapress_property_value( $properties, 'title_link' ); ?>">
								<?php echo implode( '', $bookUrls ); ?>
                            </select>
						<?php endif; ?>
                    </div>
                    <div class="supapress-field-wrapper">
                        <label class="supapress-label" for="title_link_target"><?php _e( 'Open link in new tab', 'supapress' ) ?>:</label>
                        <div class="onoffswitch">
                            <input type="hidden" name="title_link_target" value="off"/>
                            <input type="checkbox" name="title_link_target" class="onoffswitch-checkbox"
                                   id="title_link_target"<?php echo supapress_checked( $properties, 'title_link_target' ); ?> />
                            <label class="onoffswitch-label" for="title_link_target">
                                <span class="onoffswitch-inner" data-label-before="Yes" data-label-after="No"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
		echo supapress_get_empty_element_template( __('Subtitle', 'supapress' ), 'show_subtitle' );
		echo supapress_get_empty_element_template( __('Author name', 'supapress' ), 'show_author' );
		echo supapress_get_empty_element_template( __('Author bio', 'supapress' ), 'show_author_bio' );
        echo supapress_get_empty_element_template( __('Edition', 'supapress' ), 'show_edition' );
		echo supapress_get_empty_element_template( __('Format', 'supapress' ), 'show_format' );
		?>
        <div class="hide tab-level show_pubdate supapress-accordion-wrapper sub-content-wrapper">
			<?php echo supapress_get_element_template( __('Publication date', 'supapress' ) ); ?>
            <div class="supapress-accordion-content element-config hide">
                <div class="supapress-accordion-content-inner element-config-inner">
                    <div class="supapress-field-wrapper ">
                        <label class="supapress-label" for="pub_date_format"><?php _e( 'Date formats', 'supapress' ) ?>:</label>
                        <select name="pub_date_format" id="pub_date_format" class="supapress-dropdown">
                            <option value="Y-m-d"<?php echo supapress_selected( $properties, 'pub_date_format', 'Y-m-d' ); ?>>
                                1970-01-01
                            </option>
                            <option value="j F Y"<?php echo supapress_selected( $properties, 'pub_date_format', 'j F Y' ); ?>>
                                31 <?php _e( 'January', 'supapress' ) ?> 1970
                            </option>
                            <option value="jS F Y"<?php echo supapress_selected( $properties, 'pub_date_format', 'jS F Y' ); ?>>
                                31st <?php _e( 'January', 'supapress' ) ?> 1970
                            </option>
                            <option value="d/m/Y"<?php echo supapress_selected( $properties, 'pub_date_format', 'd/m/Y' ); ?>>
                                31/01/1970
                            </option>
                            <option value="d-m-Y"<?php echo supapress_selected( $properties, 'pub_date_format', 'd-m-Y' ); ?>>
                                31-01-1970
                            </option>
                            <option value="m/d/Y"<?php echo supapress_selected( $properties, 'pub_date_format', 'm/d/Y' ); ?>>
                                01/31/1970
                            </option>
                            <option value="m-d-Y"<?php echo supapress_selected( $properties, 'pub_date_format', 'm-d-Y' ); ?>>
                                01-31-1970
                            </option>
                            <option value="m/d/Y H:i:s"<?php echo supapress_selected( $properties, 'pub_date_format', 'm/d/Y H:i:s' ); ?>>
                                1/31/1970 00:00:00
                            </option>
                            <option value="F j, Y"<?php echo supapress_selected( $properties, 'pub_date_format', 'F j, Y' ); ?>>
                                <?php _e( 'January', 'supapress' ) ?> 31, 1970
                            </option>
                            <option value="F jS, Y"<?php echo supapress_selected( $properties, 'pub_date_format', 'F jS, Y' ); ?>>
                                <?php _e( 'January', 'supapress' ) ?> 31st, 1970
                            </option>
                            <option value="F j"<?php echo supapress_selected( $properties, 'pub_date_format', 'F j' ); ?>>
                                <?php _e( 'January', 'supapress' ) ?> 31
                            </option>
                            <option value="F jS"<?php echo supapress_selected( $properties, 'pub_date_format', 'F jS' ); ?>>
                                <?php _e( 'January', 'supapress' ) ?> 31st
                            </option>
                            <option value="jS M Y"<?php echo supapress_selected( $properties, 'pub_date_format', 'jS M Y' ); ?>>
                                31st <?php _e( 'Jan', 'supapress' ) ?> 1970
                            </option>
                            <option value="l, j M Y"<?php echo supapress_selected( $properties, 'pub_date_format', 'l, j M Y' ); ?>>
                                <?php _e( 'Monday', 'supapress' ) ?>, 31 Jan 1970
                            </option>
                            <option value="l, jS M Y"<?php echo supapress_selected( $properties, 'pub_date_format', 'l, jS M Y' ); ?>>
                                <?php _e( 'Monday', 'supapress' ) ?>, 31st Jan 1970
                            </option>
                            <option value="F Y"<?php echo supapress_selected( $properties, 'pub_date_format', 'F Y' ); ?>>
                                <?php _e( 'January', 'supapress' ) ?> 1970
                            </option>
                            <option value="m/d"<?php echo supapress_selected( $properties, 'pub_date_format', 'm/d' ); ?>>
                                12/31
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
		<?php
		echo supapress_get_empty_element_template( 'Summary', 'show_summary' );
		echo supapress_get_empty_element_template( 'Description', 'show_description' );
		?>
        <div class="hide tab-level show_price supapress-accordion-wrapper sub-content-wrapper">
			<?php echo supapress_get_element_template( __('Price', 'supapress' ) ); ?>
            <div class="supapress-accordion-content element-config hide">
                <div class="supapress-accordion-content-inner element-config-inner">
                    <div class="supapress-field-wrapper ">
                        <label class="supapress-label" for="price"><?php _e( 'Price locale', 'supapress' ) ?>:</label>
                        <select name="price[]" id="price" multiple="multiple" title="Click to select a price locale">
                            <option value="USD"<?php echo supapress_multi_selected( $properties, 'price', 'USD', supapress_set_default( $action ) ); ?>>
                                <?php _e( 'Price', 'supapress' ) ?> (USD)
                            </option>
                            <option value="GBP"<?php echo supapress_multi_selected( $properties, 'price', 'GBP' ); ?>>
                                <?php _e( 'Price', 'supapress' ) ?> (GBP)
                            </option>
                            <option value="CAD"<?php echo supapress_multi_selected( $properties, 'price', 'CAD' ); ?>>
                                <?php _e( 'Price', 'supapress' ) ?> (CAD)
                            </option>
                            <option value="AUD"<?php echo supapress_multi_selected( $properties, 'price', 'AUD' ); ?>>
                                <?php _e( 'Price', 'supapress' ) ?> (AUD)
                            </option>
                            <option value="NZD"<?php echo supapress_multi_selected( $properties, 'price', 'NZD' ); ?>>
                                <?php _e( 'Price', 'supapress' ) ?> (NZD)
                            </option>
                            <option value="EUR"<?php echo supapress_multi_selected( $properties, 'price', 'EUR' ); ?>>
                                <?php _e( 'Price', 'supapress' ) ?> (EUR)
                            </option>
                            <option value="JPY"<?php echo supapress_multi_selected( $properties, 'price', 'JPY' ); ?>>
                                <?php _e( 'Price', 'supapress' ) ?> (JPY)
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
		<?php
		echo supapress_get_empty_element_template( __( 'Series', 'supapress' ), 'show_series' );
		echo supapress_get_empty_element_template( __( 'Imprint', 'supapress' ), 'show_imprint' );
		echo supapress_get_empty_element_template( __( 'Publisher', 'supapress' ), 'show_publisher' );
		echo supapress_get_empty_element_template( __( 'ISBN-13', 'supapress' ), 'show_isbn13' );
		echo supapress_get_empty_element_template( __( 'Trim size', 'supapress' ), 'show_trimsize' );
		echo supapress_get_empty_element_template( __( 'Weight', 'supapress' ), 'show_weight' );
		echo supapress_get_empty_element_template( __( 'Awards', 'supapress' ), 'show_awards' );
		echo supapress_get_empty_element_template( __( 'Reviews', 'supapress' ), 'show_reviews' );
		echo supapress_get_empty_element_template( __( 'Pages', 'supapress' ), 'show_pages' );
		?>
		<?php
		$retailerNames = get_option( 'widget_retailer_name' );
		if( empty( $retailerNames ) ) :
            echo supapress_get_empty_element_template( __( 'Retailers', 'supapress' ), 'show_retailers' );
		else : 
		?>
		<div class="hide tab-level show_retailers supapress-accordion-wrapper sub-content-wrapper">
			<?php echo supapress_get_element_template( __( 'Retailers', 'supapress' ) ); ?>
            <div class="supapress-accordion-content element-config hide">
                <div class="supapress-accordion-content-inner element-config-inner">
                    <div class="supapress-field-wrapper">
                        <label class="supapress-label" for="retailer_links"><?php _e( 'Retailer', 'supapress' ) ?>:</label>
                        <select name="retailer_links[]" id="retailer_links" multiple="multiple" title="Click to select a retailer">
                            <?php foreach( $retailerNames as $key => $value ) : ?>
                                <?php
                                    // Linked to HCUSM-2213 and ensures retailer links added before the ordering feature are not lost
                                    if(isset($properties['retailer_links']) && isset($properties['retailer_links'][$key]) && $properties['retailer_links'][$key] === "on") {   
                                        $selected = ' rel="option_' . $key . '" selected="selected"';
                                    } elseif( $action !== "edit" ) {
                                        $selected = ' rel=""';
                                    } else {
                                        $selected = supapress_multi_selected( $properties, 'retailer_links', $key, supapress_set_default( $action ) );
                                    }
                                ?>
                                <option value="<?php echo $key; ?>"<?php echo $selected; ?>>
                                    <?php echo $value; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="hide tab-level show_sales_date supapress-accordion-wrapper sub-content-wrapper">
			<?php echo supapress_get_element_template( __('Sales date', 'supapress' ) ); ?>
            <div class="supapress-accordion-content element-config hide">
                <div class="supapress-accordion-content-inner element-config-inner">
                    <div class="supapress-field-wrapper ">
                        <label class="supapress-label" for="sales_date_format"><?php _e( 'Date formats', 'supapress' ) ?>:</label>
                        <select name="sales_date_format" id="sales_date_format" class="supapress-dropdown">
                            <option value="Y-m-d"<?php echo supapress_selected( $properties, 'sales_date_format', 'Y-m-d' ); ?>>
                                1970-01-01
                            </option>
                            <option value="j F Y"<?php echo supapress_selected( $properties, 'sales_date_format', 'j F Y' ); ?>>
                                31 <?php _e( 'January', 'supapress' ) ?> 1970
                            </option>
                            <option value="jS F Y"<?php echo supapress_selected( $properties, 'sales_date_format', 'jS F Y' ); ?>>
                                31st <?php _e( 'January', 'supapress' ) ?> 1970
                            </option>
                            <option value="d/m/Y"<?php echo supapress_selected( $properties, 'sales_date_format', 'd/m/Y' ); ?>>
                                31/01/1970
                            </option>
                            <option value="d-m-Y"<?php echo supapress_selected( $properties, 'sales_date_format', 'd-m-Y' ); ?>>
                                31-01-1970
                            </option>
                            <option value="m/d/Y"<?php echo supapress_selected( $properties, 'sales_date_format', 'm/d/Y' ); ?>>
                                01/31/1970
                            </option>
                            <option value="m-d-Y"<?php echo supapress_selected( $properties, 'sales_date_format', 'm-d-Y' ); ?>>
                                01-31-1970
                            </option>
                            <option value="m/d/Y H:i:s"<?php echo supapress_selected( $properties, 'sales_date_format', 'm/d/Y H:i:s' ); ?>>
                                1/31/1970 00:00:00
                            </option>
                            <option value="F j, Y"<?php echo supapress_selected( $properties, 'sales_date_format', 'F j, Y' ); ?>>
                                <?php _e( 'January', 'supapress' ) ?> 31, 1970
                            </option>
                            <option value="F jS, Y"<?php echo supapress_selected( $properties, 'sales_date_format', 'F jS, Y' ); ?>>
                                <?php _e( 'January', 'supapress' ) ?> 31st, 1970
                            </option>
                            <option value="F j"<?php echo supapress_selected( $properties, 'sales_date_format', 'F j' ); ?>>
                                <?php _e( 'January', 'supapress' ) ?> 31
                            </option>
                            <option value="F jS"<?php echo supapress_selected( $properties, 'sales_date_format', 'F jS' ); ?>>
                                <?php _e( 'January', 'supapress' ) ?> 31st
                            </option>
                            <option value="jS M Y"<?php echo supapress_selected( $properties, 'sales_date_format', 'jS M Y' ); ?>>
                                31st <?php _e( 'Jan', 'supapress' ) ?> 1970
                            </option>
                            <option value="l, j M Y"<?php echo supapress_selected( $properties, 'sales_date_format', 'l, j M Y' ); ?>>
                                <?php _e( 'Monday', 'supapress' ) ?>, 31 <?php _e( 'Jan', 'supapress' ) ?> 1970
                            </option>
                            <option value="l, jS M Y"<?php echo supapress_selected( $properties, 'sales_date_format', 'l, jS M Y' ); ?>>
                                <?php _e( 'Monday', 'supapress' ) ?>, 31st <?php _e( 'Jan', 'supapress' ) ?> 1970
                            </option>
                            <option value="F Y"<?php echo supapress_selected( $properties, 'sales_date_format', 'F Y' ); ?>>
                                <?php _e( 'January', 'supapress' ) ?> 1970
                            </option>
                            <option value="m/d"<?php echo supapress_selected( $properties, 'sales_date_format', 'm/d' ); ?>>
                                12/31
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="selector">
		<?php  
		echo supapress_get_toggle_field( __( 'Search filters', 'supapress' ), 'show_filters', $properties, 'search_results' );
		echo supapress_get_toggle_field( __( 'Search sort by', 'supapress' ), 'show_sort_by', $properties, 'search_results' );
		echo supapress_get_toggle_field( __( 'Search per page', 'supapress' ), 'show_per_page', $properties, 'search_results' );
		echo supapress_get_toggle_field( __( 'Search pagination', 'supapress' ), 'show_pagination', $properties, 'search_results' );
		echo supapress_get_toggle_field( __( 'Pagination message', 'supapress' ), 'show_result_count', $properties, 'search_results' );
		echo supapress_get_toggle_field( __( 'Search term message', 'supapress' ), 'show_search_term', $properties, 'search_results' );
		echo supapress_get_toggle_field( __( 'Book cover', 'supapress' ), 'show_cover', $properties, '', $action );
		echo supapress_get_toggle_field( __( 'Title', 'supapress' ), 'show_title', $properties, '', $action );
		echo supapress_get_toggle_field( __( 'Subtitle', 'supapress' ), 'show_subtitle', $properties );
		echo supapress_get_toggle_field( __( 'Author name', 'supapress' ), 'show_author', $properties, '', $action );
		echo supapress_get_toggle_field( __( 'Author bio', 'supapress' ), 'show_author_bio', $properties );
        echo supapress_get_toggle_field( __( 'Edition', 'supapress' ), 'show_edition', $properties );
		echo supapress_get_toggle_field( __( 'Format', 'supapress' ), 'show_format', $properties );
		echo supapress_get_toggle_field( __( 'Publication date', 'supapress' ), 'show_pubdate', $properties );
		echo supapress_get_toggle_field( __( 'Summary', 'supapress' ), 'show_summary', $properties );
		echo supapress_get_toggle_field( __( 'Description', 'supapress' ), 'show_description', $properties );
		echo supapress_get_toggle_field( __( 'Price', 'supapress' ), 'show_price', $properties );
		echo supapress_get_toggle_field( __( 'Series', 'supapress' ), 'show_series', $properties );
		echo supapress_get_toggle_field( __( 'Imprint', 'supapress' ), 'show_imprint', $properties );
		echo supapress_get_toggle_field( __( 'Publisher', 'supapress' ), 'show_publisher', $properties );
		echo supapress_get_toggle_field( __( 'ISBN-13', 'supapress' ), 'show_isbn13', $properties );
		echo supapress_get_toggle_field( __( 'Trim size', 'supapress' ), 'show_trimsize', $properties, 'product_details' );
		echo supapress_get_toggle_field( __( 'Weight', 'supapress' ), 'show_weight', $properties, 'product_details' );
		echo supapress_get_toggle_field( __( 'Awards', 'supapress' ), 'show_awards', $properties, 'product_details' );
		echo supapress_get_toggle_field( __( 'Reviews', 'supapress' ), 'show_reviews', $properties, 'product_details' );
		echo supapress_get_toggle_field( __( 'Pages', 'supapress' ), 'show_pages', $properties );
		echo supapress_get_toggle_field( __( 'Retailers', 'supapress' ), 'show_retailers', $properties );
		echo supapress_get_toggle_field( __( 'Sales date', 'supapress' ), 'show_sales_date', $properties );
		?>
    </div>
</div>
