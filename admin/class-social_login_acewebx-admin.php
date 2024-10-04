<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://acewebx.com
 * @since      1.0.0
 *
 * @package    Social_Login_by_Acewebx_Login
 * @subpackage Social_Login_by_Acewebx_Login/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Social_Login_by_Acewebx_Login
 * @subpackage Social_Login_by_Acewebx_Login/admin
 * @author     acewebx 
 */
class Google_login_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/socail_login_byacewebx-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style('bootstrap-css', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap-grid.min.css');
		wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', array(), '6.5.2');
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/socail_login_byacewebx-admin.js', array( 'jquery' ),'2.0 0', false );
	}
	function custom_login_form() {
	}
	// Function to add custom setting page for Social Login
	function custom_setting_page() {
		add_menu_page(
			'Social login',
			'Social login', 
			'manage_options', 
			'social_login', 
			array( $this, 'custom_setting_page_content' ), 
			'dashicons-admin-users',
			80 
		);
	}
	// Callback function to render content for the custom setting page
	function custom_setting_page_content() {
		require_once(plugin_dir_path( dirname( __FILE__ ) ) . '/admin/partials/social_login-admin-display.php');
	}
}
