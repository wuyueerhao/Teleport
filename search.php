<?php get_header(); ?>
<div id="content" class="search wrapper" role="main">
	<section id="main" class="inner alignleft">
		<header class="pagetitle">
			<h1>Search Results for: "<?php echo get_search_query(); ?>"</h1>
		</header>
		<?php if ( have_posts() && strlen( trim(get_search_query()) ) != 0 ) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<?php get_template_part('article'); ?>
		<?php endwhile; ?>
		<?php tp_paging(); ?>
		<?php else : ?>
		<div style="margin:0px auto;">	
			<div class="beauty_heading">
				<div class="primary">
					<?php _e("No results <strong>found</strong>", "beauty_dictionary" ); ?>
				</div>
				<div class="secondary">
					<?php _e("Please try again by refining your search!", 'beauty_dictionary'); ?>
				</div>
			</div>			
			<div class="wpb_content_element">
				<?php get_search_form(); ?>
			</div>
		</div>
		<?php endif; ?>
	</section>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
