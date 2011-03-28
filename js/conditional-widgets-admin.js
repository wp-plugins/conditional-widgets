 /* javascript */
/*
 function toggle_custom_tab_options(optionName) {
	var useOptions = jQuery("input[@name=optionName]:checked").val();
	if (useOptions == 1) {
		jQuery(".custom_tab_check").removeAttr("disabled");
		jQuery(".ces_custom_tab_page_name").css({"color" : 'black'});

	} else {
		jQuery(".custom_tab_check").attr("disabled", "disabled");
		jQuery(".ces_custom_tab_page_name").css({"color" : '#CCC'});

	}
}
function livenCheckboxes() {
	jQuery(".custom_tab_check").removeAttr("disabled");
}
*/
/*
jQuery(document).ready(function () {
  //pre disable form if needed
  toggle_custom_tab_options(jQuery("input[@class=ces_custom_theme_page_option]:checked").attr("name"));
});
*/
function conditional_widgets_form_toggle(divID) {
	jQuery("#" + divID).slideToggle("slow");
}