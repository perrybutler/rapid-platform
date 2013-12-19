<?php

/* ====================================================================
	FILE INFO:
	
	This file handles the entire admin (backend) 
	for the theme. It works by stepping through 
	an array of custom dashboard fragments that 
	have been defined by the developer in one or 
	more rapid-config.php files. By combining 
	these fragments we end up with a fully 
	functional admin (backend) page.
*/

class RapidSettings {

	public $default_rapid_options;
	public $stored_rapid_options;
	public $dashboard_fragments;
	public $dashboard_name;
	public $admin_notices;
	
	function __construct() {
		
		// load the user-defined settings from the rapid-config.php file(s).
		$config_name = "rapid-config.php";
		$core_config = dirname(__FILE__) . "/" . $config_name;
		$sample_config = dirname(__FILE__) . "/../" . $config_name;
		$parent_theme_config = get_template_directory() . '/' . $config_name;
		$child_theme_config = get_stylesheet_directory() . '/' . $config_name;
		if (file_exists($child_theme_config)) {
			// load config in child theme folder
			include_once($child_theme_config);
		}
		if (file_exists($parent_theme_config)) {
			// load config in parent theme folder
			include_once($parent_theme_config);
		}
		if (file_exists($sample_config)) {
			// load sample config (included with Rapid Platform, not user-defined)
			include_once($sample_config);
		}
		// load core config (included with Rapid Platform, not user-defined)
		include_once($core_config);

		// start the $_SESSION to persist admin_notices through ajax calls
		session_start();
		
		// load the (current) rapid options
		$this->stored_rapid_options = get_option("rapid_options");
		
		// handle missing and orphaned options
		$this->handle_missing_options();
		$this->handle_orphaned_options();
		
		// register action hooks
		add_action( 'admin_menu', array($this, 'admin_page_init') ); // adds a submenu ("Rapid Settings") to the admin panel's menu structure
		add_action( 'admin_bar_menu', array($this, 'admin_bar_init'), 999 );
		add_action( 'wp_enqueue_scripts', array($this, 'global_inline_scripts') );
		add_action( 'wp_ajax_save_options', array($this, 'save_options') );
		add_action( 'wp_ajax_delete_orphaned_options', array($this, 'delete_orphaned_options') );
		add_action( 'wp_ajax_restore_default_options', array($this, 'restore_default_options') );
		add_action( 'wp_ajax_get_admin_notices', array($this, 'get_admin_notices') );
		add_action( 'wp_ajax_update_admin_notice', array($this, 'update_admin_notice') );
		
	}

	/* ====================================================================
		ADMIN PAGE/MENU:
	*/
	
	public function register_dashboard_fragment($page, $option_title, $option_tip, $field_name, $field_type, $default_value, $data1 = "", $data2 = "", $data3 = "") {
		$this->default_rapid_options[$field_name] = $default_value;
		$this->dashboard_fragments[$page][$option_title] = array("title" => $option_title, "tip" => $option_tip, "field_name" => $field_name, "field_type" => $field_type, "data1" => $data1, "data2" => $data2, "data3" => $data3);
	}
	
	public function set_dashboard_name($name) {
		$this->dashboard_name = esc_attr($name);
	}
	
	function admin_bar_init() {
		global $wp_admin_bar;
		$wp_admin_bar->add_node( array(
			'parent' => 'appearance',
			'id' => 'rapid_admin_menu',
			'title' => $this->dashboard_name,
			'href' => admin_url( 'admin.php?page=rapid-admin')
		) );
	}
	
	function admin_page_init() {
	
		// register the style and script file used by the admin (dashboard) page
		wp_register_style( 'rapid_dashboard_style', plugins_url( 'rapid-settings.css' , __FILE__ ) );
		wp_register_script( 'rapid_dashboard_script', plugins_url( 'rapid-settings.js' , __FILE__ ) );
		
		// add a new page to the WordPress admin menu and hook the script/style
		$page = add_menu_page( $this->dashboard_name, $this->dashboard_name, 'manage_options', 'rapid-admin', array($this, 'admin_page_content') );
		add_action( 'admin_print_styles-' . $page, array($this, 'admin_styles') );
		add_action( 'admin_print_scripts-' . $page, array($this, 'admin_scripts') );
		
	}
	
	function admin_scripts() {
		wp_enqueue_script( 'rapid_dashboard_script' );
	}
	
	function admin_styles() {
		wp_enqueue_style( 'rapid_dashboard_style' );
	}
	
	function global_inline_scripts() {
		// acquire & sanitize the stored option
		$google_fonts = esc_attr($this->stored_rapid_options['google_fonts']);
		$html = '';
		$html .= '<style type="text/css">';
		$html .= '@import url(http://fonts.googleapis.com/css?family=' . $google_fonts . ');';
		$html .= '</style>';
		echo $html;
	}
	
	public function admin_page_content() {
	
		// abort if user has no permission
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
	
		// start output of the page's div container (wrap) / heading / intro text
		echo "<div class='wrap'>";
		echo "<div id='icon-options-general' class='icon32'><br></div>";
		echo "<h2>" . esc_attr($this->dashboard_name) . "</h2>";
		echo "<p>Modify custom option values for the site/theme here.</p>";
		
		// start output of the paginator controls
		echo "<div class='rapid-dashboard rp-paginator'>";
		echo '<div class="rp-paginator-controls"></div>';
		echo "<form id='options-form' action='save_options' method='post'>";
		
		// iterate through each fragment and output it to build the full dashboard (options page)
		if ($this->dashboard_fragments != "") {
		foreach ($this->dashboard_fragments as $page => $fragments) {
			$fragment_html = "";
			$fragment_html .= '<div class="section">';
				$fragment_html .= '<h3>' . $page . '</h3>';
				foreach ($fragments as $fragment) {
					$field_name = esc_attr($fragment['field_name']); // $field_name used in html output - sanitize it
					$field_type = $fragment['field_type']; // $field_type used in php logic - no need to sanitize it
					$fragment_title = esc_attr($fragment["title"]); // $fragment_title used in html output - sanitize it
					$val = $this->stored_rapid_options[$field_name]; // $val must be sanitized later (if used in html output) in the conditional
					$tip = esc_attr($fragment["tip"]); // $tip used in html output - sanitize it
					$data1 = esc_attr($fragment["data1"]); // $data1 usually used in html output - sanitize it
					$data2 = esc_attr($fragment["data2"]); // $data2 usually used in html output - sanitize it
					// start the subsection html
					$fragment_html .= "<div class='subsection'>";
					$fragment_html .= '<div class="subsection-label">';
					$fragment_html .= $fragment_title;
					$fragment_html .= '</div>';
					$fragment_html .= '<div class="subsection-content">';
					// output some html (based on the field_type) to fully integrate this fragment into the page as a configurable setting
					if ($field_type == "text") {
						$val = esc_attr($val); // $val used in html output - sanitize it
						$fragment_html .= '<input name="' . $field_name . '" type="text" value="' . $val . '"></input><br/>';
					}
					elseif ($field_type == "textarea") {
						$val = esc_textarea($val); // $val used in html output - sanitize it
						$fragment_html .= '<textarea name="' . $field_name . '">' . $val . '</textarea><br/>';
					}
					elseif ($field_type == "select") {
						$select_options = explode(",", $data1);
						$fragment_html .= '<select name=' . $field_name . '>';
						foreach ($select_options as $select_option) {
							if ($select_option == $val) {
								$fragment_html .= "<option value='" . $select_option . "' selected>" . $select_option . "</option>";
							}
							else {
								$fragment_html .= "<option value='" . $select_option . "'>" . $select_option . "</option>";
							}
						}
						$fragment_html .= '</select><br/>';
					}
					elseif ($field_type == "radio") {
						$select_options = explode(",", $data1);
						foreach ($select_options as $select_option) {
							if ($select_option == $val) {
								$fragment_html .= '<input type="radio" name="' . $field_name . '" value="' . $select_option . '" checked></input>' . $select_option . '<br/>';
							}
							else {
								$fragment_html .= '<input type="radio" name="' . $field_name . '" value="' . $select_option . '"></input>' . $select_option . '<br/>';
							}
						}
					}
					elseif ($field_type == "checkbox") {
						if ($val == "checked" || $val == "true" || $val == "yes" || $val == true || $val == 1) {
							$val = "checked";
						}
						else {
							$val = "";
						}
						$fragment_html .= '<input type="checkbox" name="' . $field_name . '" ' . $val . '></input><br/>';
					}
					elseif ($field_type == "button") {
						$fragment_html .= '<input class="button" type="button" name="' . $field_name . '" value="' . $data1 . '" onclick="' . $data2 . ';return false;"></input>';
						$fragment_html .= '<span class="button-callback" id=' . $field_name .'_callback></span>';
					}
					$fragment_html .= '<div class="rapid-dashboard-tip">' . $tip . '</div>';
					$fragment_html .= '</div>';	
					$fragment_html .= "</div>";				
				}
			$fragment_html .= '</div>';		
			echo $fragment_html;		
		}
		}
		
		// end output of the paginator controls
		echo "<div id='submit-container'>";
		echo "<input type='submit' id='submit' name='submit' class='button button-primary' value='Save Changes'></input>";
		echo "<span id='submit-message'></span>";
		echo "</div>";
		echo "</form>";
		
		// end output of the page's div container (wrap)
		echo "</div>";
	}


	/* ====================================================================
		ADMIN NOTICE FUNCTIONS:
		
		TODO: Warning: Invalid argument supplied for foreach()
	*/
	
	public function get_admin_notices() {
		$html = "";
		if ( isset($_SESSION['admin_notices']) ) {
			foreach($_SESSION['admin_notices'] as $admin_notice) {
				$html .= $admin_notice;
			}
			// all single-view notices should now be unset (two-stage logic)
			unset($_SESSION['admin_notices']['deferred_rapid_data_installed']);
			// send response to client callback
			echo $html;
		}
		die();
	}

	public function update_admin_notice($key, $val) {
		// build and set/unset the raw html for this admin notice
		if ($val != null) {
			$html = "";
			$html .= "<div class='updated'>";
			$html .= "<p>" . $val . "</p>";
			$html .= "</div>";
			$_SESSION['admin_notices'][$key] = $html;
		}
		else {
			unset($_SESSION['admin_notices'][$key]);
		}
		// TODO: die() here causes delete_orphaned_options() to return empty
		// because it is not being called from an ajax context since ajax has
		// already been established via delete_orphaned_options()
		//die();
	}


	/* ====================================================================
		HANDLE MISSING OPTIONS:
	*/
	
	public function handle_missing_options() {
		// insert missing options
		$rows_affected = 0;
		foreach ($this->default_rapid_options as $option_key => $option_val) {
			// check if stored_rapid_options contains this default option
			if ( array_key_exists($option_key, $this->stored_rapid_options) == false ) {
				// stored_rapid_options does NOT contain this default option, ADD it
				$this->stored_rapid_options[$option_key] = $option_val;
				$rows_affected += 1;
			}
		}
		// save the options
		update_option("rapid_options", $this->stored_rapid_options);
		// notify the user
		if ($rows_affected > 0) {
			$this->update_admin_notice('deferred_rapid_data_installed', "Rapid Platform <span style='color:#00dd00; font-weight:bold;'>initialized " . $rows_affected . " settings</span> found in the rapid-config.php file(s). Consider viewing or changing these settings via the <a href='admin.php?page=rapid-admin'>Rapid Settings</a> menu.");
		}
	}

	/* ====================================================================
		HANDLE ORPHANED OPTIONS:
	*/
	
	public function handle_orphaned_options() {
		// detect orphaned options
		$orphaned_rapid_options = array_diff_key($this->stored_rapid_options, $this->default_rapid_options);
		if (count($orphaned_rapid_options) > 0) {
			$this->update_admin_notice('deferred_rapid_data_orphans', "Rapid Platform <span style='color:#ff9900; font-weight:bold;'>detected " . count($orphaned_rapid_options) . " settings</span> in the database which are no longer defined in the rapid-config.php file(s). Consider viewing or deleting these orphaned settings via the Maintenance tab in the <a href='admin.php?page=rapid-admin'>Rapid Settings</a> menu.");
		}
	}

	/* ====================================================================
		DELETE ORPHANED OPTIONS:
	*/
	
	public function delete_orphaned_options() {
		// (re)get the stored rapid options array
		$this->stored_rapid_options = get_option("rapid_options");
		if (is_array($this->stored_rapid_options) == false) {
			$this->stored_rapid_options = array();
		}
		// detect orphaned options
		$orphaned_rapid_options = array_diff_key($this->stored_rapid_options, $this->default_rapid_options);
		// delete orphaned options
		foreach ($orphaned_rapid_options as $option_key => $option_val) {
			unset($this->stored_rapid_options[$option_key]);
		}
		update_option("rapid_options", $this->stored_rapid_options);
		// clear the global admin notice
		$this->update_admin_notice('deferred_rapid_data_orphans', null);
		// notify user
		echo "<span style='color:#009933;'><i class='icon-check'></i></span>Orphans were deleted successfully! " . count($orphaned_rapid_options) . " options were affected.";
		die();
	}

	/* ====================================================================
		RESTORE DEFAULT OPTIONS:
	*/
	
	public function restore_default_options() {
		// compare the two
		$changed_rapid_options = array_diff($this->default_rapid_options, $this->stored_rapid_options);
		// restore defaults
		$result = update_option("rapid_options", $this->default_rapid_options);
		// return something to the client to indicate success or failure; an admin_notice isn't enough because there might not be any...
		if ($result == true) {
			// success
			echo "<span style='color:#009933;'><i class='icon-check'></i></span>Default values were restored! " . count($changed_rapid_options) . " options were affected. Please refresh the page...";
		}
		else {
			if (count($changed_rapid_options) > 0) {
				// could not save options
				echo "<span style='color:#ff0000;'><i class='icon-attention'></i></span>Default values could not be restored." . count($changed_rapid_options) . " options were not affected."; ;
			}
			else {
				// there were no changes to be saved
				echo "<span style='color:#eecc00;'><i class='icon-attention'></i></span>Default values are already in effect.";
			}
		}
		die();
	}
	
	public function save_options() {
		// acquire the (current) stored options and clone it for comparison later
		$stored_rapid_options = get_option("rapid_options");
		$new_rapid_options = array_merge(array(), $stored_rapid_options); // clone the stored rapid options and make changes to this array instead so we can compare new with old later
		// proceed if there are default options to work with
		$result = "NULL";
		if (is_array($this->default_rapid_options)) {
			// iterate through the default rapid option keys and:
			//	-extract the new (posted) matching field value
			//	-store it in an array for saving to the database later
			foreach ($this->default_rapid_options as $option_key => $option_val) {
				if (isset($_POST[$option_key]) == true) {
					// get the POSTED option value
					$option_val_new = $_POST[$option_key];
					// store the POSTED option value
					$new_rapid_options[$option_key] = $option_val_new;
				}
				else {
					// kill the POSTED option value
					$new_rapid_options[$option_key] = null;
				}
			}
			// compare the options
			$changed_rapid_options = array_diff($new_rapid_options, $stored_rapid_options);
			// save the options
			$result = update_option("rapid_options", $new_rapid_options);
		}
		// detect any orphaned options and create a global admin notice
		$this->handle_orphaned_options();
		// return something to the client to indicate success or failure; an admin_notice isn't enough because there might not be any...
		if ($result == true) {
			// SUCCESS
			echo "<span style='color:#009933;'><i class='icon-check'></i></span>Changes were saved successfully! " . count($changed_rapid_options) . " options were updated.";
		}
		else {
			if (count($changed_rapid_options) > 0) {
				// FAILED
				echo "<span style='color:#ff0000;'><i class='icon-attention'></i></span>Changes could not be saved." . count($changed_rapid_options) . " options were not updated."; ;
			}
			else {
				// NO CHANGES
				echo "<span style='color:#eecc00;'><i class='icon-attention'></i></span>There were no changes to be saved.";
			}
		}
		die();
	}

}

?>