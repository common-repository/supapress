<?php

if ( ! defined( 'ABSPATH' ) ) exit;

add_action('widgets_init', 'supapress_load_widgets');

add_action( 'admin_enqueue_scripts', 'supapress_admin_enqueue_scripts' );

function supapress_load_widgets() {
	register_widget('Supapress_Search');
	register_widget('Supapress_Widgets');
	register_widget('Supapress_ISBN_Lookup');
	register_widget('Supapress_Search_Results');
	register_widget('Supapress_Product_Details');
}

class Supapress_Search extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'supapress_search', 'description' => 'Display the Supafolio search form.');
		$control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'supapress-search-widget');
		parent::__construct('supapress-search-widget', 'Supafolio Search', $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		$pageId = isset( $instance['searchPage'] ) ? $instance['searchPage'] : "";

		/** @noinspection PhpUnusedLocalVariableInspection used in view called by supapress_locate_template */
		$action = $pageId === 0 ? "" : get_page_link($pageId);

		/** @noinspection PhpUnusedLocalVariableInspection used in view called by supapress_locate_template */
		$value = isset( $_GET['keyword'] ) ? sanitize_text_field(stripslashes($_GET['keyword'])) : "";
		
		/** @noinspection PhpUnusedLocalVariableInspection used in view called by supapress_locate_template */
		$placeholder = isset( $instance['placeholder'] ) ? $instance['placeholder'] : "";

		include supapress_locate_template('widget-search-form');
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['searchPage'] = (int) $new_instance['searchPage'];
		$instance['placeholder'] = (string) $new_instance['placeholder'];
		return $instance;
	}

	function form($instance) {
		$defaults = array('searchPage' => "", "placeholder" => "Book search");
		$instance = wp_parse_args((array) $instance, $defaults); ?>

		<p>
			<label for="<?php echo $this->get_field_id('searchPage'); ?>">Choose a page:</label>
			<select id="<?php echo $this->get_field_id('searchPage'); ?>" name="<?php echo $this->get_field_name('searchPage'); ?>" class="widefat" style="margin-top: 5px;">
				<option value="">- None -</option>
				<?php
				foreach (get_pages() as $page) {
					$selected = $instance['searchPage'] === $page->ID ? ' selected="selected"' : '';
					echo '<option' . $selected . ' value="' . $page->ID .'">' . $page->post_title . '</option>';
				}
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('placeholder'); ?>">Placeholder text:</label>
			<input id="<?php echo $this->get_field_id('placeholder'); ?>" name="<?php echo $this->get_field_name('placeholder'); ?>" value="<?php echo $instance['placeholder']; ?>" class="widefat" style="margin-top: 5px;" type="text" />
		</p> <?php
	}
}

class Supapress_Widgets extends WP_Widget {
    protected $labels = array(
        'isbn_lookup' => 'ISBN Lookup',
        'product_details' => 'Product Details',
        'search_results' => 'Search Results'
    );

	function __construct( $id_base = 'supapress-widgets', $name = 'Supafolio Module', $widget_options = array(), $control_options = array() ) {
	    if(empty($widget_ops)) {
		    $widget_ops = array('classname' => 'supapress_widgets', 'description' => 'Display a Supafolio module.');
        }
        if(empty($control_ops)) {
	        $control_ops = array('width' => 300, 'height' => 350, 'id_base' => $id_base);
        }
		parent::__construct($id_base, $name, $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		/** @noinspection PhpUnusedLocalVariableInspection used in view called by supapress_locate_template */
		$widgetId = isset( $instance['widgetId'] ) ? $instance['widgetId'] : "";

		include supapress_locate_template('widget-display');
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['widgetId'] = (int) $new_instance['widgetId'];
		return $instance;
	}

	function form($instance) {
	    $this->renderForm($instance);
    }

	function renderForm($instance, $type = '') {
		$defaults = array( 'widgetId' => '');
		$instance = wp_parse_args( (array) $instance, $defaults );
		$prefix = 'a';

		$params = array(
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'post_type' => 'supapress_widget'
		);

		if(!empty($type)) {
			$params['meta_query'] = array(
				array(
					'key' => '_widget_type',
					'value' => $type
				)
			);

			$label = $this->labels[$type];
			$prefix = $type === 'isbn_lookup' ? 'an' : $prefix;
		} else {
			$label = 'module';
        }

		$posts = get_posts($params);

		?>
        <p class="supapress-has-modules">
            <label for="<?php echo $this->get_field_id('widgetId'); ?>">Choose <?php echo $prefix; ?> <?php echo $label; ?> or <a class="supapress-widget-control-module-new" data-admin-url="<?php echo admin_url( 'admin.php?page=supapress-new&type=' . $type ); ?>">add new</a></label>
            <br/>
            <select id="<?php echo $this->get_field_id('widgetId'); ?>" name="<?php echo $this->get_field_name('widgetId'); ?>" class="widefat supapress-widget-control-module-list">
                <option value="">- None -</option>
				<?php
				foreach ($posts as $post) {
					$selected = $instance['widgetId'] === $post->ID ? ' selected="selected"' : '';
					echo '<option' . $selected . ' value="' . $post->ID .'">' . $post->post_title . '</option>';
				}
				?>
            </select>
            <a class="supapress-widget-control-module-edit" data-admin-url="<?php echo admin_url( 'admin.php?page=supapress&action=edit' ); ?>">edit</a>
        </p>
        <p class="supapress-has-modules">
            Can't see your <?php echo $label; ?>? <a class="supapress-widget-control-module-refresh" data-admin-url="<?php echo admin_url( 'admin-ajax.php?action=supapress_get_module_list' ); ?>">refresh list</a>
        </p>
        <p class="supapress-no-modules">
            Create your first <?php echo $label; ?>
            <br/>
            <a class="supapress-widget-control-module-new" data-admin-url="<?php echo admin_url( 'admin.php?page=supapress-new&type=' . $type ); ?>">add new</a>
        </p>
        <p class="supapress-no-modules">
            <a class="supapress-widget-control-module-refresh" data-admin-url="<?php echo admin_url( 'admin-ajax.php?action=supapress_get_module_list' ); ?>">refresh</a> this panel after creating your <?php echo $label; ?>
        </p>
        <?php $scriptId = uniqid('supapress-widget-script-'); ?>
        <script id="<?php echo $scriptId; ?>">
            (function($) {
                $(function() {
                    var type = '<?php echo $type; ?>',
                        hasModules = <?php echo empty($posts) ? 'false' : 'true';?>,
                        $form = $('#<?php echo $scriptId; ?>').parents('form'),
                        $moduleList = $form.find('.supapress-widget-control-module-list'),
                        $editButton = $form.find('.supapress-widget-control-module-edit'),
                        $newButton = $form.find('.supapress-widget-control-module-new'),
                        $refreshButton = $form.find('.supapress-widget-control-module-refresh'),
                        $hasModules = $form.find('.supapress-has-modules'),
                        $noModules = $form.find('.supapress-no-modules'),
                        $loading = null,
                        refreshModuleList = function(e) {
                            e.preventDefault();
                            e.stopPropagation();

                            if(!$loading) {
                                $loading = $('<img>').attr('src', '<?php echo plugin_dir_url( __FILE__ ) . '/admin/img/ajax-loader-small.gif'; ?>');
                                $(this).parent().append($loading);
                            }

                            $.post({
                                url: $refreshButton.data('adminUrl'),
                                dataType: 'json',
                                data: {
                                    module_type: type
                                },
                                success: function(data) {
                                    var $first = $moduleList.children().first().clone(),
                                        selected = $moduleList.val();

                                    if(data.length) {
                                        $moduleList.empty();
                                        $moduleList.append($first);

                                        $(data).each(function(index, item) {
                                            var $option = $('<option value="' + item.ID + '"' + (item.ID.toString() === selected ? ' selected="selected"' : '') + '>' + item.post_title + '</option>');
                                            $moduleList.append($option);
                                        });

                                        $hasModules.show();
                                        $noModules.hide();
                                    } else {
                                        $hasModules.hide();
                                        $noModules.show();
                                    }

                                    if($loading) {
                                        $loading.remove();
                                        $loading = null;
                                    }
                                },
                                fail: function() {
                                    alert('There has been an error. Please refresh this page.');

                                    if($loading) {
                                        $loading.remove();
                                        $loading = null;
                                    }
                                }
                            });
                        },
                        winName = 'supapress-edit',
                        win;

                    $editButton.on('click', function() {
                        var val = $moduleList.val();

                        if(!val) {
                            alert('Please select a module to edit');
                        } else {
                            win = window.open($editButton.data('adminUrl') + '&post=' + val, winName);
                            $(win).on('unload', refreshModuleList);
                        }
                    });

                    $newButton.on('click', function () {
                        win = window.open($newButton.data('adminUrl'), winName);
                        $(win).on('unload', refreshModuleList);
                    });

                    $refreshButton.on('click', refreshModuleList);

                    if(hasModules) {
                        $hasModules.show();
                        $noModules.hide();
                    } else {
                        $hasModules.hide();
                        $noModules.show();
                    }
                });
            })(jQuery);
        </script>
        <style>
            .supapress-has-modules,
            .supapress-no-modules {
                display: none;
            }

            .supapress-has-modules select {
                margin-top: 15px;
            }

            .supapress-has-modules a,
            .supapress-no-modules a {
                color: #21759b!important;
                text-decoration: underline!important;
                cursor: pointer;
            }

            .supapress-has-modules a:hover,
            .supapress-no-modules a:hover {
                color: #d54e21!important;
            }

            .supapress-widget-control-module-edit {
                display: inline-block;
                margin-left: 5px;
            }
        </style>
        <?php
	}
}

class Supapress_ISBN_Lookup extends Supapress_Widgets {
	function __construct() {
		$widget_ops = array('classname' => 'supapress_isbn_lookup', 'description' => 'Display a Supafolio ISBN Lookup.');
		$control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'supapress-isbn-lookup');
		parent::__construct('supapress-isbn-lookup', 'Supafolio ISBN Lookup', $widget_ops, $control_ops);
	}

    public function form($instance) {
        $this->renderForm($instance, 'isbn_lookup');
    }
}

class Supapress_Product_Details extends Supapress_Widgets {
	function __construct() {
		$widget_ops = array('classname' => 'supapress_product_details', 'description' => 'Display a Supafolio Product Details.');
		$control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'supapress-product-details');
		parent::__construct('supapress-product-details', 'Supafolio Product Details', $widget_ops, $control_ops);
	}

    public function form($instance) {
        $this->renderForm($instance, 'product_details');
    }
}

class Supapress_Search_Results extends Supapress_Widgets {
	function __construct() {
		$widget_ops = array('classname' => 'supapress_search_results', 'description' => 'Display a Supafolio Search Results.');
		$control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'supapress-search-results');
		parent::__construct('supapress-search-results', 'Supafolio Search Results', $widget_ops, $control_ops);
	}

    public function form($instance) {
        $this->renderForm($instance, 'search_results');
    }
}