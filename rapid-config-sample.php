<?php

/* ====================================================================
	FILE INFO:
	
	This file enables the developer to define one or more options that 
	will be automatically integrated into the WordPress backend.
	
	USAGE:
	
	Call the following function to integrate a new option:
		$this->register_dashboard_fragment
		(
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
		
	Attribute description:
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
		
	Notes:
		-$field_name is tied to the option key in the database so when 
		 this attribute changes it will force the creation of a new 
		 option and the old one will be orphaned
*/

/* ====================================================================
	EXAMPLE SETTINGS:
	
	These settings are for demonstrating the Rapid Settings. Feel free 
	to CHANGE or DELETE these.
*/

$this->register_dashboard_fragment(
	"Samples",
	"Checkbox Example",
	"Demonstrates a 'checkbox' form field + option from the rapid-config.php file.",
	"checkbox_example",
	"checkbox",
	"true"
);

$this->register_dashboard_fragment(
	"Samples",
	"Radio Example",
	"Demonstrates a 'radio' form field + option from the rapid-config.php file.",
	"radio_example",
	"radio",
	"peach",
	"apple,orange,peach,banana"
);

$this->register_dashboard_fragment(
	"Samples",
	"Select Example",
	"Demonstrates a 'select' form field + option from the rapid-config.php file.",
	"select_example",
	"select",
	"peach",
	"apple,orange,peach,banana"
);

$this->register_dashboard_fragment(
	"Samples",
	"Text Example",
	"Demonstrates a 'text' form field + option from the rapid-config.php file.",
	"text_example",
	"text",
	"Custom text goes here..."
);

$this->register_dashboard_fragment(
	"Samples",
	"Textarea Example",
	"Demonstrates a 'textarea' form field + option from the rapid-config.php file.",
	"textarea_example",
	"textarea",
	"Custom multi-line text goes here..."
);

$this->register_dashboard_fragment(
	"Samples",
	"Button Example",
	"Demonstrates a 'button' form field + option from the rapid-config.php file.",
	"button_example",
	"button",
	"",
	"Execute a Javascript function!",
	"alert('Welcome traveler!')"
);

?>