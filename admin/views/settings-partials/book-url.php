<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/** @type string $pageList */
/** @type int $current */
/** @type string $linkPage */
/** @type string $linkPattern */
?>
<div class="book-url supapress-accordion-wrapper">
	<?php echo supapress_get_element_template(  __( 'Book page URL', 'supapress') . " {$current}", ($current === 1 ? true : false)); ?>
	<div class="supapress-accordion-content<?php echo $current === 1 ? '' : ' hide' ; ?>">
		<div class="supapress-accordion-content-inner">
			<div class="supapress-field-wrapper">
				<label class="supapress-label" for="widget_book_link_page<?php echo $current ; ?>">
					<?php _e('Book WordPress page or external url:', 'supapress' );?>
				</label>
				<select class="supapress-dropdown widget_link_page" name="widget_book_link_page[]" id="widget_book_link_page<?php echo $current ; ?>" data-value="<?php echo esc_attr( $linkPage ); ?>">
					<?php echo $pageList; ?>
				</select>
			</div>
			<div class="supapress-field-wrapper">
				<label class="supapress-label supapress-tooltip-wrapper" for="widget_book_link_pattern<?php echo $current ; ?>">
					<span><?php _e('Book url pattern:', 'supapress' );?></span>
					<span class="supapress-tooltip-icon" title="To access helpful data placeholders try typing '%' to open the autocomplete menu.<br />---<br />The ISBN-13 placeholder is a required value.">
						<svg class="svg-icon">
							<use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-tooltip"></use>
						</svg>
					</span>
				</label>
				<div class="supapress-links-wrapper">
					<div class="supapress-domain-slug code<?php if ($linkPage<0) echo ' hide'; ?>"><span><?php echo get_site_url(); ?></span></div>
					<div class="supapress-link-pattern-wrapper">
						<input class="supapress-input widget_link_pattern" data-default="/%isbn13%" data-trim-trailing-slash="true" name="widget_book_link_pattern[]" id="widget_book_link_pattern<?php echo $current ; ?>" type="text" value="<?php echo esc_attr( $linkPattern ); ?>" />
					</div>
				</div>

			</div>
			<div class="supapress-field-wrapper">
				<label class="supapress-label"><?php _e('Book url preview:', 'supapress' );?></label>
				<div class="widget_link_preview supapress-label"></div>
			</div>
		</div>
	</div>
</div>