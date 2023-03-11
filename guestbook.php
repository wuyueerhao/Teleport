<?php /* Template Name: Guestbook */ ?>
<?php get_header(); ?>
<div id="content" class="guestbook wrapper" role="main">
	<section id="main" class="inner alignleft">
		<header class="pagetitle">
			<h1><?php the_title(); ?></h1>
		</header>
		<article class="post ">
			<section class="content">
				<?php echo do_shortcode('[active num=15 size=100 days=30]');?>
				<?php the_post(); ?>
				<?php the_content(); ?>
			</section>
		</article>
		<?php comments_template('', true); ?>
	</section>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>