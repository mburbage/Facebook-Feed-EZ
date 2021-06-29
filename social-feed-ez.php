<?php
/**
 * Plugin Name:         Social Feed Ez
 * Plugin URI:          https://services.redcircle.biz/social-feed-ez
 * Description:         A simple tool to load your Facebook feed on your site.
 * Author:              Red Circle
 * Author URI:          https://services.redcircle.biz/
 *
 * Version:             0.1.0
 * Requires at least:   3.8.0
 * Requires PHP:        5.2
 *
 * License:             GPL v3
 *
 * Text Domain:         social-feed-ez
 * Domain Path:         /languages
 *
 * Social Feed Ez
 * Copyright (C) 2008-2018, Red Circle, support@redcircle.biz
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category            Plugin
 * @copyright           Copyright © 2018 Michael Burbage
 * @author              Michael Burbage
 * @package             Red Circle
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

require_once __DIR__ . '/vendors/autoload.php';

if (!defined('SOCIAL_FEED_EZ_PLUGIN_DIR')) {
	define('SOCIAL_FEED_EZ_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

require_once SOCIAL_FEED_EZ_PLUGIN_DIR . 'includes/admin.php';

add_shortcode('social_feed_ez', 'social_feed_ez_init');

add_action('wp_enqueue_scripts', 'social_feed_ez_scripts');



function social_feed_ez_scripts() {

	wp_enqueue_script('social_feed_ez_feed', plugins_url('/assets/js/feed.js', __FILE__));
	wp_enqueue_style('social_feed_ez_feed_css', plugins_url('/assets/css/feed.css', __FILE__));
	wp_enqueue_style('social_feed_ez_fa_css', plugins_url('/assets/css/fontawesome.min.css', __FILE__));
	wp_enqueue_style('social_feed_ez_fr_css', plugins_url('/assets/css/regular.min.css', __FILE__));
}

function social_feed_ez_init() {

	$app_id = esc_html(get_option('social_feed_ez_app_id'));
	$app_secret = esc_html(get_option('social_feed_ez_app_secret'));
	//$page_id = esc_html(get_option('social_feed_ez_page_id'));
	$ll_token = esc_html(get_option('social_feed_ez_ll_access_token'));
	$page_id = esc_html(get_option('social_feed_ez_page_id'));

	$fb = new \Facebook\Facebook([
		'app_id' => $app_id,
		'app_secret' => $app_secret,
		'graph_api_version' => 'v9.0',
		'default_access_token' => $user_token,
	]);

	try {
		// Returns a `FacebookFacebookResponse` object
		if ($page_id == 'me') {
			$response = $fb->get(
				'/' . $page_id . '/feed?fields=full_picture,id,created_time,height,icon,message,message_tags,picture,place,shares,sharedposts,comments,admin_creator,from,permalink_url,description&limit=6',
				$ll_token
			);
		} else {
			$response = $fb->get(
				'/' . $page_id . '/feed?fields=full_picture,id,created_time,height,icon,message,message_tags,picture,place,shares,sharedposts,comments,likes,admin_creator,from,permalink_url,description&limit=6',
				$ll_token
			);
		}
	} catch (\Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch (\Facebook\Exceptions\FacebookSDKException $e) {
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	$graphNode = $response->getGraphEdge();

	return social_feed_ez_display($graphNode);
}

/**
 * 
 * 
 */
function social_feed_ez_display($results) {

	if (!is_admin()) {

		$profile_pic = social_feed_ez_user_picture();

		// Start output buffering
		ob_start();

		echo '<div class="social-feed-ez-wrapper ' . get_option('social_feed_display_type') . '">';

		foreach ($results as &$post) {

			include SOCIAL_FEED_EZ_PLUGIN_DIR . '/templates/content_single_post.php';
		}

		echo '</div>';

		// Capture contents of buffer in a variable
		$content = ob_get_contents();

		// End buffering
		ob_end_clean();

		// Return your menu and bask in the warming glow of working code
		return $content;
	}
}

/**
 * 
 * 
 */
function social_feed_ez_user_picture() {

	//
	$app_id = esc_html(get_option('social_feed_ez_app_id'));
	$app_secret = esc_html(get_option('social_feed_ez_app_secret'));
	//$page_id = esc_html(get_option('social_feed_ez_page_id'));
	$ll_token = esc_html(get_option('social_feed_ez_ll_access_token'));
	$page_id = esc_html(get_option('social_feed_ez_page_id'));

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
		echo 'Graph returned an error: ' . esc_html($e->getMessage());
		exit;
	} catch (FacebookExceptionsFacebookSDKException $e) {
		echo 'Facebook SDK returned an error: ' . esc_html($e->getMessage());
		exit;
	}

	$graphNode = $response->getGraphNode();

	return $graphNode['url'];
}
