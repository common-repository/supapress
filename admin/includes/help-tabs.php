<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SupaPress_Help_Tabs {

	private $screen;

	public function __construct( WP_Screen $screen ) {
		$this->screen = $screen;
	}

	public function set_help_tabs( $type ) {
		switch ( $type ) {
			case 'list':
				$this->screen->add_help_tab( array(
					'id' => 'list_overview',
					'title' => 'Overview',
					'content' => $this->content( 'list_overview' )
				) );

				$this->screen->add_help_tab( array(
					'id' => 'list_available_actions',
					'title' => 'Available Actions',
					'content' => $this->content( 'list_available_actions' )
				) );

				$this->sidebar();

				return;
			case 'add':
				$this->screen->add_help_tab( array(
					'id' => 'add_new',
					'title' => 'Adding A Module',
					'content' => $this->content( 'add_new' )
				) );

				$this->sidebar();

				return;
			case 'edit':
				$this->screen->add_help_tab( array(
					'id' => 'edit_overview',
					'title' => 'Overview',
					'content' => $this->content( 'edit_overview' )
				) );

				$this->sidebar();

				return;
		}
	}

	private function content( $name ) {
		$content = array();

		$content['list_overview'] = '<p>On this screen, you can manage modules provided by Supafolio. You can manage an unlimited number of modules. Each module has a unique ID and Supafolio shortcode ([supapress ...]). To insert a module into a post or a text widget, insert the shortcode into the target.</p>';
		$content['list_available_actions'] = '<p>Hovering over a row in the modules list will display action links that allow you to manage your module. You can perform the following actions:</p>';
		$content['list_available_actions'] .= '<p><strong>Edit</strong> - Navigates to the editing screen for that module. You can also reach that screen by clicking on the module title.</p>';
		$content['list_available_actions'] .= '<p><strong>Duplicate</strong> - Clones that module. A cloned module inherits all content from the original, but has a different ID.</p>';
		$content['list_available_actions'] .= '<p><strong>Delete</strong> - Deletes that module.</p>';
		$content['add_new'] = '<p>You can add a new module on this screen.</p>';
		$content['edit_overview'] = '<p>On this screen, you can edit a module. A module will be one of the following: <ul><li>ISBN Lookup</li><li>Search Results</li><li>Product Details</li></ul></p>';

		if ( ! empty( $content[$name] ) ) {
			return $content[$name];
		}
	}

	public function sidebar() {
		$content = '<p><strong>For more information:</strong></p>';
		$content .= '<p><a href="http://www.supadu.com/" target="_blank">Docs</a></p>';
		$content .= '<p><a href="http://www.supadu.com/" target="_blank">FAQ</a></p>';
		$content .= '<p><a href="http://www.supadu.com/" target="_blank">Support</a></p>';

		$this->screen->set_help_sidebar( $content );
	}
}