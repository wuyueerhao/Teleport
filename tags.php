<?php /* Template Name: Tags */ ?>
<?php get_header(); ?>
<div id="content" class="tags wrapper" role="main">
	<section id="main" class="inner alignleft">
		<header class="pagetitle"><h1>All Tags</h1></header>
		<article class="tags post">
			<section class="content">
				<?php wp_tag_cloud('smallest=13&number=&unit=px'); ?>
			</section>
		</article>
	</section>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
