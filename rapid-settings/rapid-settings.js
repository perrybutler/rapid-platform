/*==============================================
	FILE INFO:
	
	This file adds client-side functionality to 
	only the Rapid Options page in the WordPress
	backend.
	
	TIPS:
	
	This file uses a typical ajax pattern for a
	streamlined user experience. When the user
	executes a server action (e.g. clicks a 
	button to have the server process something)
	we follow this pattern:
	
	1. NOTIFY the user of the process being 
	   started.
	2. GATHER any data (form fields) supplied by 
	   the user.
	3. SUBMIT this data to the server for 
	   asynchronous processing.
	4. NOTIFY the user of the server response 
	   once the process has completed (using the
	   callback supplied by jQuery).
	   
	Furthermore, a comment prefixed with // === 
	marks a single phase in the pattern and 
	signifies when/where a pattern is being 
	used in the code (for fast gotos).
*/

jQuery(document).ready(function() {
	// ajaxify the options form submit process
	jQuery("#options-form").submit(function() {
		// === NOTIFY
		jQuery("#submit").attr("value", "Saving, please wait...");
		jQuery("#submit-message").fadeOut();
		// === GATHER
		// get a querystring representing the form data being submitted (ignores unchecked [null] checkboxes)
		var data = jQuery(this).serialize();
		// also get (pre-process) unchecked (null) checkboxes so their "false" value gets saved properly in the db
		var data_checkboxes = "";
		jQuery("#options-form input[type=checkbox]").each(function() {
			if (jQuery(this).prop("checked")) {
				data_checkboxes += "&" + this.name + "=1";
			}
			else {
				data_checkboxes += "&" + this.name + "=0";
			}
		});
		// append unchecked (null) checkboxes
		data += data_checkboxes;
		// set the ajax action (function) to be executed at the server
		data += "&action=save_options";
		// === SUBMIT
		// post the serialized (string) data and respond to the return value from the server
		jQuery.post(ajaxurl, data, function(response) {
			// === NOTIFY
			jQuery("#submit").attr("value", "Save Changes");
			jQuery("#submit-message").html(response);
			jQuery("#submit-message").fadeIn();
			renderAdminNotices();
		});
		return false;
	});
});

function delete_orphaned_options_action() {
	// === NOTIFY
	jQuery("input[name='delete_orphaned_options']").attr("value", "Deleting, please wait...");
	jQuery("#delete_orphaned_options_callback").fadeOut();
	// === GATHER
	var data = {action: "delete_orphaned_options"};
	// === SUBMIT
	jQuery.post(ajaxurl, data, function(response) {
		// === NOTIFY
		jQuery("input[name='delete_orphaned_options']").attr("value", "Delete Orphans");
		jQuery("#delete_orphaned_options_callback").fadeIn();
		jQuery("#delete_orphaned_options_callback").html(response);
		renderAdminNotices();
	});
	return false;
}

function restore_default_option_values_action() {
	// === NOTIFY
	jQuery("input[name='restore_default_option_values']").attr("value", "Restoring, please wait...");
	jQuery("#restore_default_option_values_callback").fadeOut();
	// === GATHER
	var data = {action: "restore_default_options"};
	// === SUBMIT
	jQuery.post(ajaxurl, data, function(response) {
		// === NOTIFY
		jQuery("input[name='restore_default_option_values']").attr("value", "Restore Defaults");
		jQuery("#restore_default_option_values_callback").fadeIn();
		jQuery("#restore_default_option_values_callback").html(response);
		renderAdminNotices();
	});
	return false;
}