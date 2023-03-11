<?php get_header(); ?>
<div id="content" class="wrapper" role="main" itemscope itemtype="http://schema.org/Person">
	<section id="main" class="inner alignleft">
		<?php while (have_posts()) : the_post(); ?>
			<?php get_template_part('article'); ?>
		<?php endwhile; ?>
		<?php tp_paging(); ?>
	</section>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
