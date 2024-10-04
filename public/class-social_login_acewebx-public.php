<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://acewebx.com
 * @since      1.0.0
 *
 * @package    Google_login
 * @subpackage Google_login/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    social_login_by_acewebx
 * @subpackage social_login_by_acewebx/public
 * @author     acewebx 
 */
class Google_login_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
	// include the css file 
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/social_login_acewebx-public.css', array(), $this->version, 'all');
		wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', array(), '6.5.2' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
	// include script file
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/social_login_acewebx-public.js', array( 'jquery' ), $this->version, false );
 		wp_enqueue_script('facebook-sdk', 'https://connect.facebook.net/en_US/sdk.js', array(), '1.0', true);
	}
	// Display custom Google login button in the admin login form.
	function admin_custom_login_form() {
    $custom_data = get_option('my_google_key');

    if (isset($custom_data['Google_login']) && isset($custom_data['button_display_admin_login_form']) && $custom_data['Google_login'] && $custom_data['button_display_admin_login_form']) {
		$Client_id =$custom_data['Client_ID'];
		$redirect_uri = urlencode(home_url());
            $google_login_url = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id={$Client_id}&redirect_uri={$redirect_uri}&scope=email%20profile";

            echo '<a href="' . esc_url($google_login_url) . '" class="button admin-google-login-button"><img class="google-image" src="https://cdn1.iconfinder.com/data/icons/google-s-logo/150/Google_Icons-09-512.png">Login with Google </a>';

            echo "<style>
                    .google-image {
                        width: 29px;
                    }
                    .button.admin-google-login-button {
                        display: flex;
                        justify-content: center;
                        margin: 0 0 10px 0;
                    }
                </style>";
    	}
	}
	// Display custom Google login button in the 'My Account' login form.
	function myaccount_custom_login_form() {
	$custom_data = get_option('my_google_key');
	$Client_id =$custom_data['Client_ID'];
	if(isset($custom_data['Google_login'])&& $custom_data['Google_login'] =='1'){
    $redirect_uri = urlencode(home_url());
	$google_login_url = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id={$Client_id}&redirect_uri={$redirect_uri}&scope=email%20profile";
	echo '<a href="' . esc_url($google_login_url) . '" class="button google-login-button"><img class="google-image"src="https://cdn1.iconfinder.com/data/icons/google-s-logo/150/Google_Icons-09-512.png">Login with Google </a>';
	}
	}
	// Handle registration of new user from Google OAuth.
	function myaccount_register_user_from_google() {
		    $custom_data = get_option('my_google_key');
		if(is_array($custom_data) && isset($custom_data['Client_ID'], $custom_data['Client_secret'], $custom_data['role'])) {
			$Client_id =$custom_data['Client_ID'];
			$client_secret =$custom_data['Client_secret'];
			$role =$custom_data['role'];	
			};
		if (isset($_GET['code']) && !empty($_GET['code']|| isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'google_auth_nonce'))) {
			$code = sanitize_text_field($_GET['code']);
			// Exchange authorization code for access token
			$token_url = 'https://accounts.google.com/o/oauth2/token';
			$params = array(
				'code' => $code,
				'client_id' => $Client_id,
				'client_secret' => $client_secret,
				'redirect_uri' => home_url(),
				'grant_type' => 'authorization_code'
			);
			$response = wp_remote_post($token_url, array(
			'body' => $params
			));
			if (is_wp_error($response)) {
				return; // Handle error here
			}
			$response_body = wp_remote_retrieve_body($response);
			$token_data = json_decode($response_body, true);
			if (isset($token_data['access_token'])) {
				// Get user info
				$profile_url = 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $token_data['access_token'];
				$profile_response = wp_remote_get($profile_url);
				if (!is_wp_error($profile_response) && wp_remote_retrieve_response_code($profile_response) === 200) {
					$profile_data = wp_remote_retrieve_body($profile_response);
					$profile_data = json_decode($profile_data, true);
				// Create WordPress user if not exists
				if ($profile_data && isset($profile_data['email'])) {
					$user = get_user_by('email', $profile_data['email']);	
					if (!$user) {
						// Create new user
							$user_id = wp_insert_user(array(
							'user_login' => $profile_data['email'],
							'user_email' => $profile_data['email'],
							'user_pass' => wp_generate_password(),
							'first_name' => $profile_data['given_name'] ?? '',
							// 'last_name' => $profile_data['family_name'] ?? '',
							'role' => $role,	
						));
						if (!is_wp_error($user_id)) {		
						if (isset($profile_data['picture'])) {
							$avatar_url = $profile_data['picture'];
							update_user_meta($user_id, 'custom_user_avatar', $avatar_url);
						}					
						} 
							if (is_wp_error($user_id)) {
							 wp_die('Error creating user');
						}		
					}
					$user = get_user_by('email', $profile_data['email']);	
						$user_id= $user->data->ID;
						wp_set_current_user($user_id);
						wp_set_auth_cookie($user_id);
						do_action('wp_login', $user->user_login, $user);
						// Redirect user after login
						wp_redirect(home_url($custom_data['Redirect_url']));
						exit;
					}
				}
			}
		} 
	}
	// Display Facebook login button in the admin login form.
	public function adminfacebook_login(){
	$custom_data = get_option('my_google_key');
 	if (isset($custom_data['facebook_login']) && isset($custom_data['button_display_admin_login_form']) && $custom_data['facebook_login'] && $custom_data['button_display_admin_login_form']) {	
		$redirect_uri = urlencode(home_url('/')); 
		$app_id =   $custom_data['facebook_app_id'];   //1792723511220152;
		$login_url = 'https://www.facebook.com/v13.0/dialog/oauth?response_type=code&client_id=' . $app_id . '&redirect_uri=' . $redirect_uri . '&scope=public_profile,email';
		echo '<a href="' .esc_url($login_url) . '" class="button admin-facebook-login-button"><img class="facebook-image"src="https://static-00.iconduck.com/assets.00/facebook-icon-512x512-seb542ju.png">Login with Facebook</a>';
		echo "<style>
		.facebook-image {
		width: 23px;
		padding: 2px;
		margin-left: 17px;
		}
		.button.admin-facebook-login-button {
		width: 100%;
		justify-content: center;
		display: flex;
		gap: 6px;
		}
		</style>";
	}
	}
	//  Display Facebook login button in the 'My Account' login form.
	public function facebook_login(){
		$custom_data = get_option('my_google_key');
		if(isset($custom_data['facebook_login'])&& $custom_data['facebook_login']=='1'){
		$redirect_uri = urlencode(home_url('/')); 
		$app_id = $custom_data['facebook_app_id']; //1792723511220152;
		$login_url = 'https://www.facebook.com/v13.0/dialog/oauth?response_type=code&client_id=' . $app_id . '&redirect_uri=' . $redirect_uri . '&scope=public_profile,email';
		echo '<a href="' . esc_url($login_url) . '" class="button facebook-login-button"><img class="facebook-image"src="https://static-00.iconduck.com/assets.00/facebook-icon-512x512-seb542ju.png">Login with Facebook</a>';
		
	}
	}
	// Handle Facebook login API integration.
	public function fabook_login_api(){
	$custom_data = get_option('my_google_key');
	if (isset($_GET['code'])|| isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'google_auth_nonce')) {
		$app_id =	$custom_data['facebook_app_id'];     // 1792723511220152;
        $app_secret = $custom_data['facebook_app_secret'];  //'5436239a8aef57f7542306bd2cd6fbb6';
        $redirect_uri = urlencode(home_url('/'));
        $code = $_GET['code'];
        $token_url = 'https://graph.facebook.com/v13.0/oauth/access_token?client_id=' . $app_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $app_secret . '&code=' . $code;
        $response = wp_remote_get($token_url);
		if (!is_wp_error($response)) {
            $body = wp_remote_retrieve_body($response);
            $params = json_decode($body);
            if (isset($params->access_token)) {
				$user_profile_url = 'https://graph.facebook.com/v13.0/me?fields=id,name,email,picture&access_token=' . $params->access_token;
                $user_profile_response = wp_remote_get($user_profile_url);
                if (!is_wp_error($user_profile_response)) {
                    $user_profile_body = wp_remote_retrieve_body($user_profile_response);
                    $user_data = json_decode($user_profile_body);					
                    if (is_object($user_data)) {
                        $username = isset($user_data->name) ? $user_data->name : '';
                        $email = isset($user_data->email) ? $user_data->email : '';
						$profile_picture = isset($user_data->picture->data->url) ? $user_data->picture->data->url : '';
                        if (!empty($username) && !empty($email)) {	
							    $name = explode(" ", $username);
								$first_name = isset($name[0]) ? $name[0] : '';
								$last_name = isset($name[1]) ? $name[1] : '';
								$custom_data = get_option('my_google_key');
								$role = !empty($custom_data['role']) ? $custom_data['role'] : '';
								$user_id = wp_insert_user(array(
									'user_login' => $email,
									'user_email' => $email,
									'user_pass' => wp_generate_password(),
									'first_name' => $first_name,
									'last_name' => $last_name,
									'role' => $role
								));
								$user = get_user_by('email', $email);
								$user_id= $user->data->ID;
								wp_set_current_user($user_id);
								wp_set_auth_cookie($user_id);
								update_user_meta($user_id, 'custom_user_avatar', $profile_picture);
								do_action('my_custom_login_action', $user->user_login, $user);
								wp_redirect(home_url($custom_data['Redirect_url']));
							exit;
						   }
                        } 
                    } 
                } 
            } 
        
		}
	}	
	// Display custom user avatar based on user meta.

	function custom_user_avatar($avatar, $id_or_email, $size, $default, $alt) {
    if (is_numeric($id_or_email)) {
        $user_id = (int) $id_or_email;
    } elseif (is_object($id_or_email)) {
        if (!empty($id_or_email->user_id)) {
            $user_id = (int) $id_or_email->user_id;
        }
    } else {
        $user = get_user_by('email', $id_or_email);
        if ($user) {
            $user_id = $user->ID;
        }
    }
    if (isset($user_id)) {
        $custom_avatar_url = get_user_meta($user_id, 'custom_user_avatar', true);
        if ($custom_avatar_url) {
            $avatar = "<img alt='{$alt}' src='{$custom_avatar_url}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
    }
    return $avatar;
	}

	// Display social login buttons shortcode.
	
	function social_login_button() {
    $custom_data = get_option('my_google_key');

    // Start output buffering
    ob_start();
    if (!is_user_logged_in()) {
        echo '<div class="social-login-container">';
        if (isset($custom_data['Google_login']) && !empty($custom_data['Google_login'])) {
            $Client_id = $custom_data['Client_ID'];
            $redirect_uri = urlencode(home_url());
            $google_login_url = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id={$Client_id}&redirect_uri={$redirect_uri}&scope=email%20profile";

            echo '<a href="' . esc_url($google_login_url) . '" class="button google-login-button"><img class="google-image" src="https://cdn1.iconfinder.com/data/icons/google-s-logo/150/Google_Icons-09-512.png">Login with Google</a>';
        }
        if (isset($custom_data['facebook_login']) && !empty($custom_data['facebook_login'])) {
            $redirect_uri = urlencode(home_url('/'));
            $app_id = $custom_data['facebook_app_id'];
            $login_url = 'https://www.facebook.com/v13.0/dialog/oauth?response_type=code&client_id=' . $app_id . '&redirect_uri=' . $redirect_uri . '&scope=public_profile,email';
            echo '<a href="' . esc_url($login_url) . '" class="button facebook-login-button"><img class="facebook-image" src="https://static-00.iconduck.com/assets.00/facebook-icon-512x512-seb542ju.png">Login with Facebook</a>';
        }
        echo '</div>'; // Close the container div
    }
    // Get the output buffer content and clean the buffer
    return ob_get_clean();
	}
}

