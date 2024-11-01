<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/** @type SupaPress_Widget $post */
$action = supapress_current_page();
$properties = $action === 'edit' ? $post->get_properties() : array();
$bookUrls = supapress_get_book_pattern_list();
?>
<div class="wrap supapress-wrap <?php echo $action; ?>">
	<?php include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/header.php'; ?>
	<?php do_action( 'supapress_admin_notices' ); ?>
	<form id="supapress-add-edit-form" method="post" autocomplete="off">
		<?php include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/add-edit-partials/step1.php'; ?>
        <?php include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/add-edit-partials/step2.php'; ?>
	</form>
</div>