<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://acewebx.com
 * @since      1.0.0
 *
 * @package    Google_login
 * @subpackage Google_login/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Google_login
 * @subpackage Google_login/includes
 * @author     acewebx 
 */
class Google_login {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Google_login_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SOCIAL_LOGIN_BY_ACEWEBX_VERSION' ) ) {
			$this->version = SOCIAL_LOGIN_BY_ACEWEBX_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'social_login_by_acewebx';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Google_login_Loader. Orchestrates the hooks of the plugin.
	 * - Google_login_i18n. Defines internationalization functionality.
	 * - Google_login_Admin. Defines all hooks for the admin area.
	 * - Google_login_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-social-login-by-acewebx-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-social-login-by-acewebx-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-social_login_acewebx-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-social_login_acewebx-public.php';

		$this->loader = new Google_login_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Google_login_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Google_login_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		// Instantiate the admin-facing functionality class
		$plugin_admin = new Google_login_Admin($this->get_plugin_name(), $this->get_version());
	
		// Enqueue admin styles and scripts
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	
		// Add a custom settings page to the WordPress admin menu
		$this->loader->add_action('admin_menu', $plugin_admin, 'custom_setting_page');
	}
	

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		// Instantiate the public-facing functionality class
		$plugin_public = new Google_login_Public($this->get_plugin_name(), $this->get_version());
	
		// Enqueue public styles and scripts
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	
		// Add Google login button to WooCommerce login form and WordPress admin login form
		$this->loader->add_action('woocommerce_login_form_end', $plugin_public, 'myaccount_custom_login_form');
		$this->loader->add_action('login_form', $plugin_public, 'admin_custom_login_form');
	
		// Add Facebook login button to WooCommerce login form and WordPress admin login form
		$this->loader->add_action('woocommerce_login_form_end', $plugin_public, 'facebook_login');
		$this->loader->add_action('login_form', $plugin_public, 'adminfacebook_login');
	
		// Handle user registration via Google login
		$this->loader->add_action('init', $plugin_public, 'myaccount_register_user_from_google');
	
		// Handle user login via Facebook login API
		$this->loader->add_action('init', $plugin_public, 'fabook_login_api');
	
		// Customize user avatars
		$this->loader->add_filter('get_avatar', $plugin_public, 'custom_user_avatar', 10, 5);
	
		// Add shortcode for displaying social login buttons
		add_shortcode('social_login', array($plugin_public, 'social_login_button'));
	}
	
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Google_login_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
