<?php

add_action('in_widget_form', 'conditional_widgets_form', 10, 3);
add_filter('widget_update_callback', 'conditional_widgets_update', 10, 2);

/**
 * Display the form at the bottom of each widget.
 */
function conditional_widgets_form($widget, $return, $instance) {

	// always show the form
	if ($return == 'noform') { $return = true; }
	
	//prefill variables
	$instance = conditional_widgets_init_instance($instance);
	
	//whether to display ON or OFF so users can easily see which widgets are conditional	

	$active = cets_conditional_widgets_instance_is_active($instance);

	?>
		
	<div id="cets-conditional-widget">	
		<div class="conditional-widget-top">
        	<div class="conditional-widget-title-action">
            	<a href="#" id="conditional-widget-toggle-wrap-<?php print $widget->id ?>" onclick="conditional_widgets_form_toggle('conditional-widget-form-<?php print $widget->id; ?>'); return false;"></a>
            </div>
            
            <div class="conditional-widget-title">
                <h5><?php _e('Widget Display Control'); ?>
                <?php 
                if($active) {
					?>
					&nbsp;<span class="conditional-widgets-active"><?php _e('ON'); ?></span>
					<?php
                } else {
					?>
                    &nbsp; <span class="conditional-widgets-inactive"><?php _e('OFF'); ?></span>
					<?php }
				?>
                </h5>
            </div>
		</div>
		
		<div class="conditional-widget-form" id="conditional-widget-form-<?php print $widget->id; ?>">
			
			<p class='cw-instructions'>
			<?php _e('Select a combination of options to control on which sections of your site this widget is shown.'); ?>
			</p>
			
			<p>
			<input type="checkbox" name="cw_home_enable_checkbox" <?php checked($instance['cw_home_enable_checkbox']); ?>> <?php conditional_widgets_form_show_hide_select('cw_select_home_page', $instance['cw_select_home_page'], true); ?> <?php _e('on Front Page'); ?>
			</p>
			

			
			
			<?php
				

				$type_tax_pairs = apply_filters('conditional_widgets_type_tax_pairs', array());
				
				
				if ($type_tax_pairs) {
					foreach($type_tax_pairs as $pair) {
						
						

						$tax = $pair['tax'];
						$type = $pair['type'];

						
						// valid taxonomy
						$taxonomy_object = get_taxonomy($tax);
						if ($taxonomy_object == false) {
							continue;
						}

						// valid type
						$post_type_object = get_post_type_object($type);
						if (!$post_type_object) {
							continue;
						}
						
						// taxonomy applied to type
						// TODO - validate

						$selected_ids = array();
						// for prefilling form fields..
						if (isset($instance['cw_custom'][$type][$tax])) {
							
							$custom_subdata = $instance['cw_custom'][$type][$tax];
						
						} else {
							$custom_subdata = conditional_widgets_get_default_custom_subdata();
						}
						
						if (isset($custom_subdata['selected_ids'])) {
							$selected_ids = $custom_subdata['selected_ids'];
						} 

						


						echo "<h6 class='conditional-widget-header conditional-widget-sub-heading'>{$taxonomy_object->labels->name}</h6>";
						echo "<input type='checkbox' name='cw_custom[{$type}][{$tax}][enable]' value='1' " . checked($custom_subdata['enable'], 1, 0) . "> Enable " . $post_type_object->labels->singular_name . " Logic and ";
						 conditional_widgets_form_show_hide_select("cw_custom[$type][$tax][select]", $custom_subdata['select']);
						echo " on " . $post_type_object->labels->name . " in selected " . $taxonomy_object->labels->name . ":";

						echo "<p>";
							echo "<span class='cw_sub_checkbox'><input type='checkbox' name='cw_custom[{$type}][{$tax}][all]' value='1' " . checked($custom_subdata['all'], 1, 0) . " > ALL {$taxonomy_object->labels->name} (or select below)<br/></span>";
								if (is_taxonomy_hierarchical($tax)) {
									echo "<span class='cw_sub_checkbox'><input type='checkbox' name='cw_custom[{$type}][{$tax}][sub]' " . checked($custom_subdata['sub'], 1, 0) . "> Include sub-{$taxonomy_object->labels->name} automatically</span>";
								}
						echo "</p>";

						echo "<div class='conditional-widgets-checkbox-wrapper'>";



						conditional_widgets_term_checkboxes($tax, $post_type_object->name, $selected_ids);
						echo "</div>";

					}
				}


			?>





			<h6 class="conditional-widget-header conditional-widget-sub-heading"><?php _e('Pages'); ?></h6>
			
			<p>
			<input type="checkbox" name="cw_pages_enable_checkbox" <?php checked($instance['cw_pages_enable_checkbox']); ?>> <?php _e('Enable Page Logic and '); ?>
			<?php conditional_widgets_form_show_hide_select('cw_select_pages', $instance['cw_select_pages'], false); ?> <?php _e('on selected Pages:'); ?><br>
				<span class='cw_sub_checkbox'>
					<?php
					if ( ! isset($instance['cw_pages_all']) ) {
						$instance['cw_pages_all'] = 0;
					}
					?>
					<input type="checkbox" name="cw_pages_all" value="1" <?php checked($instance['cw_pages_all']); ?>> <?php _e('ALL pages (or select below)'); ?><br/>
				</span>

				<span class='cw_sub_checkbox'>
					<input type="checkbox" name="cw_pages_sub_checkbox" <?php checked($instance['cw_pages_sub_checkbox']); ?>> <?php _e('Include sub-pages automatically'); ?>
				</span>
			</p>
			
			<div class='conditional-widgets-checkbox-wrapper'>
			<?php
			$selected_pages = array();
			if (isset($instance['cw_selected_pages'])) {
				if (is_array($instance['cw_selected_pages'])) {
					$selected_pages = $instance['cw_selected_pages'];
				}
			}
			conditional_widgets_page_checkboxes($selected_pages);
			?>
			</div>
		
			<h6 class="conditional-widget-header conditional-widget-sub-heading"><?php _e('Special Page Options'); ?></h6>
			
			<ul class="conditional-widgets-special-page-option-list">
				<!-- posts page -->
				<li>
					<input type="checkbox" name="cw_posts_page_hide_checkbox" <?php checked($instance['cw_posts_page_hide']); ?>>	<?php _e('Hide on Posts Page (when using a static front page)'); ?>
				</li>
				
				<!-- 404 -->
				<li>
					<input type="checkbox" name="cw_404_hide_checkbox" <?php checked($instance['cw_404_hide']); ?>>	<?php _e('Hide on 404s (Page Not Found)'); ?>
				</li>
				
				<!-- search results -->
				<li>
					<input type="checkbox" name="cw_search_hide_checkbox" <?php checked($instance['cw_search_hide']); ?>>	<?php _e('Hide when displaying Search Results'); ?>
				</li>
			
				<!-- archives -->
				<li>
					<input type="checkbox" name="cw_author_archive_hide_checkbox" <?php checked($instance['cw_author_archive_hide']); ?>>	<?php _e('Hide on Author Archives'); ?>
				</li>
				<li>
					<input type="checkbox" name="cw_date_archive_hide_checkbox" <?php checked($instance['cw_date_archive_hide']); ?>>	<?php _e('Hide on Date Archives'); ?>
				</li>
				<li>
					<input type="checkbox" name="cw_tag_archive_hide_checkbox" <?php checked($instance['cw_tag_archive_hide']); ?>>	<?php _e('Hide on Tag Archives'); ?>
				</li>
				
				
			</ul>
			
		
		
		
		</div> <!-- toggled div -->
	
	</div> <!-- /#cets-conditional-widgets -->
	
	
	
	
	<?php
}  // /function form()



/**
 * Process the form submission. (Save settings.)
 */
function conditional_widgets_update($new_instance, $old_instance) {
	
	$instance = $new_instance;  //save old data, and only change the following stuff:
	
	//home
	$instance['cw_home_enable_checkbox'] = isset($_POST['cw_home_enable_checkbox']) ? 1:0;
	$instance['cw_select_home_page'] = $_POST['cw_select_home_page'];
	
	
	// custom types, including posts and categories - since 1.9
	$type_tax_pairs = apply_filters('conditional_widgets_type_tax_pairs', array());
	
	foreach($type_tax_pairs as $pair) {
		
		// todo - validate
		$type = $pair['type'];
		$tax = $pair['tax'];
	
		$custom_subdata = $_POST['cw_custom'][$type][$tax];

		if (!isset($instance['cw_custom'])) {
			$instance['cw_custom'] = array();
		}
		if (!isset($instance['cw_custom'][$type])) {
			$instance['cw_custom'][$type] = array();
		}
		if (!isset($instance['cw_custom'][$type][$tax])) {
			$instance['cw_custom'][$type][$tax] = array();
		}

		$instance['cw_custom'][$type][$tax]['enable'] = isset($custom_subdata['enable']) ? 1:0;
		$instance['cw_custom'][$type][$tax]['all'] = isset($custom_subdata['all']) ? 1:0;
		$instance['cw_custom'][$type][$tax]['sub'] = isset($custom_subdata['sub']) ? 1:0;
		$instance['cw_custom'][$type][$tax]['select'] = $custom_subdata['select'];

		if (isset($custom_subdata['selected_ids'])) {
			$instance['cw_custom'][$type][$tax]['selected_ids'] = $custom_subdata['selected_ids'];
		} else {
			$instance['cw_custom'][$type][$tax]['selected_ids'] = '';
		}

	}

	//pages
	$instance['cw_pages_enable_checkbox'] = isset($_POST['cw_pages_enable_checkbox']) ? 1:0;
	$instance['cw_select_pages'] = $_POST['cw_select_pages'];
	$instance['cw_pages_sub_checkbox'] = isset($_POST['cw_pages_sub_checkbox']) ? 1:0;
	
	if (isset($_POST['cw_selected_pages'])) {
		$instance['cw_selected_pages'] = $_POST['cw_selected_pages'];
	} else {
		$instance['cw_selected_pages'] = '';
	}
	
	$instance['cw_pages_all'] = isset($_POST['cw_pages_all']) ? 1:0;
	
	// utility - since 1.0.4
	//404, search, archive
	$instance['cw_posts_page_hide'] = isset($_POST['cw_posts_page_hide_checkbox']) ? 1:0;
	$instance['cw_404_hide'] = isset($_POST['cw_404_hide_checkbox']) ? 1:0;
	$instance['cw_search_hide'] = isset($_POST['cw_search_hide_checkbox']) ? 1:0;
	$instance['cw_date_archive_hide'] = isset($_POST['cw_date_archive_hide_checkbox']) ? 1:0;
	$instance['cw_author_archive_hide'] = isset($_POST['cw_author_archive_hide_checkbox']) ? 1:0;
	$instance['cw_tag_archive_hide'] = isset($_POST['cw_tag_archive_hide_checkbox']) ? 1:0;
	
	return $instance;
	
}

function cets_conditional_widgets_instance_is_active( $instance ) {
	
	$type_tax_pairs = apply_filters('conditional_widgets_type_tax_pairs', array());


	if ($instance['cw_home_enable_checkbox'] ||  $instance['cw_pages_enable_checkbox'] || $instance['cw_404_hide'] || $instance['cw_search_hide'] || $instance['cw_author_archive_hide'] || $instance['cw_date_archive_hide'] || $instance['cw_tag_archive_hide'] || $instance['cw_posts_page_hide']) {
		return true;
	} 

	foreach($type_tax_pairs as $pair) {
		$tax = $pair['tax'];
		$type = $pair['type'];
		if (isset($instance['cw_custom'][$type][$tax]['enable']) && $instance['cw_custom'][$type][$tax]['enable'] == 1) {
			return true;
		}
	}

	return false;

}