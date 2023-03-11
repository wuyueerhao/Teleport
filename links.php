<?php /* Template Name: Links */ ?>
<?php get_header(); ?>
<div id="content" class="links wrapper" role="main">
	<section id="main" class="inner alignleft">
		<header class="pagetitle">
			<h1>Links</h1>
		</header>
		<article class="post ">
			<section class="content">

				<?php echo get_link_items(); ?>	
			</section>
		</article>
		<?php comments_template('', true); ?>
	</section>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
