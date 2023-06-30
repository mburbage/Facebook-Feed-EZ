<div class="page-wrapper">
    <div class="page-row page-<?php echo $value['instagram_business_account']['id'] ? $value['instagram_business_account']['id'] : $value['id'];?>">    
		<?php 

        $ig_page_id = $value['instagram_business_account']['id'] ? $value['instagram_business_account']['id'] : '';
        $ig_page_id_disabled = $value['instagram_business_account']['id'] ? '' : 'disabled';

        $fb_page_id = $value['id'];

        $get_profile_pic = '' != $profile_url ? $profile_url : plugins_url() . '/social-feed-ez/assets/img/no-profile-pic.jpg';

        

        echo '<div class="page-item page-name">';
        echo '<img src="' . esc_html($get_profile_pic) . '" class="pages-profile-pic" />';
        echo esc_html($value['name']);
        //echo $value['instagram_business_account']['id'] ? ' Instagram: ' : ' Facebook: ';
        echo '</div>';
        echo '<div class="page-item ig-page-name">';
		echo 'Instagram: ';
        $checked = $options == $ig_page_id ? 'checked' : '';
        echo '<input type="radio" id="' . esc_html($ig_page_id) . '" name="page" value="' . esc_html($ig_page_id) . '" ' . esc_html($ig_page_id_disabled) . ' ' . esc_html($checked) . '>';
        //echo $value['instagram_business_account']['id'] ? $value['instagram_business_account']['id'] : 'Instagram Page Not Found';
        echo '</div>';
        echo '<div class="page-item fb-page-name">';
		echo 'Facebook: ';
        $checked = $options == $fb_page_id ? 'checked' : '';
        echo '<input type="radio" id="' . esc_html($fb_page_id) . '" name="page" value="' . esc_html($fb_page_id) . '" ' . esc_html($checked) . '>';
        echo '</div>'; 
        ?>
    </div>
</div>