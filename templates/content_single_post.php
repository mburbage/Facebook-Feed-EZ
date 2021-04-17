<div id="<? echo $post['id']; ?>" class="post-wrapper">
	<div class="post-inner <? echo $post['full_picture'] ? 'has-image' : 'no-image'; ?>" style="background: center / cover no-repeat url(<? echo $post['full_picture']; ?>) ;">
		<div class="post-header">
			<div class="post-icon"><img src="<? echo $profile_pic; ?>" /></div>
			<div class="post-name">
				<? echo $post['from']['name'];?>
			</div>
		</div>
	</div>
	<div class="post-footer">
		<div class="post-message">
			<? echo $post['message'];?>
		</div>
		<div class="post-engagements">
			<div class="post-like <? echo $post['likes'] ? '' : 'hide'; ?>">
				<a href="<? echo $post['permalink_url']; ?>" target="_blank">
					<i class="far fa-thumbs-up"></i>
					<div class="like-count">
						<? echo $post['likes'] ? $post['likes']['count'] : ''; ?>
					</div>
				</a>
			</div>
			<div class="post-comment <? echo $post['comments'] ? '' : 'hide'; ?>">
				<a href="<? echo $post['permalink_url']; ?>" target="_blank">
					<i class="far fa-comment"></i>
					<div class="comment-count">
						<? echo $post['comments'] ? $post['comments']['counts'] : ''; ?>
					</div>
				</a>
			</div>
			<div class="post-share <? echo $post['shares'] ? '' : 'hide'; ?>">
				<a href="<? echo $post['permalink_url']; ?>" target="_blank">
					<i class="far fa-share"></i>
					<div class="share-count">
						<? echo $post['shares'] ? $post['shares']['count'] : ''; ?>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>
