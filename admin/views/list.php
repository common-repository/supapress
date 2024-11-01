<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/** @type SupaPress_Widget_List_Table $list_table */
$title = esc_html( 'Supafolio Modules' );
$addNew = ' <a href="' . esc_url( menu_page_url( 'supapress-new', false ) ) . '" class="add-new-button">' . esc_html( '+ Add New' ) . '</a>';
$searchTerm = !empty( $_REQUEST['s'] ) ? sprintf( '<span class="subtitle">Search results for &#8220;%s&#8221;</span>', esc_html( $_REQUEST['s'] ) ) : '';
?>
<div class="wrap supapress-wrap">
	<?php include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/header.php'; ?>
	<?php do_action( 'supapress_admin_notices' ); ?>
	<form method="get">
		<div class="title-wrapper list-page-title extended-width inline-block">
			<span class="list-title"><?php echo $title; ?></span>
		</div>
		<?php $list_table->search_box( 'Find your module', 'supapress-widget' ); ?>
		<div class="search-term-wrapper"><?php echo $searchTerm; ?></div>
		<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
		<?php $list_table->display(); ?>
		<div class="add-new-wrapper"><?php echo $addNew; ?></div>
	</form>
</div>