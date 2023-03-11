<section id="sidebar" class="alignright inner">
<?php if ( is_active_sidebar( 'main_sidebar' ) ) : ?>
	<?php  dynamic_sidebar('main_sidebar'); ?>
<?php else : ?>	
	<div class="widget widget-recent-posts">
		<header class="title">Recent Posts</header>
		<ul>
			<?php tp_recent_posts(10); ?>
		</ul>
	</div>
	<div class="widget widget-recent-comments">
		<header class="title">Recent Comments</header>
		<ul>
			<?php tp_recent_comments(6); ?>
		</ul>
	</div>
	<div class="widget widget-hot-tags">
		<header class="title">Hot Tags</header>
		<div class="st-tag-cloud">
			<?php wp_tag_cloud('smallest=11&largest=16&number=25&unit=px'); ?>
		</div>
		<div class="more">
			<a class="view-more" href="<?php echo home_url("tags"); ?>">More Tags »</a>
		</div>
	</div>    
	<div class="widget widget-ccl">
		<header class="title">License</header>
		<div class="cclicense">
			This work is licensed under a <a href="//creativecommons.org/licenses/by-nc-sa/4.0/" rel="nofollow external" target="_blank">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 Unported License</a>.
		</div>
	</div>

<?php endif; ?>	
</section>

