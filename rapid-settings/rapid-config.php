<?php

/* ====================================================================
	FILE INFO:
	
	This is the core config file used by Rapid Platform to integrate 
	standard default options such as Delete Orphans and Restore 
	Defaults. You should integrate your custom options using 
	a separate rapid-config.php file in a parent/child theme's root 
	directory, which is detected and initialized automatically.
	
	USAGE:
	
	-Call the following function to integrate a new setting:
	
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
		-$page: name of the tabbed page this setting should belong to, 
		 alphanumeric
		-$option_title: the setting's name, shown as the field label
		-$option_tip: a short description of the setting
		-$field_name: a unique name for the setting field/id, 
		 recommended: $option_title converted to lowercase with 
		 spaces converted to underscores
		-$field_type: text, textarea, radio, checkbox, select, button
		-$default_value: a default value for the setting, used on first 
		 init
		-$data1: optional field data (button caption, etc)
		-$data2: optional field data
		-$data3: optional field data
		
	-Notes:
		-$field_name is tied to the setting key in the database so when 
		 this attribute changes it will force the creation of a new 
		 setting and the old one will be orphaned
		 
	TODO:
	
	Let the admin create new top level pages/menus using a delimited
	naming convention or the $page. e.g. Maintenance would be 
	Rapid Options\Maintenance.
*/

/* ====================================================================
	CORE SETTINGS:
	
	Here we add some settings for the core plugin.
*/

$this->set_dashboard_name("Rapid Settings");

$this->register_dashboard_fragment(
	"Maintenance",
	"Delete Orphaned Settings",
	"If executed, Rapid Platform will automatically delete old (orphaned) settings keys and values from the WordPress database that were previously created by the Rapid Platform but no longer detected in the rapid-config.php file(s).",
	"delete_orphaned_options",
	"button",
	"",
	"Delete Orphans",
	"delete_orphaned_options_action();"
);

$this->register_dashboard_fragment(
	"Maintenance",
	"Restore Default Values",
	"If executed, Rapid Platform will automatically restore all default setting values detected in the rapid-config.php file(s). Orphaned settings will not be affected.<br/><br/>WARNING: current values will be overwritten with default values!",
	"restore_default_option_values",
	"button",
	"",
	"Restore Defaults",
	"restore_default_option_values_action();"
);

$this->register_dashboard_fragment(
	"Maintenance",
	"Restore Default Values",
	"If executed, Rapid Platform will automatically restore all default setting values detected in the rapid-config.php file(s). Orphaned settings will not be affected.<br/><br/>WARNING: current values will be overwritten with default values!",
	"restore_default_option_values",
	"button",
	"",
	"Restore Defaults",
	"restore_default_option_values_action();"
);

/* ====================================================================
	CORE COMPONENT SETTINGS:
	
	Here we add some settings for various Rapid UI components.
*/

$this->register_dashboard_fragment(
	"Components",
	"System Fonts",
	"These System Fonts will become a font style choice in the theme Customization panel.<br/><br/>Instructions: System Fonts are traditional fonts that are already installed on most people's computers. System Fonts are delimited with <strong>|</strong>. Use the existing values (above) as a reference for how the string should be formatted.",
	"system_fonts",
	"textarea",
	"Arial|Book Antiqua|Comic Sans MS|Courier New|Georgia|Monotype Corsiva|Tahoma|Times New Romain|Verdana"
);

$this->register_dashboard_fragment(
	"Components",
	"Google Web Fonts",
	"These Google Web Fonts will become a font style choice in the theme Customization panel, as well as being available to your entire site for design purposes.<br/><br/>Instructions: When you choose one or more fonts at Google Web Fonts and then click Use, they give you three options for using the fonts on a website. The one we care about is @import. With their @import string in front of you, copy ONLY the font portion of the string and paste it here. If your fonts aren't showing up, make sure the string formatting symbols <strong>+ | : ,</strong> are intact and in their proper locations. Use the existing string (above) as a reference for how the string should be formatted.",
	"google_fonts",
	"textarea",
	"Lustria|Kristi|Niconne|Caesar+Dressing|PT+Sans+Narrow:400,700|Quicksand:300,400,700|Swanky+and+Moo+Moo|Allura|Limelight|Just+Me+Again+Down+Here|Coda:400,800|Alegreya+SC:400,400italic,700,700italic,900,900italic|Yanone+Kaffeesatz:400,200,300,700|Audiowide|Cuprum|Oswald:400,700,300|Source+Sans+Pro|Marcellus+SC|Anaheim|Oranienbaum|Carrois+Gothic|Gentium+Basic|Playfair+Display+SC|Oswald|Pontano+Sans"
);

?>