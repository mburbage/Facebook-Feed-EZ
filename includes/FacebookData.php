<?php

class FacebookData {

    public $test_var = 'test var';

    public function FacebookProfilePic($page_id) {
        //
		$app_id = sanitize_text_field(get_option('social_feed_ez_app_id'));
		$app_secret = sanitize_text_field(get_option('social_feed_ez_app_secret'));
		$ll_token = sanitize_text_field(get_option('social_feed_ez_ll_access_token'));

		$ch = curl_init();

		// set url 
		curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v12.0/' . $page_id . '/picture?redirect=0&access_token=' . $ll_token);

		//return the transfer as a string 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// $output contains the output string 
		$output = json_decode(curl_exec($ch));

		// close curl resource to free up system resources 
		curl_close($ch);

		return $output->data->url;
        

    }
    public function InstagramProfilePic($page_id) {
        //
		$app_id = sanitize_text_field(get_option('social_feed_ez_app_id'));
		$app_secret = sanitize_text_field(get_option('social_feed_ez_app_secret'));
		$ll_token = sanitize_text_field(get_option('social_feed_ez_ll_access_token'));

		$ch = curl_init();

		// set url 
		curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v12.0/' . $page_id . '/?fields=name,username,profile_picture_url,website&access_token=' . $ll_token);

		//return the transfer as a string 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// $output contains the output string 
		$output = json_decode(curl_exec($ch));

		// close curl resource to free up system resources 
		curl_close($ch);

		//return $output->profile_picture_url ? $output->profile_picture_url : plugins_url() . '/social-feed-ez/assets/img/no-profile-pic.jpg';
		return $output->profile_picture_url;

    }
}

?>
