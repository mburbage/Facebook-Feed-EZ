<div id="<? echo esc_html($output->id); ?>" class="post-wrapper">
	<a href="<?php echo esc_html($output->permalink); ?>" target="_blank">
		<div class="post-inner <? echo esc_html($output->media_url) ? 'has-image' : 'no-image'; ?>" style="background: center / cover no-repeat url(<? echo esc_html($output->media_url); ?>) ;">
			<div class="post-header">
				<div class="post-icon"><img src="<? echo esc_html($profile_pic); ?>" title="@<? echo esc_html($output->username); ?>" />
					<div class="post-name">

					</div>
				</div>

			</div>
		</div>
		<div class="post-footer">
		<div class="post-message">
			<? echo esc_html($output['message']) ? esc_html($output['message']) : esc_html($output['description']);
			?>
		</div>
		<div class="post-engagements">
			<div class="post-like <? echo esc_html($output['likes']) ? '' : 'hide'; 
									?>">
				<a href="<? echo esc_url($output['permalink_url']); 
							?>" target="_blank">
					<i class="far fa-thumbs-up"></i>
					<div class="like-count">
						<? echo esc_html($output['likes']) ? esc_html($output['likes']['count']) : ''; 
						?>
					</div>
				</a>
			</div>
			<div class="post-comment <? echo esc_html($output['comments']) ? '' : 'hide'; 
										?>">
				<a href="<? echo esc_url($output['permalink_url']); 
							?>" target="_blank">
					<i class="far fa-comment"></i>
					<div class="comment-count">
						<? echo esc_html($output['comments']) ? esc_html($output['comments']['counts']) : ''; 
						?>
					</div>
				</a>
			</div>
			<div class="post-share <? echo esc_html($output['shares']) ? '' : 'hide'; 
									?>">
				<a href="<? echo esc_url($output['permalink_url']); 
							?>" target="_blank">
					<i class="far fa-share"></i>
					<div class="share-count">
						<? echo esc_html($output['shares']) ? esc_html($output['shares']['count']) : ''; 
						?>
					</div>
				</a>
			</div>
			<div class="post-open <? echo esc_url($output['permalink_url']) ? '' : 'hide'; 
									?>">
				<a href="<? echo esc_url($output['permalink_url']); 
							?>" target="_blank">
				<i class="far fa-arrow-alt-circle-right"></i>
				</a>
			</div>
		</div>
	</div>
	</a>
</div>