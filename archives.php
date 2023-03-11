<?php /* Template Name: Archives */ ?>
<?php get_header(); ?>
<div id="content" class="archives wrapper" role="main">
	<section id="main" class="inner alignleft">
		<header class="pagetitle"><h1>Archives</h1></header>
		<article class="post">
			<section class="content">
				<?php tp_get_archives(); ?>
			</section>
		</article>
	</section>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
