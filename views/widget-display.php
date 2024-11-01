<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/** @type int $widgetId */ ?>
<aside class="widget widget_supapress_widgets">
	<?php echo do_shortcode('[supapress id="' . $widgetId . '"]'); ?>
</aside>