<?php get_header(); ?>
<div id="content" class="author wrapper" role="main">
	<section id="main" class="inner alignleft">
		<?php the_post(); ?>
		<header class="pagetitle">
			<h1>Posts by <?php the_author(); ?></h1>
		</header>
		<?php while (have_posts()) : the_post(); ?>
			<?php get_template_part('article', 'excerpt'); ?>
		<?php endwhile; ?>
		<?php tp_paging(); ?>
	</section>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>