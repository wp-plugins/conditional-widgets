<?php
/**
Plugin Name: Conditional Widgets
Plugin URI: http://wordpress.org/extend/plugins/conditional-widgets/
Description: Grants users advanced control over which pages and categories each widget is displayed on
Version: 1.5
Author: Jason Lemahieu and Kevin Graeme
Author URI: 
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Display the form at the bottom of each widget.
 */
function conditional_widgets_form($widget, $return, $instance) {
	
	// always show the form
	if ($return == 'noform') { $return = true; }
	
	//prefill variables
	$instance = conditional_widgets_init_instance($instance);
	
	//whether to display ON or OFF so users can easily see which widgets are conditional
	if ($instance['cw_home_enable_checkbox']||  $instance['cw_cats_enable_checkbox'] || $instance['cw_pages_enable_checkbox'] || $instance['cw_404_hide'] || $instance['cw_search_hide'] || $instance['cw_author_archive_hide'] || $instance['cw_date_archive_hide'] || $instance['cw_tag_archive_hide'] || $instance['cw_posts_page_hide']) {
		$active = true;
	} else {
		$active = false;
	}
	?>
		
	<div id="cets-conditional-widget">	
		<div class="conditional-widget-top">
        	<div class="conditional-widget-title-action">
            	<a href="#" id="conditional-widget-toggle-wrap-<?php print $widget->id ?>" onclick="conditional_widgets_form_toggle('conditional-widget-form-<?php print $widget->id; ?>'); return false;"></a>
            </div>
            
            <div class="conditional-widget-title">
                <h5>Widget Display Control
                <?php 
                if($active) {
					?>
					&nbsp;<span class="conditional-widgets-active">ON</span>
					<?php
                } else {
					?>
                    &nbsp; <span class="conditional-widgets-inactive">OFF</span>
					<?php }
				?>
                </h5>
            </div>
		</div>
		
		<div class="conditional-widget-form" id="conditional-widget-form-<?php print $widget->id; ?>">
			
			<p class='cw-instructions'>
			Select a combination of options to control on which sections of your site this widget is shown.
			</p>
			
			<p>
			<input type="checkbox" name="cw_home_enable_checkbox" <?php checked($instance['cw_home_enable_checkbox']); ?>> <?php conditional_widgets_form_show_hide_select('cw_select_home_page', $instance['cw_select_home_page'], true); ?> on Front Page
			</p>
			
			<h6 class="conditional-widget-header conditional-widget-sub-heading">Categories</h6>
			
			<p>
			<input type="checkbox" name="cw_cats_enable_checkbox" <?php checked($instance['cw_cats_enable_checkbox']); ?>> <?php conditional_widgets_form_show_hide_select('cw_select_cats', $instance['cw_select_cats'], false); ?> on Posts in Category:<br>
				<span class='cw_sub_checkbox'>
					<input type="checkbox" name="cw_cats_sub_checkbox" <?php checked($instance['cw_cats_sub_checkbox']); ?>> Include sub-categories automatically
				</span>
			</p>
			
			<div class='conditional-widgets-checkbox-wrapper'>
			<?php
			conditional_widgets_cat_checkboxes($instance['cw_selected_cats']);
			?>
			</div>
			
			<h6 class="conditional-widget-header conditional-widget-sub-heading">Pages</h6>
			
			<p>
			<input type="checkbox" name="cw_pages_enable_checkbox" <?php checked($instance['cw_pages_enable_checkbox']); ?>> <?php conditional_widgets_form_show_hide_select('cw_select_pages', $instance['cw_select_pages'], false); ?> on Pages:<br>
				<span class='cw_sub_checkbox'>
					<input type="checkbox" name="cw_pages_sub_checkbox" <?php checked($instance['cw_pages_sub_checkbox']); ?>> Include sub-pages automatically
				</span>
			</p>
			
			<div class='conditional-widgets-checkbox-wrapper'>
			<?php
			conditional_widgets_page_checkboxes($instance['cw_selected_pages']);
			?>
			</div>
		
			<h6 class="conditional-widget-header conditional-widget-sub-heading">Special Page Options</h6>
			
			<ul class=conditional-widgets-special-page-option-list'>
				<!-- posts page -->
				<li>
					<input type="checkbox" name="cw_posts_page_hide_checkbox" <?php checked($instance['cw_posts_page_hide']); ?>>	Hide on Posts Page (when using a static front page)
				</li>
				
				<!-- 404 -->
				<li>
					<input type="checkbox" name="cw_404_hide_checkbox" <?php checked($instance['cw_404_hide']); ?>>	Hide on 404s (Page Not Found)
				</li>
				
				<!-- search results -->
				<li>
					<input type="checkbox" name="cw_search_hide_checkbox" <?php checked($instance['cw_search_hide']); ?>>	Hide when displaying Search Reults
				</li>
			
				<!-- archives -->
				<li>
					<input type="checkbox" name="cw_author_archive_hide_checkbox" <?php checked($instance['cw_author_archive_hide']); ?>>	Hide on Author Archives
				</li>
				<li>
					<input type="checkbox" name="cw_date_archive_hide_checkbox" <?php checked($instance['cw_date_archive_hide']); ?>>	Hide on Date Archives
				</li>
				<li>
					<input type="checkbox" name="cw_tag_archive_hide_checkbox" <?php checked($instance['cw_tag_archive_hide']); ?>>	Hide on Tag Archives
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
	
	//cats
	$instance['cw_cats_enable_checkbox'] = isset($_POST['cw_cats_enable_checkbox']) ? 1:0;
	$instance['cw_select_cats'] = $_POST['cw_select_cats'];
	$instance['cw_cats_sub_checkbox'] = isset($_POST['cw_cats_sub_checkbox']) ? 1:0;
	$instance['cw_selected_cats'] = $_POST['cw_selected_cats'];
	
	//pages
	$instance['cw_pages_enable_checkbox'] = isset($_POST['cw_pages_enable_checkbox']) ? 1:0;
	$instance['cw_select_pages'] = $_POST['cw_select_pages'];
	$instance['cw_pages_sub_checkbox'] = isset($_POST['cw_pages_sub_checkbox']) ? 1:0;
	$instance['cw_selected_pages'] = $_POST['cw_selected_pages'];
	
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

/**
 * Determine whether or not this widget should be displayed on this page request
 */
function conditional_widgets_widget($instance) {
	
	/* variables we have access to
	$instance['cw_home_enable_checkbox']
	$instance['cw_select_home_page']  //show == 1, hide == 0, show only on home == 2
	
	//pages
	$instance['cw_pages_enable_checkbox']
	$instance['cw_select_pages']
	$instance['cw_pages_sub_checkbox']
	$instance['cw_selected_pages']
	
	//cats
	$instance['cw_cats_enable_checkbox']
	$instance['cw_select_cats']
	$instance['cw_cats_sub_checkbox']
	$instance['cw_selected_cats']
	
	// utility
	$instance['cw_posts_page_hide']
	$instance['cw_404_hide']
	$instance['cw_search_hide']
	$instance['cw_date_archive_hide']
	$instance['cw_author_archive_hide']
	$instance['cw_tag_archive_hide']
	
	*/
	
	if (WP_DEBUG) {
		$debug = true;
	} else {
		$debug = false;
	}
	global $wp_query;
	$qvars = $wp_query->query_vars;

	$instance = conditional_widgets_init_instance($instance);
	
	if ($instance['cw_home_enable_checkbox']) {
		//box checked for home page logic takes priority by processing first
		switch ($instance['cw_select_home_page']) {
			case 0: //hide if front page
				if (is_front_page()) {
					if ($debug) {echo "[Widget hidden because front page.]<br/><br/>"; }
					return false;
				}
				break;
			case 1: //show if front page
				if (is_front_page()) {
					if ($debug) {echo "Showing because front page:<br/>"; }
					return $instance;
				}
				break;
			case 2: //only show on front, hide otherwise
				if (is_front_page()) {
					if ($debug) {echo "Showing because front page:<br/>"; }
					return $instance;
				} else {
					if ($debug) {echo "[ Widget hidden because not front page]<br/><br/>"; }
					return false;
				}
				break;
		}
	}
	
	
	$arr_pages = $instance['cw_selected_pages'];
		
	if ($instance['cw_pages_enable_checkbox'] && is_page()) {
		//box checked for caring about what pages
		$current_page_id = $wp_query->post->ID;
		
		//see if we care about subpages
		if ($instance['cw_pages_sub_checkbox'] == 1) {
			
			foreach($arr_pages as $page) {
				$args = 'child_of=' . $page;
				$newpages = get_pages($args);
				foreach ($newpages as $newpage) {
					array_push($arr_pages, $newpage->ID);
				}
			}
		} //if subpages count
		
		$match = false;
		if (in_array($current_page_id, $arr_pages)) {
			$match = true;
		}
			
		if ($match) {
			//we matched a page
			if ($instance['cw_select_pages'] == 1) {
				//and we're showing on matched pages - SHOW
				if ($debug) {echo "Showing because we're set to show on this page:<br/>"; }
				return $instance;
			} else {
				//and we're hiding on matched pages - so HIDE
				if ($debug) {echo "[Widget hidden because this page wasnt chosen to have this widget displayed.]<br/><br/>"; }
				return false;
			}
		} else {
			//we did NOT match a page
			if ($instance['cw_select_pages'] == 1) {
				//and we're telling it to show on matched pages - so HIDE
				if ($debug) {echo "[Widget hidden because this is a page you told me to hid it on.]<br/><br/>"; }
				return false;
			} else {
				//we didn't match a page, and we told it to hide on those pages - so SHOW
				if ($debug) {echo "Showing because front page:<br/>"; }
				return $instance;
			}
		}
	} //is_page && we care
	
	//see if we care about categories...   (2/28/2012: AND if this page is related to categories...)
	$match = false;
	if ($instance['cw_cats_enable_checkbox'] && (is_single() || is_category())) {
		//starting point. haven't matched yet - checked categories.
		
		$arr_cats = $instance['cw_selected_cats'];

		//expand arr_cats to include subcats
		if ($instance['cw_cats_sub_checkbox'] == 1) {
			
			foreach($instance['cw_selected_cats'] as $cat) {
				$args = 'child_of=' . $cat;
				$newcats = get_categories($args);
			
				foreach ($newcats as $newcat) {
					array_push($arr_cats, $newcat->term_id);
				}	
			}	
		} //if including subcats
		
		$current_post_id = $wp_query->post->ID;
			
		//if this is a single_post
		if (is_single()) {
			//get the categories of the post
			$postcats = get_the_category($qvars['p']);
			
			foreach ($postcats as $cat) {
				if (in_array($cat->term_id, $arr_cats)) {
					$match = true;
					continue;
				}
			}
		}
		
		//if this is a categories archive page
		if (is_category()) {
		
			$cat = $qvars['cat'];
			if (in_array($cat, $arr_cats)) {
				$match = true;
			}
		}
		
		// Logic Processing now that we've determined whether or now we're a match
		if ($match) {
					
			//we matched a cat
			if ($instance['cw_select_cats'] == 1) {
				//and we're showing on matched cats
				if ($debug) {echo "Showing because I was told to show this on this category:<br/>"; }
				return $instance;
			} else {
				//and we're hiding on matched cats
				if ($debug) {echo "[Widget hidden because I was told to show on certain categories - and this isn't one of em.]<br/><br/>"; }
				return false;
			}
		} else {
			//we did NOT match a cat
			if ($instance['cw_select_cats'] == 1) {
				//and we're telling it to show on matched cats - so HIDE
				if ($debug) {echo "[Widget hidden because this category doesn't match ones we were told to show on.]<br/><br/>"; }
				return false;
			} else {
				//we didn't match a cat, and we told it to hide on those cats - so SHOW
				if ($debug) {echo "Showing because this isn't one of the categories we were told to hide on:<br/>"; }
				return $instance;
			}
		}
		
	} //if we care about categories
		
	//since 1.4
	if (is_home()) {
		if ($instance['cw_posts_page_hide'] == 1) {
			if ($debug) {echo "[Widget hidden because this is the home page.]<br/><br/>"; }
			return false;
		}
	}
	
	// since 1.1
	if (is_404()) {
		if ($instance['cw_404_hide'] == 1) {
			if ($debug) {echo "[Widget hidden because this is the 404 page.]<br/><br/>"; }
			return false;
		}
	}  //if 404
		
	// since 1.1
	if (is_search()) {		
		if ($instance['cw_search_hide'] == 1) {
			if ($debug) {echo "[Widget hidden because this is the search page.]<br/><br/>"; }
			return false;
		}
	} // if search
	
	// since 1.1
	if (is_author()) {
		if ($instance['cw_author_archive_hide'] == 1) {
			if ($debug) {echo "[Widget hidden because this is an author archive page.]<br/><br/>"; }
			return false;
		}
	}
	
	// since 1.1
	if (is_date()) {
		if ($instance['cw_date_archive_hide'] == 1) {
			if ($debug) {echo "[Widget hidden because this is a date archive page.]<br/><br/>"; }
			return false;
		}
	}
	
	//since 1.2
	if (is_tag()) {
		if ($instance['cw_tag_archive_hide'] == 1) {
			if ($debug) {echo "[Widget hidden because this is a tag archive.]<br/><br/>"; }
			return false;
		}

	}
	
	//default to showing
	return $instance;
	
}

// register widget callbacks and update functions
add_filter('widget_display_callback', 'conditional_widgets_widget');
add_action('in_widget_form', 'conditional_widgets_form', 10, 3);
add_filter('widget_update_callback', 'conditional_widgets_update', 10, 2);


/**
 * Helper function for displaying the list of checkboxes for Pages
 */
function conditional_widgets_page_checkboxes($selected=array()) {
	echo "<ul class='conditional-widget-selection-list'>";
	wp_list_pages( array( 'title_li' => null, 'walker' => new Conditional_Widgets_Walker_Page_Checklist($selected) ) );
	echo "</ul>";
}

/**
 * Helper function for displaying the list of checkboxes for Categories
 */
function conditional_widgets_cat_checkboxes($selected = array()) {
	
	echo "<ul class='conditional-widget-selection-list'>";
	wp_category_checklist(0, 0, $selected, false, new Conditional_Widget_Walker_Category_Checklist, false);
	echo "</ul>";

}

/**
 * Walker class for processing the Category checklists
 */
class Conditional_Widget_Walker_Category_Checklist extends Walker {

	var $tree_type = 'category';
	var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

	function start_lvl(&$output, $depth, $args) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}

	function end_lvl(&$output, $depth, $args) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	function start_el(&$output, $category, $depth, $args) {
		extract($args);
		$name = 'cw-categories';

		$output .= "\n<li>" . '<label class="selectit"><input value="' . $category->term_id . '" type="checkbox" name="cw_selected_cats[]"' . checked( in_array( $category->term_id, $selected_cats ), true, false ) . disabled( empty( $args['disabled'] ), false, false ) . ' /> ' . esc_html( apply_filters('the_category', $category->name )) . '</label>';
	}

	function end_el(&$output, $category, $depth, $args) {
		$output .= "</li>\n";
	}
}

/**
 * Walker class for processing the Page checklists
 */
class Conditional_Widgets_Walker_Page_Checklist extends Walker {

	function __construct($selected = array()) {
		//$this->checked should be an array
		$this->checked = $selected;
	}

	var $tree_type = 'page';
	var $db_fields = array ('parent' => 'post_parent', 'id' => 'ID');

	function start_lvl(&$output, $depth, $args) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}
	
	function end_lvl(&$output, $depth, $args) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}
	
	function start_el(&$output, $page, $depth, $args) {		
		$output .= "\n<li>";
		$output .= '<label class="selectit">';
		$output .=	"<input value='$page->ID' type='checkbox' name='cw_selected_pages[]' " . checked( in_array( $page->ID, $this->checked ), true, false ) .  " /> " . esc_html( $page->post_title ) . "</label>";
	}
	
	function end_el(&$output, $category, $depth, $args) {
		$output .= "</li>\n";
	}
}

/**
 * Helper function for outputting the select boxes in the widget's form
 */
function conditional_widgets_form_show_hide_select($name, $value='', $only=false) {
	echo "<select name=$name>";
	echo "<option value='1' ";
	if ($value == 1) {echo "selected='selected'";}
	echo ">Show </option>";
	
	if ($only) {
		echo "<option value='2' ";
		if ($value == 2) {echo "selected='selected'";}
		echo "> Show only</option>";
	}
	
	echo "<option value='0' ";
	if ($value == 0) {echo "selected='selected'";}
	echo ">Hide </option>";
	echo "</select>";
}	

/**
 * Display CSS in admin head
 */
function conditional_widgets_css_admin() {
	
	// CSS and Javascript for HTML HEAD
	?>
	<!-- Conditional Widgets Admin CSS -->
	<link rel="stylesheet" href="<?php echo plugins_url('css/conditional-widgets-admin.css',__FILE__)?>" type="text/css" />

    <?php 
}

/**
 * Enqueue javascript for Widget form
 */
function conditional_widgets_admin_scripts() {
	wp_enqueue_script("jquery");
	wp_enqueue_script("conditional_widgets_admin_scripts", plugins_url('js/conditional-widgets-admin.js',__FILE__), 'jquery');
}

//only embed these on the widget page
if (strpos($_SERVER['REQUEST_URI'], 'widgets.php')) {
	add_action('admin_head', 'conditional_widgets_css_admin');
	add_action('admin_print_scripts', 'conditional_widgets_admin_scripts');
}
  
/**
 * Initializes a fresh widget instance
 */
function conditional_widgets_init_instance($instance) {
	//single values
	$keys = array('cw_home_enable_checkbox',
					'cw_select_home_page',
					'cw_pages_enable_checkbox',
					'cw_select_pages',
					'cw_pages_sub_checkbox',
					'cw_cats_enable_checkbox',
					'cw_select_cats',
					'cw_cats_sub_checkbox',
					'cw_404_hide',
					'cw_search_hide',
					'cw_author_archive_hide',
					'cw_date_archive_hide',
					'cw_tag_archive_hide',
					'cw_posts_page_hide',
					);
	foreach ($keys as $key) {
		if (!isset($instance[$key])) {
			$instance[$key] = '';
		}
	}
	
	//arrays
	$arraykeys = array('cw_selected_pages',
						'cw_selected_cats');
	foreach ($arraykeys as $arraykey) {
		if (!isset($instance[$arraykey])) {
			$instance[$arraykey] = array();
		}
	}
	return $instance;
}
