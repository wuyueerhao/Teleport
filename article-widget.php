<?php 	
	global $smof_data;
	global $beauty_show_photo;
	global $beauty_show_comments;
	// title	
	$current_post['title'] = get_the_title();	
	if ( empty( $current_post['title'] ) ) {
		$current_post['title'] = __( 'Untitled Article', 'beauty_dictionary' );
	}	
	
	// thumbnail	
	if ( $beauty_show_photo ) {
		$current_post['show_photo'] = true;
		$current_post['thumbnail'] 	= beauty_widget_post_featured_image();
	}
	else {
		$current_post['show_photo'] = false;
	}	
	// comments	
	if ( $beauty_show_comments ) {
		$current_post['show_comments'] = true;
	}
	else {
		$current_post['show_comments'] = false;
	}	
	// extra classes	
	$extra_css_class = '';
	if ( ! $beauty_show_photo ) {
		$extra_css_class = ' no_thumbnail';
	}	
?>
<li>
	<?php if ( $current_post['show_photo'] ) : ?>
	<div class="post_thumbnail">
		<?php echo $current_post['thumbnail']; ?>
	</div>
	<?php endif; ?>
	<div class="post_details">
		<div class="post_title">
			<a href="<?php echo get_permalink(); ?>" title="<?php _e( "Permalink to", "beauty_dictionary" ); ?>-<?php echo $current_post['title']; ?>" rel="bookmark" class="inherit-color accentcolor-text-on_hover">
				<?php echo $current_post['title']; ?>
			</a>
		</div>
		<div class="post_meta">
			<?php the_time('Y-n-d'); ?>
			| 
			<span class="inherit-color-on_children accentcolor-text-on_children-on_hover">
				<?php comments_popup_link(__('No comment', 'beauty_dictionary'), __('1 Comment', 'beauty_dictionary'), __('% Comments', 'beauty_dictionary')); ?>
			</span>
		</div>
	</div>
</li>
