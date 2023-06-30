<div id="<? echo esc_html($output->id); ?>" class="post-wrapper">
	<a href="<?php echo $output->permalink; ?>" target="_blank">
		<div class="post-inner <? echo esc_html($output->media_url) ? 'has-image' : 'no-image'; ?>" style="background: center / cover no-repeat url(<? echo esc_html($output->media_url); ?>) ;">
			<div class="post-header">
				<div class="post-icon"><img src="<? echo esc_html($profile_pic); ?>" title="@<? echo esc_html($output->username); ?>" style="visibility:hidden;" />
					<div class="post-name">

					</div>
				</div>

			</div>
		</div>
	</a>
</div>