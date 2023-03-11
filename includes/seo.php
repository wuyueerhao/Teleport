<?php if ( is_home() ) : ?><title><?php bloginfo('name'); ?> | <?php bloginfo('description'); ?></title>
<?php elseif ( is_search() ) : ?><title><?php echo $_GET['s']; ?>的搜索结果 | <?php bloginfo('name'); ?></title>
<?php elseif ( is_single() ) : ?><title><?php echo trim(wp_title('', false)); ?> | <?php bloginfo('name'); ?></title>
<?php elseif ( is_page() ) : ?><title><?php echo trim(wp_title('', false)); ?> | <?php bloginfo('name'); ?></title>
<?php elseif ( is_category() ): ?><title><?php single_cat_title(); ?> | <?php bloginfo('name'); ?></title>
<?php elseif ( is_year() ) : ?><title><?php the_time('Y年'); ?>发布的内容 | <?php bloginfo('name'); ?></title>
<?php elseif ( is_month() ) : ?><title><?php the_time('Y年n月'); ?>发布的内容 | <?php bloginfo('name'); ?></title>
<?php elseif ( is_day() ) : ?><title><?php the_time('Y年n月j日'); ?>发布的内容 | <?php bloginfo('name'); ?></title>
<?php elseif ( is_tag() ) : ?><title><?php single_tag_title("", true); ?> | <?php bloginfo('name'); ?></title>
<?php elseif ( is_author() ):?><title><?php wp_title(''); ?>发布的所有内容 | <?php bloginfo('name'); ?></title>
<?php endif;?>
<?php 
	$keywords = beauty_options('keyword');
	$keywords = $keywords ? $keywords : get_bloginfo('name');
	$description = beauty_options('description');
	$description = $description ? $description : get_bloginfo('description');
?>
<?php if(is_single()):?>
<?php 
	$keywords = strip_tags( get_the_tag_list( '',',','') );
	$post = get_post();
	if ($post->post_excerpt) {
		$post_desc = trim( strip_tags( $post->post_excerpt ) );
		$description = beauty_substrUtf8( $post_desc, 420, '' );
	} else {
		$description = $post->post_title;
	}
?>
<?php elseif (is_category() ): ?>
<?php 
	$keywords =  single_cat_title( '', false ) ;
	$cate_desc = category_description();
	$description = $cate_desc ? strip_tags( category_description() ) . ',' . $description : $description;
?>
<?php elseif (is_tag() ): ?>
<?php 
	$keywords = single_tag_title( '', false ) ;
?>
<?php elseif (is_page() ): ?>
<?php 
	$p_title = trim ( wp_title('', false) );
	$keywords = "{$p_title}";
?>
<?php endif; ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<meta name="description" content="<?php echo trim($description); ?>" />
