<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/** @type string $action */ ?>
<div class="content nav-tab-content">
	<div class="supapress-field-wrapper">
		<p class="green-heading"><?php _e( 'Select how you want to see your ISBN(s):', 'supapress' ); ?></p>
	</div>
	<div class="supapress-field-wrapper">
		<label for="lookup_source" class="supapress-label">
			<?php _e( 'Select where your ISBN(s) come from:', 'supapress' ); ?>
		</label>
		<select name="lookup_source" id="lookup_source" class="supapress-dropdown">
			<option value="manual"<?php echo supapress_selected($properties, 'lookup_source', 'manual'); ?>>
				<?php _e( 'Manually enter single ISBN(s)', 'supapress' ); ?>
			</option>
			<option value="bulk"<?php echo supapress_selected($properties, 'lookup_source', 'bulk'); ?>>
				<?php _e( 'Manually enter bulk ISBN(s)', 'supapress' ); ?>
			</option>
			<option value="collection"<?php echo supapress_selected($properties, 'lookup_source', 'collection'); ?>>
				<?php _e( 'Display a Supafolio collection', 'supapress' ); ?>
			</option>
		</select>
	</div>
	<div class="hide manual lookup-source-input supapress-field-wrapper">
		<label class="supapress-label" for="isbn_lookup"><?php _e( 'Search catalogue:', 'supapress' ); ?></label>
		<input name="isbn_lookup" class="supapress-input" id="isbn_lookup" type="text" data-ajax-url="<?php echo admin_url('admin-ajax.php'); ?>" placeholder="Enter product title or ISBN" />
	</div>
	<div class="hide bulk lookup-source-input supapress-field-wrapper">
		<label class="supapress-label" for="isbn_lookup_bulk"><?php _e( 'Enter ISBN(s):', 'supapress' ); ?></label>
		<textarea name="isbn_lookup_bulk" class="supapress-input" id="isbn_lookup_bulk" type="text" data-ajax-url="<?php echo admin_url('admin-ajax.php'); ?>" placeholder="Enter product ISBN(s) - one per line or comma separated"></textarea>
		<a id="supapress-add-bulk-isbns-button"><?php _e( 'Add ISBN(s)', 'supapress' ); ?></a>
	</div>
	<div class="hide bulk manual lookup-source-input supapress-field-wrapper">
		<select name="isbn_list[]" id="isbn_list" data-svg-url="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-three-squares" multiple="multiple" title="Click to select isbn">
			<?php if(isset($properties['isbn_list'])) : ?>
				<?php foreach($properties['isbn_list'] as $key => $value) : ?>
					<option <?php echo supapress_invalid_book_class($value); ?>data-isbn="<?php echo $key; ?>" value="<?php echo "{$key}|||{$value}"; ?>" selected="selected"><?php echo "{$value} ({$key})"; ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
		</select>
	</div>
	<div class="hide collection lookup-source-input supapress-field-wrapper">
		<label for="lookup_collection" class="supapress-label"><?php _e( 'Select a collection:', 'supapress' ); ?></label>
		<select name="lookup_collection" class="supapress-dropdown" id="lookup_collection" data-value="<?php echo supapress_property_value($properties, 'lookup_collection'); ?>"></select>
		<a href="#" target="_blank" class="edit-collection-button"><?php _e( 'Edit collection', 'supapress' ); ?></a>
	</div>
</div>