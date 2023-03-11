<?php get_header(); ?>
<div id="content" class="wrapper" role="main">
	<section id="main" class="inner alignleft">
		<header class="pagetitle"><h1><?php the_title(); ?></h1></header>
		<article class="post" itemscope itemtype="http://schema.org/Article">
			<section class="content" itemprop="articleBody">
				<?php the_content(); ?>
			</section>
		</article>
		<?php comments_template('', true); ?>
	</section>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
