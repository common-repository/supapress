<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/** @type string $pageList */
/** @type boolean $clearCacheAllowed */
?>
<div class="wrap settings supapress-wrap" data-site-url="<?php echo SUPAPRESS_SITE_URL; ?>/">
	<?php include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/header.php'; ?>
    <?php do_action( 'supapress_admin_notices' ); ?>
	<form method="post" action="options.php" autocomplete="off" id="supapress-settings-form">
		<h2 class="nav-tab-wrapper">
			<a href="javascript:void(0);" data-tab="general" class="nav-tab nav-tab-active"><?php _e('General', 'supapress' );?></a>
			<a href="javascript:void(0);" data-tab="links" class="nav-tab"><?php _e('Book Page URLs', 'supapress' );?></a>
			<a href="javascript:void(0);" data-tab="retailer-links" class="nav-tab"><?php _e('Retailer Links', 'supapress' );?></a>
			<a href="javascript:void(0);" data-tab="cache" class="nav-tab"><?php _e('Cache', 'supapress' );?></a>
			<a href="javascript:void(0);" data-tab="seo" class="nav-tab"><?php _e('SEO', 'supapress' );?></a>
			<a href="javascript:void(0);" data-tab="advanced" class="nav-tab"><?php _e('Advanced', 'supapress' );?></a>
		</h2>
		<?php settings_fields( 'supapress-settings' ); ?>
		<?php do_settings_sections( 'supapress-settings' ); ?>
		<div class="general nav-tab-content">
			<div class="supapress-field-wrapper">
				<label class="supapress-label" for="api_key"><?php _e('API Key:', 'supapress' );?></label>
				<input class="supapress-input" name="api_key" id="api_key" type="text" placeholder="Enter your API key here" value="<?php echo esc_attr( get_option('api_key') ); ?>" />
			</div>
	        <div class="supapress-field-wrapper">
	            <label class="supapress-label" for="no_books"><?php _e('No books text (Optional):', 'supapress' );?></label>
	            <input class="supapress-input" name="no_books" id="no_books" type="text" placeholder="<?php echo SUPAPRESS_DEFAULT_NO_BOOKS_MESSAGE; ?>" value="<?php echo esc_attr( get_option('no_books') ); ?>" />
	        </div>
			<div class="supapress-field-wrapper">
				<label class="supapress-label" for="no_book"><?php _e('Book not found text (Optional):', 'supapress' );?></label>
				<input class="supapress-input" name="no_book" id="no_book" type="text" placeholder="<?php echo SUPAPRESS_DEFAULT_BOOK_NOT_FOUND_MESSAGE; ?>" value="<?php echo esc_attr( get_option('no_book') ); ?>" />
			</div>
			<div class="supapress-field-wrapper">
				<label class="supapress-label" for="service_url"><?php _e('Service URL (Optional):', 'supapress' );?></label>
				<input class="supapress-input" name="service_url" id="service_url" type="text" placeholder="Only change if you know what you're doing" value="<?php echo esc_attr( get_option('service_url') ); ?>" />
			</div>
		</div>
		<div class="links hide nav-tab-content">
			<div class="supapress-field-wrapper">
				<p class="green-heading supapress-tooltip-wrapper">
					<span><?php _e('Build book page urls', 'supapress' );?></span>
					<span class="supapress-tooltip-icon" title="<?php _e('Use the dropdowns below to show / hide your book page url settings.<br />---<br />Select the WordPress page containing your product details module from the dropdown then enter your URL pattern.', 'supapress' );?>">
						<svg class="svg-icon">
							<use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-tooltip"></use>
						</svg>
					</span>
				</p>
			</div>
			<div class="urls book-urls">
				<?php supapress_get_book_urls($pageList); ?>
			</div>
		</div>
		<div class="retailer-links hide nav-tab-content">
			<div class="supapress-field-wrapper">
				<p class="green-heading supapress-tooltip-wrapper">
					<span><?php _e('Retailer links', 'supapress' );?></span>
					<span class="supapress-tooltip-icon" title="<?php _e('Use the dropdowns below to show / hide your retailer links settings.<br />---<br />Enter a retailer name and enter a URL pattern.', 'supapress' );?>">
						<svg class="svg-icon">
							<use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-tooltip"></use>
						</svg>
					</span>
				</p>
			</div>
			<div class="retailers">
				<?php supapress_get_retailer_link(); ?>
			</div>
            <div class="add-new-wrapper">
                <a href="javascript:void(0)" class="add-new-button"><?php _e('+ Add New', 'supapress' );?></a>
				<img class="hide loading-icon" alt="Loading" src="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/predictive-loading.gif" />
            </div>
		</div>
		<div class="cache hide nav-tab-content">
            <div class="supapress-field-wrapper">
                <p class="supapress-paragraph">
					<span> 
						<?php printf( __('Each unique service call is currently being cached by default for %d hours.', 'supapress'), SUPAPRESS_CACHE_LIFETIME_DEFAULT / HOUR_IN_SECONDS);?>
                        <?php if($clearCacheAllowed) echo "<br/>" . __("Click the 'clear cache' button below to instantly clear this cache.", 'supapress'); ?>
                    </span>
                </p>
            </div>
            <?php if($clearCacheAllowed): ?>
                <div class="supapress-field-wrapper">
                    <p class="supapress-paragraph">
                        <span id="supapress-clear-cache-button"><?php _e('Clear Cache', 'supapress' );?></span>
                    </p>
                </div>
            <?php endif; ?>
			<?php
			$cache_settings = array(
				'product_details_cache_lifetime' => SUPAPRESS_CACHE_LIFETIME_PRODUCT_DETAILS,
				'search_results_cache_lifetime'  => SUPAPRESS_CACHE_LIFETIME_SEARCH_RESULTS,
				'isbn_lookups_cache_lifetime'    => SUPAPRESS_CACHE_LIFETIME_ISBN_LOOKUP
			);
			?>
            <div class="supapress-field-wrapper">
                <p class="supapress-paragraph">
                    <span><?php _e('You can override the duration of the cache for each type of service call below:', 'supapress' );?></span>
                </p>
            </div>
            <div class="supapress-field-wrapper supapress-cache-lifetime">
                <label for="supapress-product-details-cache-lifetime" class="supapress-label">
					<?php _e('Product Details Cache Lifetime:', 'supapress' );?>
				</label>
                <select id="supapress-product-details-cache-lifetime" name="product_details_cache_lifetime"
                        class="supapress-dropdown">
                    <option <?php echo supapress_selected( $cache_settings, 'product_details_cache_lifetime', '86400' ); ?>
                            value="86400">24 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'product_details_cache_lifetime', '57600' ); ?>
                            value="57600">16 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'product_details_cache_lifetime', '43200' ); ?>
                            value="43200">12 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'product_details_cache_lifetime', '28800' ); ?>
                            value="28800">8 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'product_details_cache_lifetime', '21600' ); ?>
                            value="21600">6 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'product_details_cache_lifetime', '14400' ); ?>
                            value="14400">4 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'product_details_cache_lifetime', '7200' ); ?>
                            value="7200">2 <?php _e('hours', 'supapress' );?>
                    </option>
                </select>
            </div>
            <div class="supapress-field-wrapper supapress-cache-lifetime">
                <label for="supapress-search-results-cache-lifetime" class="supapress-label">
					<?php _e('Search Results Cache Lifetime:', 'supapress' );?>
				</label>
                <select id="supapress-product-details-cache-lifetime" name="search_results_cache_lifetime"
                        class="supapress-dropdown">
                    <option <?php echo supapress_selected( $cache_settings, 'search_results_cache_lifetime', '86400' ); ?>
                            value="86400">24 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'search_results_cache_lifetime', '57600' ); ?>
                            value="57600">16 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'search_results_cache_lifetime', '43200' ); ?>
                            value="43200">12 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'search_results_cache_lifetime', '28800' ); ?>
                            value="28800">8 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'search_results_cache_lifetime', '21600' ); ?>
                            value="21600">6 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'search_results_cache_lifetime', '14400' ); ?>
                            value="14400">4 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'search_results_cache_lifetime', '7200' ); ?>
                            value="7200">2 <?php _e('hours', 'supapress' );?>
                    </option>
                </select>
            </div>
            <div class="supapress-field-wrapper supapress-cache-lifetime">
                <label for="supapress-isbn-lookups-cache-lifetime" class="supapress-label">
					<?php _e('ISBN Lookups Cache Lifetime:', 'supapress' );?>
				</label>
                <select id="supapress-product-details-cache-lifetime" name="isbn_lookups_cache_lifetime"
                        class="supapress-dropdown">
                    <option <?php echo supapress_selected( $cache_settings, 'isbn_lookups_cache_lifetime', '86400' ); ?>
                            value="86400">24 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'isbn_lookups_cache_lifetime', '57600' ); ?>
                            value="57600">16 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'isbn_lookups_cache_lifetime', '43200' ); ?>
                            value="43200">12 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'isbn_lookups_cache_lifetime', '28800' ); ?>
                            value="28800">8 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'isbn_lookups_cache_lifetime', '21600' ); ?>
                            value="21600">6 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'isbn_lookups_cache_lifetime', '14400' ); ?>
                            value="14400">4 <?php _e('hours', 'supapress' );?>
                    </option>
                    <option <?php echo supapress_selected( $cache_settings, 'isbn_lookups_cache_lifetime', '7200' ); ?>
                            value="7200">2 <?php _e('hours', 'supapress' );?>
                    </option>
                </select>
            </div>
        </div>
		<div class="seo hide nav-tab-content">
			<p class="green-heading supapress-tooltip-wrapper">
				<span><?php _e('Please enter the relevant SEO settings', 'supapress' );?></span>
					<span class="supapress-tooltip-icon" title="You will need to use the Yoast plugin in order to override the SEO values output by WordPress">
						<svg class="svg-icon">
							<use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-tooltip"></use>
						</svg>
					</span>
			</p>
			<div class="supapress-accordion-wrapper">
				<div class="element supapress-accordion-header open">	<span class="element-config-icon">		<span class="svg-right-arrow open">			<svg class="svg-icon">				<use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-right-arrow"/>			</svg>		</span>		<span>		  <?php _e('Product Details', 'supapress' );?>  		</span>	</span></div>	<div class="supapress-accordion-content" style="display: block;">
					<div class="supapress-accordion-content-inner">
						<div class="supapress-field-wrapper">
							<p class="supapress-paragraph">
								<span> 
									<?php echo __("We strongly recommend keeping this setting enabled to ensure your product details are correctly indexed.", 'supapress'); ?>
								</span>
							</p>
						</div>
						<div class="supapress-field-wrapper">
							<label class="supapress-label" for="supapress-product-details-seo-override">
								<?php _e('Override SEO:', 'supapress' );?>
							</label>
							<div class="onoffswitch">
								<input type="hidden" name="product_details_seo_override" value="off" />
								<input type="checkbox" name="product_details_seo_override" class="onoffswitch-checkbox" id="supapress-product-details-seo-override"<?php echo get_option('product_details_seo_override') === 'on' ? "checked='checked'" : ''; ?> />
								<label class="onoffswitch-label" for="supapress-product-details-seo-override">
									<span class="onoffswitch-inner" data-label-before="Yes" data-label-after="No"></span>
									<span class="onoffswitch-switch"></span>
								</label>
							</div>
						</div>
						<div class="supapress-field-wrapper">
							<label for="supapress-product-details-seo-title" class="supapress-label">
								<?php _e('Title:', 'supapress' );?>
							</label>
							<input class="widget_link_pattern supapress-input widget_input_60" data-default="%title%" id="supapress-product-details-seo-title" name="product_details_seo_title" type="text" placeholder="%title%" value="<?php echo esc_attr( get_option('product_details_seo_title') ); ?>" />
						</div>

						<div class="supapress-field-wrapper">
							<label for="supapress-product-details-seo-description" class="supapress-label">
								<?php _e('Meta description:', 'supapress' );?>
							</label>
							<input class="widget_link_pattern supapress-input widget_input_60" data-default="%description%" id="supapress-product-details-seo-description" name="product_details_seo_description" type="text" placeholder="%description%" value="<?php echo esc_attr( get_option('product_details_seo_description') ); ?>" />
						</div>

						<div class="supapress-field-wrapper">
							<label for="supapress-product-details-seo-canonical" class="supapress-label">
								<?php _e('Canonical:', 'supapress' );?>
							</label>
							<div class="supapress-links-wrapper">
								<div class="supapress-domain-slug code"><span><?php echo get_site_url(); ?></span></div>
								<div class="supapress-link-pattern-wrapper">
									<input class="widget_link_pattern supapress-input" data-default="/%isbn13%" data-trim-trailing-slash="true" id="supapress-product-details-seo-canonical" name="product_details_seo_canonical" type="text" placeholder="/%isbn13%" value="<?php echo esc_attr( get_option('product_details_seo_canonical') ); ?>" />
								</div>
							</div>
						</div>

						<div class="supapress-field-wrapper">
							<label for="supapress-product-details-seo-canonical-primary-format" class="supapress-label">
								<?php _e('Redirect to primary format:', 'supapress' );?>
							</label>
							<div class="onoffswitch">
								<input type="hidden" name="product_details_seo_primary_format_canonical" value="off" />
								<input type="checkbox" name="product_details_seo_primary_format_canonical" class="onoffswitch-checkbox" id="supapress-product-details-seo-canonical-primary-format" <?php echo get_option('product_details_seo_primary_format_canonical') === 'on' ? "checked='checked'" : ''; ?> />
								<label class="onoffswitch-label" for="supapress-product-details-seo-canonical-primary-format">
									<span class="onoffswitch-inner" data-label-before="Yes" data-label-after="No"></span>
									<span class="onoffswitch-switch"></span>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="advanced hide nav-tab-content">
			<div class="supapress-field-wrapper">
				<p class="supapress-paragraph">
					<span><?php _e("If you would like to enable AngularJS support for your site, turn on the option below.", "supapress" );?></span>
					<br />
					<span><?php printf( __('You will need to make sure that you add %s as a dependency of you AngularJS app as the code below shows:', 'supapress'), '<b>supapressAngular</b>');?></span>
				</p>
				<code class="supapress-javascript-code">
					<span class="keyword">var</span>
					myApp
					<span class="operator">=</span>
					angular<span class="punctuation">.</span><span class="function">module</span><span class="punctuation">(</span><span class="string">'myApp'</span><span class="punctuation">,</span>
					<span class="punctuation">[</span><span class="string">'supapressAngular'</span><span class="punctuation">]</span><span class="punctuation">)</span><span class="punctuation">;</span>
				</code>
			</div>
			<div class="supapress-field-wrapper">
				<label class="supapress-label" for="angularjs_support"><?php _e("Enable AngularJS support:", "supapress" );?></label>
				<div class="onoffswitch">
					<input type="hidden" value="off" name="angularjs_support">
					<input type="checkbox" id="angularjs_support" class="onoffswitch-checkbox" name="angularjs_support"<?php echo esc_attr( get_option( 'angularjs_support' ) ) == 'on' ? ' checked="checked"' : ''; ?>>
					<label for="angularjs_support" class="onoffswitch-label">
						<span class="onoffswitch-inner" data-label-before="Yes" data-label-after="No"></span>
						<span class="onoffswitch-switch"></span>
					</label>
				</div>
			</div>
		</div>
		<div class="save-button-wrapper">
			<?php submit_button( __( 'Save Changes', 'supapress'), 'save-button', 'submit', false); ?>
		</div>
	</form>
</div>
