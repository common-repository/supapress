<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/** @type int $current */
/** @type string $retailerName */
/** @type string $retailerImage */
/** @type string $retailerLinkPattern */
/** @type string $retailerTrackingPattern */
/** @type array $formats */

?>
<div class="retailer-link supapress-accordion-wrapper">
	<?php echo supapress_get_element_template($retailerName ? $retailerName : __('New Retailer Link', 'supapress'), ($current === 1 ? true : false)); ?>
	<div class="supapress-accordion-content<?php echo $current === 1 ? '' : ' hide' ; ?>">
		<div class="supapress-accordion-content-inner">
			<div class="supapress-field-wrapper">
				<label class="supapress-label" for="widget_retailer_name<?php echo $current ; ?>">
					<?php _e('Retailer Name:', 'supapress' );?>
				</label>
				<input class="widget_retailer_name supapress-input widget_input_60" name="widget_retailer_name[]" id="widget_retailer_name<?php echo $current ; ?>" type="text" placeholder="Enter retailer name" value="<?php echo !empty($retailerName) ? $retailerName : "Retailer Name";?>" />
            </div>
            <div class="supapress-field-wrapper">
                <label class="supapress-label" for="retailer_image"><?php _e('Retailer Link Image:', 'supapress' );?></label>
                <img class="image-preview"
                     data-default-src="<?php echo $retailerImage; ?>"
                     src="<?php echo $retailerImage; ?>" alt="Retailer Link Image"
                     onerror="this.src = '<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/image-missing.jpg';" />
                <input class="supapress-input retailer-image-upload" id="retailer_image" data-button="retailer_image_button"
                       type="text" placeholder="Enter a URL or upload an image" name="retailer_image[]"
                       value="<?php echo $retailerImage; ?>" />
                <input id="retailer_image_button" class="upload_image_button upload-button" type="button" value="Upload Image" />
            </div>
			<div class="supapress-field-wrapper">
    			<?php supapress_url_pattern_label("widget_retailer_link_pattern{$current}", "Retailer Link pattern"); ?>
				<input class="supapress-input widget_link_pattern widget_input_60" name="widget_retailer_link_pattern[]" id="widget_retailer_link_pattern<?php echo $current ; ?>" placeholder="Enter retailer link pattern" type="text" value="<?php echo $retailerLinkPattern; ?>" />
			</div>
			<div class="supapress-field-wrapper">
				<label class="supapress-label"><?php _e('Retailer Link preview:', 'supapress' );?></label>
				<div class="widget_link_preview supapress-label"></div>
			</div>
			<div class="supapress-field-wrapper">
    			<?php supapress_url_pattern_label("widget_retailer_tracking_pattern{$current}", "Retailer Tracking pattern"); ?>
				<input class="supapress-input widget_link_pattern widget_input_60 no-preview tracking-pattern" name="widget_retailer_tracking_pattern[]" id="widget_retailer_tracking_pattern<?php echo $current ; ?>" placeholder="Enter retailer tracking pattern" type="text" value="<?php echo $retailerTrackingPattern; ?>" />
			</div>
			<div class="supapress-field-wrapper retailer-link-formats-wrapper">
                <label class="supapress-label supapress-tooltip-wrapper" for="retailer_link_formats<?php echo $current ; ?>">
                	<span><?php _e('Retailer Link Formats:', 'supapress' );?></span>
                	<span class="supapress-tooltip-icon" title="Select which formats the retailer link should be visible for. If no formats are selected the retailer link will show on all formats.">
                		<svg class="svg-icon">
                			<use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-tooltip"></use>
                		</svg>
                	</span>
                </label>
                <select id="retailer_link_formats<?php echo $current ; ?>" name="retailer_link_formats[<?php echo $current-1; ?>][]" class="retailer_link_formats supapress-dropdown" multiple="multiple" title="Click to select formats">
                    <?php foreach( $formats as $format ) : ?>
                        <option <?php echo supapress_multi_selected( $retailerLinkFormats, $current-1, $format->seo_name ); ?>value="<?php echo $format->seo_name; ?>"><?php echo $format->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
		</div>
	</div>
</div>