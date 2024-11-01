<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/** @type string $action */
/** @type string $placeholder */
/** @type string $value */
?>
<aside class="widget widget_supapress_search">
    <form role="search" method="get" class="search-form" action="<?php echo $action; ?>">
        <label>
            <span class="screen-reader-text"><?php echo esc_html($placeholder); ?>:</span>
            <input class="search-field" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($value); ?>" name="keyword" title="<?php echo esc_attr($placeholder); ?>:" type="search" />
        </label>
        <input class="search-submit screen-reader-text" value="Search" type="submit">
    </form>
</aside>