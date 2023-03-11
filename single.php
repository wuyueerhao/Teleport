<?php
the_post();
if (post_password_required()) {
  $description = 'This post is password protected. 这是一篇受密码保护的文章。 この投稿はパスワードで保護されています.';
} else {
  $description = $post->post_excerpt;
}
?>
<?php get_header(); ?>
<div id="content" class="single wrapper" role="main">
	<section id="main" class="inner alignleft">
		<?php tp_the_breadcrumbs(' › ', true); ?>
		<?php get_template_part('article', ''); ?>
		<?php comments_template('', true); ?>
	</section>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
