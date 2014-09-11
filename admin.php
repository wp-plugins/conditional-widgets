<?php

require_once( dirname(__FILE__) . '/update.php');
require_once( dirname(__FILE__) . '/walkers.php');
require_once( dirname(__FILE__) . '/form.php');


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
 * Helper function for displaying the list of checkboxes for Pages
 */
function conditional_widgets_page_checkboxes($selected=array()) {
	echo "<ul class='conditional-widget-selection-list'>";
	wp_list_pages( array( 'title_li' => null, 'walker' => new Conditional_Widgets_Walker_Page_Checklist($selected) ) );
	echo "</ul>";
}


function conditional_widgets_term_checkboxes($tax, $type, $selected = array()) {
	echo "<ul class='conditional-widget-selection-list'>";
	$args = array(
			'selected_cats' => $selected,
			'checked_ontop' => false,
			'taxonomy' => $tax,
			'walker' => new Conditional_Widget_Walker_Category_Checklist($type, $tax),

		);
	wp_terms_checklist(0, $args);
	echo "</ul>";
}