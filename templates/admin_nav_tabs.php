<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

<h2 class="nav-tab-wrapper">
	<a href="?page=<?php echo $_GET['page']; ?>&tab=fb_page_settings" class="nav-tab <?php echo $active_tab == 'fb_page_settings' ? 'nav-tab-active' : ''; ?>">Facebook Page Feed</a>
	<a href="?page=<?php echo $_GET['page']; ?>&tab=fb_user_settings" class="nav-tab <?php echo $active_tab == 'fb_user_settings' ? 'nav-tab-active' : ''; ?>">Facebook User Feed</a>
	<a href="?page=<?php echo $_GET['page']; ?>&tab=fb_token_settings" class="nav-tab <?php echo $active_tab == 'fb_token_settings' ? 'nav-tab-active' : ''; ?>">Facebook Access Tokens</a>
	<a href="?page=<?php echo $_GET['page']; ?>&tab=fb_display_settings" class="nav-tab <?php echo $active_tab == 'fb_display_settings' ? 'nav-tab-active' : ''; ?>">Facebook Feed Display</a>

</h2>
