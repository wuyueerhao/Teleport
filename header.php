<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?> itemscope itemtype="http://schema.org/WebPage">
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta property="wb:webmaster" content="f952588369deaeee" />
	<meta name="baidu-site-verification" content="zeBn2JteKL" />
	<meta name="viewport" content="width=device-width"/><!-- for mobile -->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/assets/images/favicon.ico" />
	<!-- open graph protocl -->
	<?php include('includes/seo.php'); ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
	<link rel='canonical' href="<?php the_permalink() ?>" />	
	<script src="<?php bloginfo('template_url'); ?>/assets/js/jquery.min.js"></script>
	<?php if (is_mobile() ): ?>				
		<?php beauty_scroll_top_link(); ?>
		<script src="<?php bloginfo('template_url'); ?>/assets/js/mobile.js"></script>	
	<?php else :?>
		<script src="<?php bloginfo('template_url'); ?>/assets/js/scrooltop.js"></script>	
	<?php endif ;?>	
	<?php wp_head(); ?>	
</head>
<body <?php body_class(); ?>>
<?php get_theme_header();?>	