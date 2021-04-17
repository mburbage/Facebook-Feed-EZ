<?php

/**
 * Register our wporg_settings_init to the admin_init action hook.
 */
add_action('admin_init', 'facebook_feed_ez_settings_init');


/**
 * Register our wporg_options_page to the admin_menu action hook.
 */
add_action('admin_menu', 'facebook_feed_ez_options_page');


/**
 * Load add feed form after selecting Record
 */
add_action('wp_ajax_nopriv_facebook_feed_ez_verify_token', 'facebook_feed_ez_verify_token', 10, 1);
add_action('wp_ajax_facebook_feed_ez_verify_token', 'facebook_feed_ez_verify_token', 10, 1);

/**
 * Add the top level menu page.
 */
function facebook_feed_ez_options_page() {
	add_options_page(
		'Facebook Feed Ez',
		'Facebook Feed Ez',
		'manage_options',
		'facebook_feed_ez',
		'facebook_feed_ez_options_page_html'
	);
}

/**
 * custom option and settings
 */
function facebook_feed_ez_settings_init() {

	// Load admin scripts
	if (is_admin()) {
		if (isset($_REQUEST['page']) && 'facebook_feed_ez' == esc_html($_REQUEST['page'])) {

			wp_enqueue_script('facebook_feed_ez_admin', plugins_url('/assets/js/admin.js', __DIR__));

			wp_localize_script(
				'facebook_feed_ez_admin',
				'plugin_data',
				array(
					'ajaxurl'            => admin_url('admin-ajax.php')
				)
			);

			wp_enqueue_style('facebook_feed_ez_admin_css', plugins_url('/assets/css/admin.css', __DIR__));
		}
	}


	// Register a new setting for "wporg" page.
	register_setting('facebook_feed_ez', 'facebook_feed_ez_app_id');
	register_setting('facebook_feed_ez', 'facebook_feed_ez_app_secret');
	register_setting('facebook_feed_ez', 'facebook_feed_ez_page_id');
	register_setting('facebook_feed_ez_token', 'facebook_feed_ez_access_token');
	register_setting('facebook_feed_ez_token', 'facebook_feed_ez_ll_access_token');
	register_setting('facebook_feed_display', 'facebook_feed_display_type');

	// Register a new section in the "wporg" page.
	add_settings_section(
		'facebook_feed_ez_section_developers',
		__('Facebook Page Feed Settings', 'facebook_feed_ez'),
		'facebook_feed_ez_section_developers_callback',
		'facebook_feed_ez'
	);
	// Register a new section in the "wporg" page.
	add_settings_section(
		'facebook_feed_ez_section_developers',
		__('Facebook Page Token Settings', 'facebook_feed_ez_token'),
		'facebook_feed_ez_section_developers_callback',
		'facebook_feed_ez_token'
	);
	// Register a new section in the "wporg" page.
	add_settings_section(
		'facebook_feed_ez_section_developers',
		__('Facebook Feed Display Settings', 'facebook_feed_diplay'),
		'facebook_feed_ez_section_developers_callback',
		'facebook_feed_display'
	);

	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'facebook_feed_ez_field_app_id', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('App ID', 'facebook_feed_ez'),
		'facebook_feed_ez_field_app_id_cb',
		'facebook_feed_ez',
		'facebook_feed_ez_section_developers'
	);
	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'facebook_feed_ez_field_app_secret', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('App Secret', 'facebook_feed_ez'),
		'facebook_feed_ez_field_app_secret_cb',
		'facebook_feed_ez',
		'facebook_feed_ez_section_developers'
	);
	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'facebook_feed_ez_field_page_id', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('Page ID', 'facebook_feed_ez'),
		'facebook_feed_ez_field_page_id_cb',
		'facebook_feed_ez',
		'facebook_feed_ez_section_developers'
	);
	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'facebook_feed_ez_field_app_token', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('Page Access Token', 'facebook_feed_ez_token'),
		'facebook_feed_ez_field_app_token_cb',
		'facebook_feed_ez_token',
		'facebook_feed_ez_section_developers'
	);
	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'facebook_feed_ez_field_ll_app_token', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('Page Long-Lived Access Token', 'facebook_feed_ez_token'),
		'facebook_feed_ez_field_ll_app_token_cb',
		'facebook_feed_ez_token',
		'facebook_feed_ez_section_developers'
	);
	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'facebook_feed_diplay_type', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
		__('Facebook Feed Display Type', 'facebook_feed_ez_token'),
		'facebook_feed_ez_field_display_cb',
		'facebook_feed_display',
		'facebook_feed_ez_section_developers'
	);
}

/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function facebook_feed_ez_section_developers_callback($args) {
}

/**
 * Input field for the app ID
 *
 * @param array $args The settings array, defining title, id, callback.
 */

function facebook_feed_ez_field_app_id_cb($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option('facebook_feed_ez_app_id');
?>
	<input type="text" class="regular-text ltr" name="facebook_feed_ez_app_id" value="<?php echo esc_html($options); ?>" />
	<p class="description">
		<?php esc_html_e('Description', 'facebook_feed_ez'); ?>
	</p>
<?php
}

/**
 * Input field for App Secret
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function facebook_feed_ez_field_app_secret_cb($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option('facebook_feed_ez_app_secret');
?>
	<input type="text" class="regular-text ltr" name="facebook_feed_ez_app_secret" value="<?php echo esc_html($options); ?>" />
	<p class="description">
		<?php esc_html_e('Description', 'facebook_feed_ez'); ?>
	</p>
<?php
}

/**
 * Input field for Page ID
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function facebook_feed_ez_field_page_id_cb($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option('facebook_feed_ez_page_id');
?>
	<input type="text" class="regular-text ltr" name="facebook_feed_ez_page_id" value="<?php echo esc_html($options); ?>" />
	<p class="description">
		<?php esc_html_e('Description', 'facebook_feed_ez'); ?>
	</p>
<?php
}

/**
 * Input field for access token.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function facebook_feed_ez_field_app_token_cb($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option('facebook_feed_ez_access_token');

	$fb_feed_nonce = wp_create_nonce('facebook-feed-ez');

?>
	<input type="text" class="regular-text ltr" id="facebook_feed_ez_access_token" name="facebook_feed_ez_access_token" value="<?php echo esc_html($options); ?>" />
	<div id="facebook-feed-ez-verify-token" class="facbook-feed-ez-btn">Verify Token</div>
	<input type="hidden" id="facebook-feed-ez-none" value="<? echo $fb_feed_nonce; ?>">
	<p class="description">
		<?php esc_html_e('Description', 'facebook_feed_ez'); ?>
	</p>
<?php
}

/**
 * Input field for long-lived access token.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function facebook_feed_ez_field_ll_app_token_cb($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option('facebook_feed_ez_ll_access_token');
?>
	<input type="text" class="regular-text ltr" id="facebook_feed_ez_ll_access_token" name="facebook_feed_ez_ll_access_token" value="<?php echo esc_html($options); ?>" readonly="readonly" />
	<p class="description">
		<?php esc_html_e('Description', 'facebook_feed_ez'); ?>
	</p>
<?php
}

/**
 * Input field for feed display type.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function facebook_feed_ez_field_display_cb($args) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option('facebook_feed_display_type');

	echo '<script>console.log('.json_encode( $options ).');</script>';
?>
	<select type="text" class="regular-text ltr" id="facebook_feed_display_type" name="facebook_feed_display_type" value="<?php echo esc_html($options); ?>">
	<option value="facebook-feed-ez-1col" <? echo $options == 'facebook-feed-ez-1col' ? 'selected' : ''; ?>>One column</option>
	<option value="facebook-feed-ez-3col" <? echo $options == 'facebook-feed-ez-3col' ? 'selected' : ''; ?>>Three columns</option>
	</select>
	<p class="description">
		<?php esc_html_e('Choose a preferred number of columns', 'facebook_feed_ez'); ?>
	</p>
<?php
}

/**
 * 
 * 
 */
function facebook_feed_ez_field_app_id($args) {
	$options = get_option('facebook_feed_ez_field__options');
}

/**
 * Top level menu callback function
 */
function facebook_feed_ez_options_page_html() {
	// check user capabilities
	if (!current_user_can('manage_options')) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if (isset($_GET['settings-updated'])) {
		// add settings saved message with the class of "updated"
		//add_settings_error('facebook_feed_ez_messages', 'facebook_feed_ez_message', __('Settings Saved', 'facebook_feed_ez'), 'updated');
	}

	// show error/update messages
	settings_errors('facebook_feed_ez_messages');



	if (isset($_GET)) {
		$active_tab = $_GET['tab'] ? $_GET['tab'] : 'fb_page_settings';
	};

?>
	<div class="wrap">

		<?

		require FACEBOOK_FEED_EZ_PLUGIN_DIR . '/templates/admin_nav_tabs.php';

		if ( 'fb_page_settings' == $active_tab ) {
			require FACEBOOK_FEED_EZ_PLUGIN_DIR . '/templates/admin_page_feed.php';		
		}

		if ( 'fb_token_settings' == $active_tab ) {
			require FACEBOOK_FEED_EZ_PLUGIN_DIR . '/templates/admin_access_tokens.php';
		}
		if ( 'fb_display_settings' == $active_tab ) {
			require FACEBOOK_FEED_EZ_PLUGIN_DIR . '/templates/admin_display_settings.php';
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
function facebook_feed_ez_verify_token($args) {

	if (isset($_POST['fb-feed-nonce']) && wp_verify_nonce($_POST['fb-feed-nonce'], 'facebook-feed-ez') && isset($_POST['fb-access-token'])) {

		

		$app_id = esc_html(get_option('facebook_feed_ez_app_id'));
		$app_secret = esc_html(get_option('facebook_feed_ez_app_secret'));
		$user_token = esc_html($_POST['fb-access-token']);



		$fb = new \Facebook\Facebook([
			'app_id' => $app_id,           //Replace {your-app-id} with your app ID '414647516265304'
			'app_secret' => $app_secret,   //Replace {your-app-secret} with your app secret 'c1d911ec827f870cd29b90c789814960'
			'graph_api_version' => 'v9.0',
			'default_access_token' => $user_token,
			'auth_type' => 'reauthorize',
		]);

		

		try {
			// Returns a `FacebookFacebookResponse` object
			$response = $fb->get(
				'/oauth/access_token?grant_type=fb_exchange_token&fb_exchange_token=' . $user_token . '&client_id=' . $app_id . '&client_secret=' . $app_secret
			);
		} catch (FacebookExceptionsFacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			wp_die();
		} catch (FacebookExceptionsFacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			wp_die();
		}

		$graphNode = $response->getGraphNode();

		echo $graphNode;

		wp_die();
	}
}
