<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://acewebx.com
 * @since      1.0.0
 *
 * @package    Social_Login_by_Acewebx_Login
 * @subpackage Social_Login_by_Acewebx_Login/admin/partials
 */
?>

<?php 
    // Check if the form is submitted
    if(isset($_POST['submit'])) {
        // Verify nonce for security
        if (!isset($_POST['submit_form_nonce']) || !wp_verify_nonce($_POST['submit_form_nonce'], 'submit_form')) {
            // Process form data and update options
            $key = array(
                'Google_login' => isset($_POST['Google_login']) ? true : false,
                'Client_ID' => isset($_POST['Client_ID']) ? sanitize_text_field($_POST['Client_ID']) : '',
                'Client_secret' => isset($_POST['Client_secret']) ? sanitize_text_field($_POST['Client_secret']) : '',
                'facebook_login' => isset($_POST['facebook_login']) ? true : false,
                'facebook_app_id' => isset($_POST['facebook_app_id']) ? sanitize_text_field($_POST['facebook_app_id']) : '',
                'facebook_app_secret' => isset($_POST['app_secret_key']) ? sanitize_text_field($_POST['app_secret_key']) : '',
                'role' => isset($_POST['role']) ? sanitize_text_field($_POST['role']) : '',
                'Redirect_url' => isset($_POST['redirect_url']) ? sanitize_text_field($_POST['redirect_url']) : '',
                'button_display_admin_login_form' => isset($_POST['buttons_display_to_admin']) ? true : false 
            );
            // Update plugin options
            update_option('my_google_key', $key);  
        }
    }

    // Retrieve saved options or use defaults
    $key_default = array(
        'Client_ID' => '',
        'Google_login' => false,
        'Client_ID' => '',
        'Client_secret' => '',
        'role' => '',
        'facebook_login' => false,
        'facebook_app_id' => '',
        'facebook_app_secret' => '',
        'button_display_admin_login_form' => false,
        'Redirect_url' => ''
    );
    $custom_data = get_option('my_google_key', $key_default);  
    
    if (!is_array($custom_data)) {
        $custom_data = $key_default;
    }
?>
<!-- Social login header -->
<div class="Social_Login_header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="social_login_left_sider_content">
                    <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . '/images/multi-user-vector-illustration-icon-260nw-714503581(2)(1).png' ?>" alt="">
                    <h1 class="header_text">Social Login</h1>
                </div>
            </div>
            <div class="col-sm-6">
                <!-- <div class="social_login_right_sider_content">
                    <a href="">You Liked It ? Donation <i class="fab fa-paypal fa-2x"></i></a>
                </div> -->
            </div>
        </div>
    </div>
</div>

<!-- Admin panel tab to change the api key section google login and facebook login tab  -->
<div class="container-fluid">
    <div class="tab-container">
        <!-- Tab buttons for Google and Facebook -->
        <button class="tab" id="Google_tab" onclick="openTab(event, 'Google')">Google</button>
        <button class="tab" id="Facebook_tab" onclick="openTab(event, 'Facebook')">Facebook</button>
    </div>
    <!-- Form for settings -->
    <form method="POST" action="" id="settings_form">
        <!-- Google Settings -->
        <div id="Google" class="tab-content">
            <!-- Steps to connect plugin with Google Console -->
            <table>
                <tr>
                    <th><label for="Google_login">Google login</label></th>
                    <td><input type="checkbox" id="Google_login" name="Google_login" class="form-check-input"<?php echo $custom_data['Google_login'] ? 'checked' : ''; ?>></td>
                    <td colspan="4" rowspan="6">
                        <!-- Detailed steps -->
                        <h2>Steps</h2>
                        <p class="steps">
                            1. Go to the <a href="https://console.cloud.google.com/">https://console.cloud.google.com/</a>, website create a new account, and log in with your account.<br>
                            2. After creating your account, go to "New Project" and create a new project.<br>
                            3. In the left-hand sidebar, click on "APIs & Services" and then "Dashboard".<br>
                            4. Click on "+ ENABLE APIS AND SERVICES" at the top of the dashboard.<br>
                            5. Click on "OAuth consent screen" in the left-hand sidebar.<br>
                            6. Add App information and App domain, then save the settings.<br>
                            7. Add Test users for testing, check the summary, and verify that the data is correct.<br>
                            8. Click on "Credentials" in the left-hand sidebar and then on "Dashboard".<br>
                            Add 'Authorized JavaScript origins' details and 'Authorized redirect URIs'. After that, save the settings. You can check your 'Client ID' and 'Client Secret' in the Additional information section on the right-hand side in the credentials tab.
                        </p>
                    </td>
                </tr>
                <!-- Google API key connect Field -->
                <tr>
                    <th><label for="Client_ID">Enter the Client ID</label></th>
                    <td><input type="text" name="Client_ID" id="Client_ID" value="<?php echo esc_attr($custom_data['Client_ID']); ?>"></td>
                </tr>
                <tr>
                    <th><label for="Client_secret">Enter the Client secret</label></th>
                    <td><input type="text" name="Client_secret" id="Client_secret" value="<?php echo esc_attr($custom_data['Client_secret']); ?>"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <!-- Instructions to get Client ID and Client secret -->
                        <p>Get the client ID and Client secret from Google Console. <a href="https://console.cloud.google.com/">https://console.cloud.google.com/</a></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Facebook Settings -->
        <div id="Facebook" class="tab-content" style="display:none;">
            <!-- Steps to connect plugin with Facebook Developer -->
            <table>
                <tr>
                    <th><label for="facebook_login">Facebook login</label></th>
                    <td><input type="checkbox" class="form-check-input" id="facebook_login" name="facebook_login" <?php echo $custom_data['facebook_login'] ? 'checked' : ''; ?>></td>
                    <td colspan="4" rowspan="6">
                        <h2>Steps</h2>
                        <p class="steps">
                            1. Go to the <a href="https://developers.facebook.com/">https://developers.facebook.com/</a> website, create a new account, and log in with your account.<br>
                            2. Navigate to the "My Apps" dropdown menu and select "Create App".<br>
                            3. Choose the appropriate app type and provide the necessary details.<br>
                            4. After creating the app, navigate to the app dashboard.<br>
                            5. Under "Settings" in the left sidebar, click on "Basic".<br>
                            6. Here, you will find your App ID and App Secret Key. Copy them for use in this form.
                        </p>
                    </td>
                </tr>
                <!-- Facebook API key fields -->
                <tr>
                    <th><label for="facebook_app_id">Enter the App ID</label></th>
                    <td><input type="text" name="facebook_app_id" id="facebook_app_id" value="<?php echo esc_attr($custom_data['facebook_app_id']); ?>"></td>
                </tr>
                <tr>
                    <th><label for="facebook_app_secret">Enter the App Secret Key</label></th>
                    <td><input type="text" name="app_secret_key" id="facebook_app_secret_key" value="<?php echo esc_attr($custom_data['facebook_app_secret']); ?>"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <!-- Instructions to get App ID and App Secret Key -->
                        <p class="get_api_key_links">Get the App ID and App Secret Key from Facebook developer. <a href="https://developers.facebook.com/">https://developers.facebook.com/</a></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Redirection setting field and shortcode -->
        <table>
            <tr>
                <th>Redirect url</th>
                <td class="redirect_url_td">
                    <!-- Display site URL and input field for redirect URL -->
                    <p><?php echo esc_url( site_url() . '/' );?></p>
                    <input type="text" name="redirect_url" value="<?php echo esc_attr($custom_data['Redirect_url']); ?>" class="redirecturl">
                </td>
            </tr>
            <tr>
                <th><label for="role">SELECT ROLE</label></th>
                <td>
                    <!-- Dropdown to select user role -->
                    <select name="role" id="role">
                        <?php 
                        global $wp_roles; 
                        $roles = $wp_roles->get_names();
                        foreach ($roles as $role_key => $role_name) {
                            echo '<option value="' . esc_attr($role_key) . '" ' . selected($custom_data['role'], $role_key, false) . '>' . esc_html($role_name) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="login_button_display_">Display in admin login page</label></th>
                <!-- Checkbox to display in WP admin -->
                <td><input type="checkbox" name="buttons_display_to_admin" id="login_button_display_" <?php echo $custom_data['button_display_admin_login_form'] ? 'checked' : ''; ?>></td>
            </tr>
            <!-- Display button using shortcode -->
            <tr>
                <th><label>Display button using shortcode</label></th>
                <td><label>[social_login]
                <button type="button" class="socical-login-shortcode-copy-btn" data-shortcode="[social_login]">Copy</button>
                </label></td>
            </tr>
        </table>
        <!-- Submit button for saving settings -->
        <?php submit_button('Save Settings'); ?>
    </form>
</div>
