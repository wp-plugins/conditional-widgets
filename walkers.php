<?php

/**
 * Walker class for processing the Category checklists
 */
class Conditional_Widget_Walker_Category_Checklist extends Walker {

	var $tree_type = 'category'; 
	var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

	var $type;
	var $tax;

	function __construct($type, $tax) {
		$this->type = $type;
		$this->tax = $tax;
	}

	function start_lvl(&$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}

	function end_lvl(&$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}
	
	function start_el(&$output, $category, $depth = 0, $args = array(), $current_object_id = 0) {
		extract($args);
		
	
		$name = "cw_custom[$this->type][$this->tax][selected_ids][]";
		

		$output .= "\n<li>" . '<label class="selectit"><input value="' . $category->term_id . '" type="checkbox" name="' . $name . '"' . checked( in_array( $category->term_id, $selected_cats ), true, false ) . disabled( empty( $args['disabled'] ), false, false ) . ' /> ' . esc_html( $category->name ) . '</label>';
	}

	function end_el(&$output, $category, $depth = 0, $args = array() ) {
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

	function start_lvl(&$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}
	
	function end_lvl(&$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}
	
	function start_el(&$output, $page, $depth = 0, $args = array(), $current_object_id = 0 ) {		
		$output .= "\n<li>";
		$output .= '<label class="selectit">';
		$output .=	"<input value='$page->ID' type='checkbox' name='cw_selected_pages[]' " . checked( in_array( $page->ID, $this->checked ), true, false ) .  " /> " . esc_html( $page->post_title ) . "</label>";
	}
	
	function end_el(&$output, $category, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}
}