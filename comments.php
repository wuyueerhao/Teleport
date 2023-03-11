<?php
  global $comment_count; 
  $_comment = $comments_by_type['comment'];
  $comment_count = count($_comment);
  $_trackbacks = $comments_by_type['pings'];
  $trackback_count = count($_trackbacks);
?>
<?php if (!post_password_required()) : ?>
<div id="comments">
	<div class="comments-tab">
		<span class="tab alignleft active">Comments <small>( <?php echo $comment_count ?> )</small></span>
		<?php if (pings_open()) : ?>
		<span class="tab alignleft">Trackbacks <small>( <?php echo $trackback_count ?> )</small></span>
		<?php endif; ?>
		<?php if (comments_open()) : ?>
		<em class="alignright"><a href="#respond" rel="nofollow">Leave a Reply</a></em>
		<?php endif; ?>
	</div>
	<div id="loading-comments"></div>	
	<div class="commentshow showlist">
		<ol class="commentlist">
		<?php if (!comments_open()) : ?>
			<li class="even">
				<p>Comments are closed.</p>
				<p>评论已关闭</p>
				<p>コメントは受け付けていません。</p>
			</li>
		<?php elseif ($comment_count == 0) : ?>
			<li class="even">
				<p>No comments yet.</p>
				<p>目前尚无任何评论.</p>
				<p>コメントはまだありません。</p>
			</li>
		<?php endif; ?>
		<?php wp_list_comments('callback=tp_single_comment&max_depth=10000'); ?>		
		</ol>	
		<nav id="navigation" class="navigation" role="navigation">
			<?php paginate_comments_links('prev_text=Prev&next_text=Next');?>
		</nav>
	</div>		
	<div class="trackshow showlist hide">
		<?php if (pings_open()) : ?>
		<ol class="trackbacks ">
			<li class="trackbackurl">
				<label for="trackback-url">TrackBack URL</label>
				<input type="text" id="trackback-url" name="trackback-url" class="w380" value="<?php trackback_url(); ?>" readonly onclick="this.select()" />
			</li>
			<?php if($trackback_count != 0) : ?>
				<?php wp_list_comments(array('callback' => 'tp_single_trackback'), $_trackbacks); ?>
			<?php else : ?>
			<li class="odd">
				<p>No trackbacks yet.</p>
				<p>目前尚无任何 trackbacks 和 pingbacks.</p>
				<p>トラックバックはまだありません。</p>
			</li>
			<?php endif; ?>
		</ol>
		<?php endif; ?>
	</div>		
	<?php if (comments_open()) : ?>
	<section id="respond" class="post_comments">	
		<h3 id="reply-title" class="comment-reply-title">
			<?php comment_form_title( 'Leave a Comment  ', 'Reply to %s' ); ?> 
			<small><?php cancel_comment_reply_link(); ?></small>		
		</h3>
		<form id="commentform" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">
			<?php if (is_user_logged_in()): ?>
			<div>Logged in as 
				<a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php" rel="nofollow">
					<strong><?php echo $user_identity; ?></strong>
				</a>. 
				<a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account" rel="nofollow">
					Logout &raquo;
				</a>
			</div>
			<?php else : ?>
			<?php if ($comment_author != "") : ?>
			<div id="welcome-back">
				Welcome back <strong><?php echo $comment_author; ?></strong>. 
				<a id="rChange" href="javascript:void(0)" rel="nofollow">Change &raquo;</a>
				<a id="rClose" href="javascript:void(0)" rel="nofollow" style="display: none">Close &raquo;</a>
			</div>
			<div id="author-info" style="display: none">
			<?php else : ?>
			<div id="author-info">
			<?php endif; ?>
				<div>
					<input type="text" id="author" name="author" size="24" tabindex="1" class="w220" value="<?php echo $comment_author; ?>" required />
					<label for="author">Name (required)</label>
				</div>
				<div>
					<input type="email" id="email" name="email" size="24" tabindex="2" class="w220" value="<?php echo $comment_author_email; ?>" required />
					<label for="email">Mail (will not be published or shared) (required)</label>
				</div>
				<div>
					<input type="url" id="url" name="url" size="24" tabindex="3" class="w220" value="<?php echo $comment_author_url; ?>" />
					<label for="url">Website</label>
				</div>
			</div>
			<?php endif; ?>
			<?php if( beauty_options('is_comment_smilies') == 'Y' ) { ?>	
			<div class="smilies">
				<ul class="kaomoji clearfix">
					<li title="不是我">ㄟ( ▔, ▔ )ㄏ</li>
					<li title="掀桌">o(*≧▽≦)ツ┏━┓</li>
					<li title="啦啦">♪(´ε｀ )</li>
					<li title="吼吼">ψ(｀∇´)ψ</li>
					<li title="汗">(－_－＃)</li>
					<li title="啊啊啊">Ｏ(≧口≦)Ｏ</li>
					<li title="无奈">┑(￣Д ￣)┍</li>
					<li title="赞一个">ᕕ╏ ͡ᵔ ‸ ͡ᵔ ╏凸</li>
					<li title="震惊">ᕦʕ ° o ° ʔᕤ</li>
					<li title="惊呆">⋋། ⊙ _̀ ⊙ །⋌</li>
					<li title="加油">(ง •_•)ง</li>					
					<li title="开心">ᕕ( ՞ ᗜ ՞ )ᕗ</li>
					<li title="抖">o((⊙﹏⊙))o.</li>
					<li title="啦啦啦">ヾ(≧▽≦*)o </li>					
					<li title="困了">(￣o￣) . z Z</li>
					<li title="那个">(．． )…</li>
					<li title="跑">ε = = (づ′▽`)づ</li>
					<li title="哈哈">ᕕ( ՞ ᗜ ՞ )ᕗ</li>
					<li title="难过">(T_T)</li>
					<li title="安慰">( T_T)＼(^-^ )</li>
					<li title="一边去">……o((≥▽≤o)</li>
				</ul> 
			</div>
			<?php } ?>	
			<div>
				<textarea id="comment" name="comment" tabindex="4" rows="8" cols="80" name="comment" required></textarea>
			</div>
			<div class="form-submit">
				<input name="submit" type="submit" id="submit" class="btn-black" tabindex="5" value="Submit Comment" />
			</div>
			<?php comment_id_fields(); ?>
			<?php do_action('comment_form', $post->ID); ?>		
			<input type="hidden" name="comment_post_ID" value="<?php the_ID() ?>" />
			<?php wp_nonce_field('akismet_comment_nonce_' . get_the_ID(), 'akismet_comment_nonce', FALSE); ?>
		</form>	
	</section>
	<?php endif; ?>
<?php else : ?>
	<div class="msgbox">
		<p>This post is password protected. Enter the password to view any comments.</p>
		<p>本文受密码保护。要查看评论，请输入密码。</p>
		<p>この投稿はパスワードで保護されています。コメントを閲覧するにはパスワードを入力してください。</p>
	</div>
</div>
<?php endif; ?>
