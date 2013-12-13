<?php

/* ====================================================================
	FILE INFO:
	
	This is the core config file used by Rapid Platform to integrate 
	standard default options such as Delete Orphans and Restore 
	Defaults. You should probably integrate your custom options using 
	a separate rapid-config.php file in the parent/child theme's root 
	folder, which will be detected and loaded automatically.
	
	USAGE:
	
	-Change $dashboard_name to rename the admin (dashboard) page/menus
	
	-Call the following function to integrate a new option:
	
		register_dashboard_fragment (
			$page, 
			$option_title, 
			$option_tip, 
			$field_name, 
			$field_type, 
			$default_value, 
			$data1, 
			$data2, 
			$data3
		)
		
	-Attribute description:
		-$page: name of the tabbed page this option should belong to, 
		 alphanumeric
		-$option_title: the option's name, shown as the field label
		-$option_tip: a short description of the option
		-$field_name: a unique name for the option field/id, 
		 recommended: $option_title converted to lowercase with 
		 spaces converted to underscores
		-$field_type: text, textarea, radio, checkbox, select, button
		-$default_value: a default value for the option, used on first 
		 init
		-$data1: optional field data (button caption, etc)
		-$data2: optional field data
		-$data3: optional field data
		
	-Notes:
		-$field_name is tied to the option key in the database so when 
		 this attribute changes it will force the creation of a new 
		 option and the old one will be orphaned
		 
	TODO:
	
	Let the admin create new top level pages/menus using a delimited
	naming conventionf or the $page. e.g. Maintenance would be 
	Rapid Options\Maintenance.
*/

$this->set_dashboard_name("Rapid Options");

$this->register_dashboard_fragment(
	"Maintenance",
	"Delete Orphaned Options",
	"If executed, Rapid Platform will automatically delete old (orphaned) option keys and values from the WordPress database that were previously created by the Rapid Platform but no longer detected in the rapid-config.php file(s).",
	"delete_orphaned_options",
	"button",
	"",
	"Delete Orphans",
	"delete_orphaned_options_action();"
);

$this->register_dashboard_fragment(
	"Maintenance",
	"Restore Default Option Values",
	"If executed, Rapid Platform will automatically restore all default option values detected in the rapid-config.php file(s). Orphaned options will not be affected.<br/><br/>WARNING: current values will be overwritten with default values!",
	"restore_default_option_values",
	"button",
	"",
	"Restore Defaults",
	"restore_default_option_values_action();"
);

$this->register_dashboard_fragment(
	"UI Settings",
	"Global design",
	"Any component/shortcode which doesn't specify a design attribute will inherit a design from this setting, in order of availability. For example, if you specify basic,dark in the field above, basic will be used as the default design for all components/shortcodes that haven't specified a design attribute, and if basic isn't an available design it will roll to the next one (dark).",
	"ui_global_design",
	"text",
	""
);

$this->register_dashboard_fragment(
	"UI Settings",
	"Global designs take priority",
	"Enabling this setting will ensure that the Global designs take priority over any user-specified design attributes.",
	"ui_global_design_priority",
	"checkbox",
	""
);

$this->register_dashboard_fragment(
	"UI Settings",
	"Frontend admin controls",
	"Enabling this setting will make special controls appear in the frontend next to heading elements for logged in admins.",
	"frontend_admin_controls",
	"checkbox",
	""
);

/*
$this->register_dashboard_fragment(
	"Test1",
	"Test1",
	"Test1",
	"test1",
	"text",
	""
);

$this->register_dashboard_fragment(
	"Test2",
	"Test2",
	"Test2",
	"test2",
	"text",
	""
);
*/
?>