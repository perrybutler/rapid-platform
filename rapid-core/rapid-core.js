/*==============================================
	FILE INFO:
	
	This file adds client-side functionality to 
	the entire WordPress backend (not just the 
	Rapid Options page).
*/

jQuery(document).ready(function() {
	// when the core loads (in the backend) we render any admin notices
	renderAdminNotices();
});

function renderAdminNotices() {
	// pull the admin_notices from the server via ajax and display them in the backend	
	var data = {action: "get_admin_notices"};
	jQuery("#admin_notices").hide();
	jQuery.post(ajaxurl, data, function(response) {
		jQuery("#admin_notices").show();
		jQuery("#admin_notices").html('');
		jQuery("h2").after("<div id='admin_notices'></div>");
		jQuery("#admin_notices").append(response);
	});

}