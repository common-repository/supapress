<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/** @type string $for */
/** @type int $label */
?>
<label class="supapress-label supapress-tooltip-wrapper" for="<?php echo $for; ?>">
	<span><?php echo $label; ?>:</span>
	<span class="supapress-tooltip-icon" title="To access helpful data placeholders try typing '%' to open the autocomplete menu.<br />---<br />The ISBN-13 placeholder is a required value.">
		<svg class="svg-icon">
			<use xlink:href="<?php echo SUPAPRESS_PLUGIN_URL; ?>/admin/img/svg/sprite.svg#icon-tooltip"></use>
		</svg>
	</span>
</label>