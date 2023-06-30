<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

require_once dirname(__DIR__) . '/vendors/autoload.php'; // change path as needed

if ('' == sanitize_text_field(get_option('social_feed_ez_app_id')) || '' == sanitize_text_field(get_option('social_feed_ez_app_secret'))) {
	wp_die('Error: Missing Facebook App ID or App Secret. Return to settings and enter your app ID and Secret', 'Error: Missing Facebook App ID or App Secret');
}

$app_id = sanitize_text_field(get_option('social_feed_ez_app_id'));
$app_secret = sanitize_text_field(get_option('social_feed_ez_app_secret'));
$ll_token = sanitize_text_field(get_option('social_feed_ez_ll_access_token'));
$page_id = sanitize_text_field(get_option('social_feed_ez_page_id'));

$fb = new \Facebook\Facebook([
	'app_id' => $app_id,
	'app_secret' => $app_secret,
	'graph_api_version' => 'v17.0',
	'default_graph_version' => 'v10.0',
]);

$helper = $fb->getRedirectLoginHelper();

if (isset($_GET['state'])) {
	$helper->getPersistentDataHandler()->set('state', $_GET['state']);
}

try {
	$accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
	// When Graph returns an error
	echo 'Graph returned an error callback: ' . $e->getMessage();
	exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
	// When validation fails or other local issues
	echo 'Facebook SDK returned an error callback: ' . $e->getMessage();
	exit;
}

if (!isset($accessToken)) {
	if ($helper->getError()) {
		header('HTTP/1.0 401 Unauthorized');
		echo "Error: " . $helper->getError() . "\n";
		echo "Error Code: " . $helper->getErrorCode() . "\n";
		echo "Error Reason: " . $helper->getErrorReason() . "\n";
		echo "Error Description: " . $helper->getErrorDescription() . "\n";
	} else {
		header('HTTP/1.0 400 Bad Request');
		echo 'Bad request';
	}
	exit;
}

// Logged in


// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);


$user_id = $tokenMetadata->getUserId();


// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId($app_id);
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (!$accessToken->isLongLived()) {
	// Exchanges a short-lived access token for a long-lived one
	try {
		$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
	} catch (Facebook\Exceptions\FacebookSDKException $e) {
		echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
		exit;
	}

	// echo '<h3>Long-lived</h3>';
	// var_dump($accessToken->getValue());
}


////////////////////////

$fb = new \Facebook\Facebook([
	'app_id' => $app_id,
	'app_secret' => $app_secret,
	'graph_api_version' => 'v17.0',
	'default_graph_version' => 'v10.0',
	'default_access_token' => $accessToken->getValue(),
]);

try {
	// Returns a `FacebookFacebookResponse` object
	// ?fields=instagram_business_account,name,id,picture

	$response = $fb->get('/' . $user_id . '/accounts?fields=instagram_business_account,name,id', $accessToken->getValue());
} catch (\Facebook\Exceptions\FacebookResponseException $e) {
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
} catch (\Facebook\Exceptions\FacebookSDKException $e) {
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
}

$graphNode = $response->getGraphEdge();



//session_unset();

// echo '<pre>';
// var_dump($graphNode);
// echo '</pre>';

$_SESSION['fb_access_token'] = $accessToken->getValue();

$_SESSION['fb_callback_results'] = $graphNode;

// echo '<pre>';
// echo $_SESSION['fb_callback_results'];
// echo $_SESSION['fb_access_token'];
// echo '</pre>';
// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
header('Location:' . site_url() . '/wp-admin/options-general.php?page=social_feed_ez&tab=social_feed_profiles');

exit();
