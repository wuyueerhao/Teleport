<?php /* Template Name: Categories */ ?>
<?php get_header(); ?>
<div id="content" class="archives wrapper" role="main">
	<section id="main" class="inner alignleft">
		<header class="pagetitle"><h1>All Categories</h1></header>
		<article class="categories post">
			<section class="content">
				<ul><?php wp_list_categories('show_count=1&hide_empty=0&title_li='); ?></ul>
			</section>
		</article>
	</section>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>

