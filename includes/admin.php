<?php

session_start();

// ini_set('display_errors', 1);
// error_reporting(E_ALL | E_STRICT);

require_once 'FacebookData.php';

/**
 * Register our wporg_settings_init to the admin_init action hook.
 */
add_action('admin_init', 'social_feed_ez_settings_init');


/**
 * Register our wporg_options_page to the admin_menu action hook.
 */
add_action('admin_menu', 'social_feed_ez_options_page');


/**
 * Load add feed form after selecting Record
 */
add_action('wp_ajax_nopriv_social_feed_ez_verify_token', 'social_feed_ez_verify_token', 10, 1);
add_action('wp_ajax_social_feed_ez_verify_token', 'social_feed_ez_verify_token', 10, 1);

if (!defined('SOCIAL_FEED_EZ_PLUGIN_DIR')) {
	define('SOCIAL_FEED_EZ_PLUGIN_DIR', dirname(__FILE__));
}

/**
 * Add the top level menu page.
 */
function social_feed_ez_options_page() {
	add_options_page(
		'Social Feed EZ',
		'Social Feed EZ',
		'manage_options',
		'social_feed_ez',
		'social_feed_ez_options_page_html'
	);
}

/**
 * custom option and settings
 */
function social_feed_ez_settings_init() {

	// Load admin scripts
	if (is_admin()) {
		if (isset($_REQUEST['page']) && 'social_feed_ez' == sanitize_text_field($_REQUEST['page'])) {

			wp_enqueue_script('social_feed_ez_admin', plugins_url() . '/social-feed-ez/assets/js/admin.js');

			wp_localize_script(
				'social_feed_ez_admin',
				'plugin_data',
				array(
					'ajaxurl'            => admin_url('admin-ajax.php')
				)
			);

			wp_enqueue_style('social_feed_ez_admin_css', plugins_url() . '/social-feed-ez/assets/css/admin.css', true);
		}
	}


	// Register a new setting for "wporg" page.
	register_setting('social_feed_ez', 'social_feed_ez_app_id');
	register_setting('social_feed_ez', 'social_feed_ez_app_secret');
	register_setting('social_feed_ez', 'social_feed_ez_page_id');
	register_setting('social_feed_ez_token', 'social_feed_ez_access_token');
	register_setting('social_feed_ez_token', 'social_feed_ez_ll_access_token');
	register_setting('social_feed_display', 'social_feed_display_type');
	register_setting('social_feed_profiles', 'social_feed_ez_page_id');
	register_setting('social_feed_profiles', 'social_feed_ez_ll_access_token');

	// Register a new section in the "wporg" page.
	add_settings_section(
		'social_feed_ez_section_developers',
		__('Facebook Page Feed Settings', 'social_feed_ez'),
		'social_feed_ez_section_developers_callback',
		'social_feed_ez'
	);
	// Register a new section in the "wporg" page.
	add_settings_section(
		'social_feed_ez_section_developers',
		__('Facebook Page Token Settings', 'social_feed_ez_token'),
		'social_feed_ez_section_developers_callback',
		'social_feed_ez_token'
	);
	// Register a new section in the "wporg" page.
	add_settings_section(
		'social_feed_ez_section_developers',
		__('Facebook Feed Display Settings', 'social_feed_diplay'),
		'social_feed_ez_section_developers_callback',
		'social_feed_display'
	);
	// Register a new section in the "wporg" page.
	add_settings_section(
		'social_feed_ez_section_developers',
		__('Facebook Feed Login', 'social_feed_login'),
		'social_feed_ez_section_fb_login',
		'social_feed_login'
	);
	// Register a new section in the "wporg" page.
	add_settings_section(
		'social_feed_ez_section_developers',
		__('Facebook Feed Profiles', 'social_feed_profiles'),
		'social_feed_ez_section_fb_profiles',
		'social_feed_profiles'
	);

	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'social_feed_ez_field_app_id', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('App ID', 'social_feed_ez'),
		'social_feed_ez_field_app_id_cb',
		'social_feed_ez',
		'social_feed_ez_section_developers'
	);
	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'social_feed_ez_field_app_secret', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('App Secret', 'social_feed_ez'),
		'social_feed_ez_field_app_secret_cb',
		'social_feed_ez',
		'social_feed_ez_section_developers'
	);
	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	// add_settings_field(
	// 	'social_feed_ez_field_page_id', // As of WP 4.6 this value is used only internally.
	// 	// Use $args' label_for to populate the id inside the callback.
	// 	__('Page ID', 'social_feed_ez'),
	// 	'social_feed_ez_field_page_id_cb',
	// 	'social_feed_ez',
	// 	'social_feed_ez_section_developers'
	// );
	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'social_feed_ez_field_app_token', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('Page Access Token', 'social_feed_ez_token'),
		'social_feed_ez_field_app_token_cb',
		'social_feed_ez_token',
		'social_feed_ez_section_developers'
	);
	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'social_feed_ez_field_ll_app_token', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('Page Long-Lived Access Token', 'social_feed_ez_token'),
		'social_feed_ez_field_ll_app_token_cb',
		'social_feed_ez_token',
		'social_feed_ez_section_developers'
	);
	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'social_feed_diplay_type', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('Facebook Feed Display Type', 'social_feed_ez_token'),
		'social_feed_ez_field_display_cb',
		'social_feed_display',
		'social_feed_ez_section_developers'
	);
	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'social_feed_ez_field_page_id', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('Page ID', 'social_feed_profiles'),
		'social_feed_ez_field_page_id_cb_profiles',
		'social_feed_profiles',
		'social_feed_ez_section_developers'
	);
	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'social_feed_ez_field_ll_app_token', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('Page Long-Lived Access Token', 'social_feed_profiles'),
		'social_feed_ez_field_ll_app_token_cb_profiles',
		'social_feed_profiles',
		'social_feed_ez_section_developers'
	);
}

/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function social_feed_ez_section_developers_callback($args) {
}

/**
 * Developers section fb login function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function social_feed_ez_section_in_login($args) {

	if ('' == sanitize_text_field(get_option('social_feed_ez_app_id')) || '' == sanitize_text_field(get_option('social_feed_ez_app_secret'))) {
		wp_die('Error: Missing Facebook App ID or App Secret. Return to settings and enter your app ID and Secret', 'Error: Missing Facebook App ID or App Secret');
	}

	$app_id = sanitize_text_field(get_option('social_feed_ez_app_id'));
	$app_secret = sanitize_text_field(get_option('social_feed_ez_app_secret'));

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.instagram.com/oauth/access_token",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => array('client_id' => $app_id, 'client_secret' => $app_secret, 'grant_type' => 'authorization_code', 'redirect_uri' => plugins_url() . '/social-feed-ez/templates/admin_fb_callback.php', 'code' => '{code}'),
		CURLOPT_HTTPHEADER => array(
			"Content-Type: multipart/form-data; boundary=--------------------------780367731654051340650991"
		),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	//print_r($response);
}

/**
 * Developers section fb login function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function social_feed_ez_section_fb_login($args) {

	//session_start();

	require_once dirname(__DIR__) . '/vendors/autoload.php'; // change path as needed

	if ('' == sanitize_text_field(get_option('social_feed_ez_app_id')) || '' == sanitize_text_field(get_option('social_feed_ez_app_secret'))) {
		wp_die('Error: Missing Facebook App ID or App Secret. Return to settings and enter your app ID and Secret', 'Error: Missing Facebook App ID or App Secret');
	}

	$app_id = sanitize_text_field(get_option('social_feed_ez_app_id'));
	$app_secret = sanitize_text_field(get_option('social_feed_ez_app_secret'));


	$fb = new \Facebook\Facebook([
		'app_id' => $app_id,
		'app_secret' => $app_secret,
		'graph_api_version' => 'v17.0',
		'default_graph_version' => 'v10.0',
		//'default_access_token' => '{access-token}', // optional
	]);

	$helper = $fb->getRedirectLoginHelper();

	if (isset($_GET['state'])) {
		$helper->getPersistentDataHandler()->set('state', $_GET['state']);
	}


	$permissions = ['email', 'business_management', 'pages_show_list', 'instagram_basic', 'pages_read_engagement', 'ads_management']; // Optional permissions
	$loginUrl = $helper->getLoginUrl(plugins_url() . '/social-feed-ez/templates/admin_fb_callback.php', $permissions);


	echo '<a href="' . esc_html($loginUrl) . '" target="_blank">Log in with Facebook!</a>';
}

/**
 * Developers section fb profilesw function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function social_feed_ez_section_fb_profiles($args) {


	$Facebook = new FacebookData();

	

	//echo '<script>console.log(' . $_SESSION['fb_callback_results'] . ');</script>';


	echo '<div class="pages-wrapper">';

	if($_SESSION['fb_callback_results'] !== null){
		foreach ($_SESSION['fb_callback_results'] as $key => $value) {

			//
			$instagram_id = $value['instagram_business_account']['id'];
			$facebook_id = $value['id'];
	
			$page_id = $instagram_id ? $instagram_id : $facebook_id;
			
			$profile_url = $instagram_id ? $Facebook->InstagramProfilePic($instagram_id) : $Facebook->FacebookProfilePic($facebook_id);
	
			$options = sanitize_text_field(get_option('social_feed_ez_page_id'));
	
			include SOCIAL_FEED_EZ_PLUGIN_DIR . '/templates/admin_pages_button_in.php';
	
		}
	}else{
		echo esc_html($_SESSION['fb_callback_results']);
	}

	
	echo '</div>';
}

/**
 * 
 * 
 */
function social_feed_ez_fb_user_picture($id) {

	//
	$app_id = sanitize_text_field(get_option('social_feed_ez_app_id'));
	$app_secret = sanitize_text_field(get_option('social_feed_ez_app_secret'));
	$ll_token = sanitize_text_field(get_option('social_feed_ez_ll_access_token'));
	$page_id = $id;

	$fb = new \Facebook\Facebook([
		'app_id' => $app_id,
		'app_secret' => $app_secret,
		'graph_api_version' => 'v9.0'
	]);

	try {
		// Returns a `FacebookFacebookResponse` object
		$response = $fb->get(
			'/' . $page_id . '/picture?redirect=0',
			$ll_token
		);
	} catch (FacebookExceptionsFacebookResponseException $e) {
		echo 'Graph returned an error social_feed_ez_fb_user_picture: ' . esc_html($e->getMessage());
		exit;
	} catch (FacebookExceptionsFacebookSDKException $e) {
		echo 'Facebook SDK returned an error social_feed_ez_fb_user_picture: ' . esc_html($e->getMessage());
		exit;
	}

	$graphNode = $response->getGraphNode();

	return $graphNode['url'];
}


/**
 * Input field for the app ID
 *
 * @param array $args The settings array, defining title, id, callback.
 */

function social_feed_ez_field_app_id_cb($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = sanitize_text_field(get_option('social_feed_ez_app_id'));
?>
	<input type="text" class="regular-text ltr" name="social_feed_ez_app_id" value="<?php echo esc_html($options); ?>" />
	<p class="description">
		<?php esc_html_e('Description', 'social_feed_ez'); ?>
	</p>
<?php
}

/**
 * Input field for App Secret
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function social_feed_ez_field_app_secret_cb($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = sanitize_text_field(get_option('social_feed_ez_app_secret'));
?>
	<input type="text" class="regular-text ltr" name="social_feed_ez_app_secret" value="<?php esc_html($options); ?>" />
	<p class="description">
		<?php esc_html_e('Description', 'social_feed_ez'); ?>
	</p>
<?php
}

/**
 * Input field for Page ID
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function social_feed_ez_field_page_id_cb($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = sanitize_text_field(get_option('social_feed_ez_page_id'));
?>
	<input type="text" class="regular-text ltr" name="social_feed_ez_page_id" id="social_feed_ez_page_id" value="<?php echo esc_html($options); ?>" />
	<p class="description">
		<?php esc_html_e('Description', 'social_feed_ez'); ?>
	</p>
<?php
}

/**
 * Input field for Page ID Callback
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function social_feed_ez_field_page_id_cb_profiles($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = sanitize_text_field(get_option('social_feed_ez_page_id'));
?>
	<input type="text" class="regular-text ltr" name="social_feed_ez_page_id" id="social_feed_ez_page_id" value="<?php echo '' == esc_html($options) ? esc_html($_SESSION['fb_callback_results'][0]['id']) : esc_html($options); ?>" />
	<p class="description">
		<?php esc_html_e('Description', 'social_feed_ez'); ?>
	</p>
<?php
}

/**
 * Input field for access token.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function social_feed_ez_field_app_token_cb($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = sanitize_text_field(get_option('social_feed_ez_access_token'));

	$fb_feed_nonce = wp_create_nonce('social-feed-ez');

?>
	<input type="text" class="regular-text ltr" id="social_feed_ez_access_token" name="social_feed_ez_access_token" value="<?php esc_html($options); ?>" />
	<div id="social-feed-ez-verify-token" class="facbook-feed-ez-btn">Verify Token</div>
	<input type="hidden" id="social-feed-ez-none" value="<? echo esc_html($fb_feed_nonce); ?>">
	<p class="description">
		<?php esc_html_e('Description', 'social_feed_ez'); ?>
	</p>
<?php
}

/**
 * Input field for long-lived access token.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function social_feed_ez_field_ll_app_token_cb($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = sanitize_text_field(get_option('social_feed_ez_ll_access_token'));
?>
	<input type="text" class="regular-text ltr" id="social_feed_ez_ll_access_token" name="social_feed_ez_ll_access_token" value="<?php echo esc_html($options); ?>" />
	<p class="description">
		<?php esc_html_e('Description', 'social_feed_ez'); ?>
	</p>
<?php
}

/**
 * Input field for long-lived access token.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function social_feed_ez_field_ll_app_token_cb_profiles($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = sanitize_text_field(get_option('social_feed_ez_ll_access_token'));
	//$_SESSION['fb_access_token']
?>
	<input type="text" class="regular-text ltr" id="social_feed_ez_ll_access_token" name="social_feed_ez_ll_access_token" value="<?php echo '' == esc_html($options) ? esc_html($_SESSION['fb_access_token']) : esc_html($options); ?>" />
	<p class="description">
		<?php esc_html_e('Description', 'social_feed_ez'); ?>
	</p>
<?php
}

/**
 * Input field for feed display type.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function social_feed_ez_field_display_cb($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = sanitize_text_field(get_option('social_feed_display_type'));

?>
	<select type="text" class="regular-text ltr" id="social_feed_display_type" name="social_feed_display_type" value="<?php echo esc_html($options); ?>">
		<option value="social-feed-ez-1col" <? echo $options == 'social-feed-ez-1col' ? 'selected' : ''; ?>>One column</option>
		<option value="social-feed-ez-3col" <? echo $options == 'social-feed-ez-3col' ? 'selected' : ''; ?>>Three columns</option>
	</select>
	<p class="description">
		<?php esc_html_e('Choose a preferred number of columns', 'social_feed_ez'); ?>
	</p>
<?php
}

/**
 * 
 * 
 */
function social_feed_ez_field_app_id($args) {
	$options = sanitize_text_field(get_option('social_feed_ez_field__options'));
}

/**
 * Top level menu callback function
 */
function social_feed_ez_options_page_html() {
	// check user capabilities
	if (!current_user_can('manage_options')) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if (isset($_GET['settings-updated'])) {
		// add settings saved message with the class of "updated"
		//add_settings_error('social_feed_ez_messages', 'social_feed_ez_message', __('Settings Saved', 'social_feed_ez'), 'updated');
	}

	// show error/update messages
	settings_errors('social_feed_ez_messages');



	if (isset($_GET)) {
		$active_tab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'fb_page_settings';
	};

?>
	<div class="wrap">

		<?

		require SOCIAL_FEED_EZ_PLUGIN_DIR . '/templates/admin_nav_tabs.php';

		if ('fb_page_settings' == $active_tab) {
			require SOCIAL_FEED_EZ_PLUGIN_DIR . '/templates/admin_page_feed.php';
		}

		if ('fb_token_settings' == $active_tab) {
			require SOCIAL_FEED_EZ_PLUGIN_DIR . '/templates/admin_access_tokens.php';
		}
		if ('fb_display_settings' == $active_tab) {
			require SOCIAL_FEED_EZ_PLUGIN_DIR . '/templates/admin_display_settings.php';
		}
		if ('social_feed_login' == $active_tab) {
			require SOCIAL_FEED_EZ_PLUGIN_DIR . '/templates/admin_fb_login.php';
		}
		if ('social_feed_profiles' == $active_tab) {
			require SOCIAL_FEED_EZ_PLUGIN_DIR . '/templates/admin_fb_profiles.php';
		}
		?>

	</div>
<?php
}

/**
 * Run on verify token button click. Link to Facebook and get long-lived access token. Return any errors.
 *
 *  @param array $args The settings array, defining title, id, callback.
 */
function social_feed_ez_verify_token($args) {

	if (null !== sanitize_text_field($_POST['fb-feed-nonce']) && wp_verify_nonce(sanitize_text_field($_POST['fb-feed-nonce']), 'social-feed-ez') && null !== sanitize_text_field($_POST['fb-access-token'])) {



		$app_id = sanitize_text_field(get_option('social_feed_ez_app_id'));
		$app_secret = sanitize_text_field(get_option('social_feed_ez_app_secret'));
		$user_token = sanitize_text_field(sanitize_text_field($_POST['fb-access-token']));



		$fb = new \Facebook\Facebook([
			'app_id' => $app_id,           //Replace {your-app-id} with your app ID '414647516265304'
			'app_secret' => $app_secret,   //Replace {your-app-secret} with your app secret 'c1d911ec827f870cd29b90c789814960'
			'graph_api_version' => 'v17.0',
			'default_access_token' => $user_token,
			'auth_type' => 'reauthorize',
		]);



		try {
			// Returns a `FacebookFacebookResponse` object
			$response = $fb->get('/oauth/access_token?grant_type=fb_exchange_token&fb_exchange_token=' . $user_token . '&client_id=' . $app_id . '&client_secret=' . $app_secret);
		} catch (\Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error social_feed_ez_verify_token: ' . esc_html($e->getMessage());
			wp_die();
		} catch (\Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error social_feed_ez_verify_token: ' . esc_html($e->getMessage());
			wp_die();
		} catch (Facebook\Exceptions\FacebookAuthenticationException $e) {
			echo 'Facebook Auth returned an error social_feed_ez_verify_token: ' . esc_html($e->getMessage());
			wp_die();
		}

		$graphNode = $response->getGraphNode();

		echo $graphNode;

		wp_die();
	}
}
