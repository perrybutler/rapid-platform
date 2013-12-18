<?php

// load the third-party scripts/libs
include_once dirname(__FILE__) . "/../libs/simple-html-dom/simple_html_dom.php";

// include the Rapid Settings module
include_once dirname(__FILE__) . "/../rapid-settings/rapid-settings.php";

// include the Rapid UI module
include_once dirname(__FILE__) . "/../rapid-ui/rapid-ui.php";

// instantiante the core
global $rapid_platform;
$rapid_platform = new RapidPlatform();

//$rapid_platform = RapidPlatform::get_instance(); UNDONE: singleton pattern...not needed?

// Rapid Platform core class (protected namespace)
class RapidPlatform {

	//UNDONE: singleton pattern...no benefit? also see http://stackoverflow.com/questions/1148068/how-to-avoid-using-php-global-objects
	/*
	private static $instance = null;
	public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
	*/

	// declare main components
	public $settings;
	public $ui;
	//UNDONE: RapidLogin() is deprecated; instead we should merge in recent work on 
	//	my WP-OpenLogin plugin...will need to determine how to handle plugin dependencies
	//public $login;

	// instantiates the Rapid Platform core
	function __construct() {

		// HACK: apply the wpautop plugin fix to prevent <p> elements from showing up everywhere in shortcode output
		remove_filter( 'the_content', 'wpautop' );
		add_filter( 'the_content', 'wpautop' , 12 );

		// localize specific WordPress/Rapid variables for use at the client (PHP -> JS)
		$wpjs = array(
			"ajaxurl" => admin_url('admin-ajax.php'),
			"template_directory" => get_bloginfo("template_directory"),
			"stylesheet_directory" => get_bloginfo("stylesheet_directory"),
			"plugins_url" => plugins_url('', __FILE__),
			"url" => get_bloginfo("url")
		);
		wp_enqueue_script('wpjs', plugins_url('/../wpjs.js', __FILE__));
		wp_localize_script('wpjs', 'wpjs', $wpjs);

		// initialize the main components
		// TODO: these 'new Object()' methods fail and cause a blank white page when the component module has not 
		//	been included, which can be hard to debug, we need better error handling here...
		$this->ui = new RapidUI();
		$this->settings = new RapidSettings();
		/*UNDONE: RapidLogin() is deprecated; instead we should merge in recent work on 
			my WP-OpenLogin plugin...will need to determine how to handle plugin dependencies
		$this->login = new RapidLogin();
		*/
		
		add_action ( 'init', array($this, 'init') );

	}
	
	// kickstarts the initialization process after main components have been initialized
	function init() {
		add_action( 'wp_enqueue_scripts', array($this, 'global_scripts_init') );
		add_action( 'admin_enqueue_scripts', array($this, 'admin_scripts_init') );
		add_action( 'admin_init', array($this, 'activate') );
		add_action( 'wp_footer', array($this, 'load_lightbox') );
		/*UNDONE: this causes major HTML elements to appear in the title for all items on the media page!!!
		 only filter the_title to include admin controls if the setting is enabled
		if ( !$this->options_framework->stored_rapid_options['fontend_admin_controls'] ) {}
		add_filter( "the_title", array($this, "frontend_admin_controls") );
		*/
	}
	
	// initializes scripts for use by all pages (aka global scripts)
	function global_scripts_init() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-easing-1-3', plugins_url('/../libs/jquery/jquery.easing.1.3.js', __FILE__), 'jquery');
		wp_enqueue_script('jquery-imagesloaded', plugins_url('/../libs/jquery/jquery.imagesloaded.js', __FILE__), 'jquery');
		wp_enqueue_script('jquery-waitforimages', plugins_url('/../libs/jquery/jquery.waitforimages.js', __FILE__), 'jquery');
		wp_enqueue_script('less', plugins_url('/../libs/less/less-1.3.3.min.js', __FILE__), array(), false, true);
	}
	
	// initializes scripts for the admin backend pages (aka admin scripts)
	function admin_scripts_init() {
		wp_register_script('rapid_core_script', plugins_url('/rapid-core.js', __FILE__), 'jquery');
		wp_enqueue_script('rapid_core_script');
	}
	
	// this gets fired on admin_init; currently not used for anything
	function activate() {

	}
	
	// this injects the lightbox component into a page template; usually best to place this in a theme's footer.php
	// TODO: rpDialog replaces this...determine best way to unify
	function load_lightbox() {
		echo '<div class="rp-lightbox"><div class="rp-lightbox-controls"><a class="rp-lightbox-control-close" href="#"><i class="icon-cancel"></i></a></div><div class="rp-lightbox-title"></div><p class="rp-lightbox-body"></div>';
		echo '<div class="rp-lightbox-dimmer"></div>';
	}

	// this injects admin controls for editing pages/posts straight into the frontend (live site), allowing a user with proper 
	//	credentials (admin) to edit a post/page "live" without leaving the actual page...this is a work in progress prototype for
	//	the main component 'Rapid Edit'
	function frontend_admin_controls($title) {
		$t = "";
		if ( in_the_loop() ) {
			if ( is_user_logged_in() ) {
				$t .= "<div class='post-admin-controls'><a href='" . get_edit_post_link() . "'><i class='icon-pencil'></i></a></div>";
				$t .= $title;
			}
			else {
				$t .= "<div class='post-admin-controls'><a href='" . get_edit_post_link() . "'><i class='icon-pencil'></i></a></div>";
				$t .= $title;
			}
		}
		else {
			$t = $title;
		}
		return $t;
	}
	
	// this injects admin controls for editing pages/posts straight into the frontend (live site), allowing a user with proper 
	//	credentials (admin) to edit a post/page "live" without leaving the actual page...this is a work in progress prototype for
	//	the main component 'Rapid Edit'
	function frontend_admin_controls2($content) {
		$t = "";
		if ( in_the_loop() ) {
			if ( is_user_logged_in() ) {
				$t .= "<div class='post-admin-controls'><a href='" . get_edit_post_link() . "'><i class='icon-pencil'></i></a></div>";
				$t .= $content;
			}
			else {
				$t .= "<div class='post-admin-controls'><a href='" . get_edit_post_link() . "'><i class='icon-pencil'></i></a></div>";
				$t .= $content;
			}
		}
		else {
			$t = $content;
		}
		return $t;
	}
	
	// quickly prints the internals of an array or object for debugging
	function debug_view ( $what ) {
		echo '<pre>';
		if ( is_array( $what ) )  {
			print_r ( $what );
		} else {
			var_dump ( $what );
		}
		echo '</pre>';
	}	
	
}

?>