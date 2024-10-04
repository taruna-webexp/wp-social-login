<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://acewebx.com
 * @since             1.0.0
 * @package           social_login_by_acewebx
 *
 * @wordpress-plugin
 * Plugin Name:       Social login by Acewebx
 * Plugin URI:        https://social_login_by_acewebx
 * Description:       Social Login is a powerful and user-friendly  plugin that enables seamless login integration with Google and Facebook. Enhance your website’s user experience by allowing visitors to log in quickly and securely using their existing social media accounts.
 * Version:           1.0.0
 * Author:            acewebx
 * Author URI:        https://acewebx.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       social_login_by_acewebx
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SOCIAL_LOGIN_BY_ACEWEBX_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-social_login_byacewebx_login-activator.php
 */
function activate_google_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-social_login_byacewebx_login-activator.php';
	Google_login_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-social-loginlogin-deactivator.php
 */
function deactivate_google_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-social-login-deactivator.php';
	Google_login_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_google_login' );
register_deactivation_hook( __FILE__, 'deactivate_google_login' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-social_login_by_acewebx_login.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_google_login() {

	$plugin = new Google_login();
	$plugin->run();

}
run_google_login();
