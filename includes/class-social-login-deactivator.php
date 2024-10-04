<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://acewebx.com
 * @since      1.0.0
 *
 * @package    Google_login
 * @subpackage Google_login/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Google_login
 * @subpackage Google_login/includes
 * @author     acewebx 
 */
class Google_login_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// Delete the option 'my_google_key' from the WordPress database when the plugin is deactivated
		delete_option('my_google_key');
	}

}
