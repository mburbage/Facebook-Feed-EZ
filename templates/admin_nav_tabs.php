<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

<h2 class="nav-tab-wrapper">
	<a href="?page=<?php echo sanitize_text_field($_GET['page']); ?>&tab=fb_page_settings" class="nav-tab <?php echo $active_tab == 'fb_page_settings' ? 'nav-tab-active' : ''; ?>">Facebook Page Feed</a>
	<a href="?page=<?php echo sanitize_text_field($_GET['page']); ?>&tab=fb_token_settings" class="nav-tab <?php echo $active_tab == 'fb_token_settings' ? 'nav-tab-active' : ''; ?>">Facebook Access Tokens</a>
	<a href="?page=<?php echo sanitize_text_field($_GET['page']); ?>&tab=fb_display_settings" class="nav-tab <?php echo $active_tab == 'fb_display_settings' ? 'nav-tab-active' : ''; ?>">Facebook Feed Display</a>
	<a href="?page=<?php echo sanitize_text_field($_GET['page']); ?>&tab=social_feed_login" class="nav-tab <?php echo $active_tab == 'social_feed_login' ? 'nav-tab-active' : ''; ?>">Facebook Feed Login</a>
	<a href="?page=<?php echo sanitize_text_field($_GET['page']); ?>&tab=social_feed_profiles" class="nav-tab <?php echo $active_tab == 'social_feed_profiles' ? 'nav-tab-active' : ''; ?>">Facebook Feed Profiles</a>

</h2>
