<?php /* Template Name: About Me */ ?>
<?php the_post(); ?>
<?php get_header(); ?>
<div id="content" class="wrapper" role="main" itemscope itemtype="http://schema.org/Person">
	<section id="main" class="inner alignleft">
		<header class="pagetitle">
			<h1><?php the_title(); ?></h1>
		</header>
		<article class="post" itemscope itemtype="http://schema.org/Article">
			<section class="content" itemprop="articleBody">
				<?php the_content(); ?>
				<?php if( is_modified() ) : ?>
					<p style="color:#777;"><?php the_modified_time('Y年n月j日'); ?>持续更新，感谢您的阅读！</p>
				<?php endif;?>
			</section>
		</article>
		<?php comments_template('', true); ?>
	</section>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
