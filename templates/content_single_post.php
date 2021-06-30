<div id="<? echo esc_html($post['id']); ?>" class="post-wrapper">
	<div class="post-inner <? echo esc_html($post['full_picture']) ? 'has-image' : 'no-image'; ?>" style="background: center / cover no-repeat url(<? echo esc_html($post['full_picture']); ?>) ;">
		<div class="post-header">
			<div class="post-icon"><img src="<? echo esc_html($profile_pic); ?>" /></div>
			<div class="post-name">
				<? echo esc_html($post['from']['name']);?>
			</div>
		</div>
	</div>
	<div class="post-footer">
		<div class="post-message">
			<? echo esc_html($post['message']) ? esc_html($post['message']) : esc_html($post['description']);?>
		</div>
		<div class="post-engagements">
			<div class="post-like <? echo esc_html($post['likes']) ? '' : 'hide'; ?>">
				<a href="<? echo esc_html($post['permalink_url']); ?>" target="_blank">
					<i class="far fa-thumbs-up"></i>
					<div class="like-count">
						<? echo esc_html($post['likes']) ? esc_html($post['likes']['count']) : ''; ?>
					</div>
				</a>
			</div>
			<div class="post-comment <? echo esc_html($post['comments']) ? '' : 'hide'; ?>">
				<a href="<? echo esc_html($post['permalink_url']); ?>" target="_blank">
					<i class="far fa-comment"></i>
					<div class="comment-count">
						<? echo esc_html($post['comments']) ? esc_html($post['comments']['counts']) : ''; ?>
					</div>
				</a>
			</div>
			<div class="post-share <? esc_html(echo $post['shares']) ? '' : 'hide'; ?>">
				<a href="<? echo esc_html($post['permalink_url']); ?>" target="_blank">
					<i class="far fa-share"></i>
					<div class="share-count">
						<? echo esc_html($post['shares']) ? esc_html($post['shares']['count']) : ''; ?>
					</div>
				</a>
			</div>
			<div class="post-open <? echo esc_url($post['permalink_url']) ? '' : 'hide'; ?>">
				<a href="<? echo esc_url($post['permalink_url']); ?>" target="_blank">
				<i class="far fa-arrow-alt-circle-right"></i>
				</a>
			</div>
		</div>
	</div>
</div>
