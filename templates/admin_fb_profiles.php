<form action="options.php" method="post">
	<?php
	// output security fields for the registered setting "wporg"
	settings_fields('social_feed_profiles');
	// output setting sections and their fields
	// (sections are registered for "wporg", each field is registered to a specific section)
	do_settings_sections('social_feed_profiles');
	// output save settings button
	submit_button('Save Settings');

	?>
</form>

