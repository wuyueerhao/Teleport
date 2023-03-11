<?php

//Functions
foreach( array( 'base', 'theme-options', 'thumb_resizer', 'latest-posts', 'popular-posts') as $name ) 
require( TEMPLATEPATH . '/includes/' . $name . '.php' );

// 主题参数
$beauty_options = get_option( 'beauty_options' );

//阻止站内文章互相 Pingback
function no_self_ping( &$links ) {
    $home = get_option( 'home' );
    foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, $home ) ) unset($links[$l]);
}
add_action( 'pre_ping', 'no_self_ping' );


// 重定向作者页面到about页面
/* add_filter('author_link', 'replace_author_url', 1000, 2);
function replace_author_url() {   
    return home_url('/about');
} */


// 评论添加@
function reply_comment_add_at( $commentdata ) {
	if( $commentdata['comment_parent'] > 0) {
		$commentdata['comment_content'] = '@<a href="#comment-' . $commentdata['comment_parent'] . '">'.get_comment_author( $commentdata['comment_parent'] ) . '</a> ' . $commentdata['comment_content'];
	}
	return $commentdata;
}
add_action( 'preprocess_comment' , 'reply_comment_add_at', 20);


//友情链接图标
global $links;
add_action("init","init_globals");
function init_globals(){
    global $links;
    $bookmarks = get_bookmarks();
    $links = array();
    if ( !empty($bookmarks) ) {
        foreach ($bookmarks as $bookmark) {
            $url = $bookmark->link_url;
            $url = rtrim($url,"/");
            array_push($links,$url);
        }
    }
}

function is_friend_link($url){
    $url = rtrim($url,"/");
    if(in_array($url,$GLOBALS["links"])){
		$aa='<i class="icon-heart iconfont" title="友情链接"></i>';
		echo $aa;
		
    }
    return false;
}

// 注销不支持的小工具	
if ( is_admin() ) {	
	function wpgo_remove_calendar_widget() {
		unregister_widget( 'WP_Widget_Calendar' );
		unregister_widget( 'WP_Widget_Archives' );
		unregister_widget( 'WP_Widget_Meta' );
		unregister_widget( 'WP_Widget_Search' );
		unregister_widget( 'WP_Widget_Text' );
		unregister_widget( 'WP_Widget_Categories' );
		unregister_widget( 'WP_Widget_RSS' );
	}
} 


add_theme_support( 'post-thumbnails' );

//获取设置
function beauty_options( $field ) {
	global $beauty_options;
	if ( isset( $beauty_options [$field] ) ) {
		$options = $beauty_options [$field];
		if ( is_array( $options ) ) {
			return $options;
		}
		return stripcslashes( $options );
	}
	return null;
}

//字符截取
function tp_substr($content, $length) {
	$content = str_replace(PHP_EOL, " ", $content);
	return mb_strimwidth(strip_tags($content), 0, $length, '...', 'UTF-8');
}

//分页菜单
function tp_paging($p = 2) {
	$chain = '';
	global $wp_query;
	$page_size = $wp_query->max_num_pages;
	if ($page_size <= 1) return;
	$current_page = get_query_var('paged');
	$current_page = empty($current_page) ? 1 : $current_page;
	$chain .= '<div class="paging">';
	$chain .= '<span>Page ' . $current_page . ' of ' . $page_size . '</span>';
	if ($current_page > $p + 1)
		$chain .= '<a class="btn-small" href="' . get_pagenum_link(1) . '">First</a>';
	if ($current_page > 1)
		$chain .= '<a class="btn-small" href="' . get_pagenum_link($current_page - 1) . '">Prev</a>';
	else
		$chain .= '<a class="btn-small" disabled>Prev</a>';
	if ($current_page > $p + 2)
		$chain .= '<a class="btn-small" disabled>...</a>' ;
	for ($i = $current_page - $p; $i <= $current_page + $p; $i++) {
		if ($i > 0 && $i <= $page_size) {
			if ($i == $current_page)
				$chain .= '<a class="btn-small" disabled>' . $i . '</a>';
			else
				$chain .= '<a class="btn-small" href="' . get_pagenum_link($i) . '">' . $i . '</a>';
		}
	}
	if ($current_page < $page_size - $p - 1)
		$chain .= '<a class="btn-small" disabled>...</a>';
	if ($current_page < $page_size)
		$chain .= '<a class="btn-small" href="' . get_pagenum_link($current_page + 1) . '">Next</a>';
	else
		$chain .= '<a class="btn-small" disabled>Next</a>';
	if ($current_page < $page_size - $p)
		$chain .= '<a class="btn-small" href="' . get_pagenum_link($page_size) . '">Last</a>';
	$chain .= '</div>';
	echo $chain;
}

//最新文章
function tp_recent_posts($number) {
	$chain = '';
	$posts = wp_get_recent_posts(array('numberposts' => $number));
	foreach ($posts as $post) {
		$chain .= '<li><a href="' . get_permalink($post['ID']) . '" title="Look ' . $post['post_title'] . '" >' . tp_substr($post['post_title'], 30) . '</a></li>';
	}
	echo $chain;
}

//最新评论
function tp_recent_comments($number) {
	$chain = '';
	$comments = get_comments(array('number' => $number, 'status' => 'approve', 'type' => 'comment'));
	foreach ($comments as $comment) {
		$GLOBALS['comment'] = $comment;
		$chain .= '<li>'. get_avatar(get_comment_author_email(), 36) . '<div class="info"><a href="' . get_comment_link() . '" rel="nofollow">' . get_comment_author() . '</a></div><div class="excerpt"  title="' . get_the_title($comment->comment_post_ID) . '">' . tp_substr(get_comment_text(), 26) . '</div></li>';
	} 
	echo $chain;
}

//评论列表
function tp_single_comment($comment, $args, $depth) {
	 $GLOBALS['comment'] = $comment;
    global $commentcount,$insertAD;;
    if(!$commentcount) {
        $page = ( !empty($in_comment_loop) ) ? get_query_var('cpage')-1 : get_page_of_comment( $comment->comment_ID, $args )-1;
        $cpp=get_option('comments_per_page');
        $commentcount = $cpp * $page;
    }
	
	?>
	<?php if ($insertAD==1 && !wp_is_mobile() && !$parent_id = $comment->comment_parent) { ?>
    <li class="comments" id="commentlistad"></li>
	<?php } ?>
	<li <?php comment_class(); ?> <?php if( $depth > 2){ echo ' style="margin-left:-50px;"';} ?> id="li-comment-<?php comment_ID() ?>" itemtype="http://schema.org/Comment" itemscope="" itemprop="comment">
	<div id="comment-<?php comment_ID(); ?>" class="comment-wrap clear">
		<div class="comment-author">
			<?php echo get_avatar( $comment, $size = '42');?>			
		</div>
		<div class="content alignleft">
			<cite class="author">
				<?php if (get_comment_author_url()) :?>
				<a class="url" id="reviewer-<?php echo get_comment_ID() ;?>" href="<?php echo get_comment_author_url() ;?>" rel="nofollow" itemprop="author" target="_blank">
				<?php else :?>
				<span class="url" id="reviewer-<?php echo get_comment_ID() ;?>'" itemprop="author">
				<?php endif ;?>
				<?php comment_author() ?>
				<?php if(get_comment_author_url()) : ?>
				</a>
				<?php is_friend_link($comment->comment_author_url);?>
				<?php else : ?>
				</span>
				<?php endif; ?>
			</cite>
			<div class="description" id="commentbody-<?php echo get_comment_ID() ;?>" itemprop="description">
				<?php if ( $comment->comment_approved == '0' ) :?>
				<em class="moderation"><i class="icon-warning-sign"></i>Your comment is awaiting moderation</em>
				<?php endif ;?>
				<?php echo get_comment_text();?>
			</div>
			
			<a href="<?php echo get_comment_link() ;?>" title="<?php echo get_comment_time('c', true) ;?>" rel="nofollow">
				<time itemprop="datePublished" datetime="<?php echo get_comment_time('c', true) ;?>">
					<?php echo tp_human_time(get_comment_time('U', true)) ;?>
				</time>
			</a>
			
			<?php if (current_user_can('edit_comment', get_comment_ID())):?>
			<a href="<?php echo get_edit_comment_link() ;?>" title="Edit comment" target="_blank">[Edit]</a>
			<?php endif ;?>
		</div>
		<div class="comment-right alignright">
			<?php if( $depth < 2): ?>
			<div class="commentnum">
				<a href="<?php echo get_comment_link() ;?>" rel="nofollow">
					<?php
						if(!$parent_id = $comment->comment_parent){
							++$commentcount;
							echo "#".$commentcount;
							++$insertAD;
						}
					?>
				</a>
			</div>
			<?php endif;?>
			<div class="reply">
				<?php comment_reply_link(array_merge( $args, array('reply_text' => 'Reply','depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</div>
		</div>
	</div>
	</li>
<?php
}

//trackback
function tp_single_trackback($comment) {
	$chain = '';
	$GLOBALS['comment'] = $comment;
	$chain .= '<li class="trackback ' . tp_comment_alt() . '">';
	$chain .= '<div class="title">';
	$chain .= '<a href="' . get_comment_author_url() . '" rel="external nofollow">' . get_comment_author() . '</a>';
	$chain .= '</div>';
	$chain .= '<time itemprop="datePublished" datetime="' . get_comment_time('c', true) . '">' . tp_human_time(get_comment_time('U', true)) . '</time>';
	$chain .= '</li>';
	echo $chain;
}


function tp_comment_alt() {
	global $comment_count;
	$class = '';
	if ($comment_count % 2)
		$class = 'odd';
	else
		$class = 'even';
	$comment_count = $comment_count - 1;
	return $class;
}

//相关文章
function wp_related_posts(){
    global $post;
    $num = 5;//文章数量
    $counter = 1;
    $exclude_id = get_post_meta($post->ID,'related',true);//获取手动置顶的相关文章
    $output ="";

    if ($exclude_id){
        $args = array(
            'post_status' => 'publish',
            'post_type' => array('post'),
            'post__in' => explode(',', $exclude_id),
            'posts_per_page' => $num
        );
        $posts = get_posts($args);
        foreach($posts as $sb){
            $output .= '<li><a href="' . get_permalink($sb->ID) . '">' . $sb->post_title . '</a></li>';//可自定义样式
            $i++;
        }
	    if( $i < $num){//自定义文章数不足后通过分类和标签处理
	        $tagsid = array();
	        $catid = array();
	        $thisid[] = $post->ID;
	        $posttags = get_the_tags();
	        $catids = get_the_category();
	        if(!empty($posttags)) {
	            foreach($posttags as $tag) {
	                $tagsid[] = $tag->term_id;
	            }
	        }
	        if(!empty($catids)) {
	            foreach($catids as $cat) {
	                $catid[] = $cat->term_id;
	            }
	        }
	        $args = array(
	            'post_type' => 'post',
	            'post__not_in' => $thisid,
	            'ignore_sticky_posts' => 1,
	            'posts_per_page' => ($num - $i),
	            'tax_query' => array(
	                'relation' => 'OR',//改成AND则必须是同标签同分类下
	                array(
	                    'taxonomy' => 'post_tag',
	                    'field'    => 'term_id',
	                    'terms'    => $tagsid,
	                ),
	                array(
	                    'taxonomy' => 'category',
	                    'field'    => 'term_id',
	                    'terms'    => $catid,
	                ),
	            ),
	        );
	        $rsp = get_posts($args );
	        foreach($rsp as $sb){
	            $output .= '<li><a href="' . get_permalink($sb->ID) . '">' . $sb->post_title . '</a></li>';//可自定义样式
	            $i++;
	        }
	    }
    }
    $final = '<ul>' . $output . '</ul>';
    return $final;
}


//面包屑
function tp_the_breadcrumbs($separator, $link){
	$chain = '';
	$chain .= '<div id="breadcrumb" itemprop="breadcrumb">';
	$chain .= '<a href="' . get_bloginfo('url') . '" class="home" rel="nofollow">Home</a> › ';
	$chain .= '<a href="' . get_bloginfo('url') . '/categories/" rel="nofollow">All Categories</a> › ';
	if (is_single()) {
		$categorys = get_the_category();
		$category = $categorys[0];
	} else {
		$category = get_category(intval(get_query_var('cat')));
	}
	if ($category->parent)
		$chain .= get_category_parents($category->parent, true, $separator);
		if ($link)
			$chain .= '<h2><a href="' . esc_url(get_category_link($category->term_id)) . '" title="' . esc_attr("View all posts in " . $category->name) . '">' . $category->name . '</a></h2>';
		else
			$chain .= '<h3>' . $category->name . '</h3>';
			$chain .= '</div>';
		echo $chain;
}

function tp_the_excerpt() {
	global $post;
	$chain = $post->post_excerpt;
	$raw_excerpt = $chain;
	if (post_password_required($post)) {
		$chain = '<div class="msgbox msg-info"><p>This post is password protected.</p><p>这是一篇受密码保护的文章。</p></div>';
		return $chain;
	} else {
		if ('' == $chain) {
			$chain = get_the_content('');
			$chain = strip_tags($chain);
			$chain = mb_strimwidth('<p>' . $chain . '</p>', 0, 500, '<p><a href="' . get_permalink() . '" class="btn" rel="nofollow">Continue Reading...</a></p>', 'UTF-8');
		}
	}
	return $chain;
}

//文章归档
function tp_get_archives() {
	$_chain = '';
	$chain = '';
	$selector = '';
	$_count = 0;
	global $wpdb, $wp_locale;
	$permalink = get_option('permalink_structure');
	$query = 'SELECT ID, post_name, post_title, comment_count, YEAR(post_date) AS "year", MONTH(post_date) AS "month", DAYOFMONTH(post_date) AS "dayofmonth" FROM ' . $wpdb->posts . ' WHERE post_status = "publish" AND post_type = "post" ORDER BY post_date DESC';
	$arcresults = $wpdb->get_results($query);
	$selector .= '<select class="selector">';
	$selector .= '<option value="all" selected="selected">All</option>';
	$chain .= '<div class="monthly-archives">';
	$_template = '<div class="year-%1$s month"><span class="all_mon"><a href="%2$s" title="Show detailed results for %3$s">%3$s</a><em>(%4$s)</em></span><ul>%5$s</ul></div>';
	if ($arcresults) {
		$_year = '';
		$_month = '';
		$_year_month = '';
		foreach ((array) $arcresults as $arcresult) {
			$year = $arcresult->year;
			$month = $arcresult->month;
			if ($_year_month != $year . $month) {
				if ($_chain != '') {
					$chain .= sprintf($_template, $_year, get_month_link($_year, $_month), $wp_locale->get_month($_month) . ' ' . $_year, sprintf(_n('%s post', '%s posts', $_count), $_count), $_chain);
					$_count = 0;
					$_chain = '';
				}
				if ($year != $_year) {
					$_year = $year;
					$selector .= '<option value="' . $year . '">' . $year . '</option>';
				}
				$_month = $month;
				$_year_month = $year . $month;
			}
			$_chain .= '<li>';
			$_chain .= '<span>' . tp_ordinal($arcresult->dayofmonth) . ' : </span>';
			if (empty($permalink))
				$_chain .= '<a href="' . home_url('?p=' . $arcresult->ID) . '" title="' . $arcresult->post_title . '">';
			else
				$_chain .= '<a href="' . home_url(str_replace("%postname%", $arcresult->post_name, $permalink)) . '" title="View this post ' . $arcresult->post_title . '">';
				$_chain .= $arcresult->post_title . '</a>';
				$_chain .= '<em>(' . $arcresult->comment_count . ')</em>';
				$_chain .= '</li>';
			$_count++;
		}
		$chain .= sprintf($_template, $_year, get_month_link($_year, $_month), $wp_locale->get_month($_month) . ' ' . $_year, sprintf(_n('%s post', '%s posts', $_count), $_count), $_chain);
	}
	$selector .= '</select>';
	$chain .= '</div>';
	$chain = $selector . $chain;
	echo $chain;
}

function tp_ordinal($num) {
	if (!in_array(($num % 100), array(11, 12, 13))) {
		switch ($num % 10) {
			case 1: return $num . 'st';
			case 2: return $num . 'nd';
			case 3: return $num . 'rd';
		}
	}
	return $num . 'th';
}

function tp_the_password_form () {
	$chain = '';
	global $post;
	$chain .= '<div class="msgbox msg-info">';
	$chain .= '<p>This post is password protected. To view it please enter your password below: </p>';
	$chain .= '<p>这是一篇受密码保护的文章。您需要提供访问密码: </p>';
	$chain .= '<form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" method="post">';
	$chain .= '<label for="pwbox-' .  get_the_ID() . '">Password: </label>';
	$chain .= '<input name="post_password" class="small" id="pwbox-' .  get_the_ID() . '" type="password" size="20" /> ';
	$chain .= '<input type="submit" class="btn small" name="Submit" value="Submit" />';
	$chain .= '</form>';
	$chain .= '</div>';
	echo $chain;
}

function tp_link_pages() {
	global $page, $numpages, $multipage, $more;
	$output = '';
	if ($multipage) {
		$output .= '<p class="post-paging"><span>Pages: </span>';
		for ($i = 1; $i < ( $numpages + 1 ); $i = $i + 1 ) {
			$output .= ' ';
			if ($i != $page || ((!$more) && ($page == 1)))
				$output .= _tp_link_page($i);
			else
				$output .= '<a class="current btn small" disabled>';
				$output .= $i;
				$output .= '</a>';
		}
		$output .= '</p>';
	}
	echo $output;
}

function _tp_link_page($i) {
	global $post, $wp_rewrite;
	if (1 == $i) {
		$url = get_permalink();
	} else {
		if ('' == get_option('permalink_structure') || in_array($post->post_status, array('draft', 'pending')))
			$url = add_query_arg( 'page', $i, get_permalink() );
		elseif ('page' == get_option('show_on_front') && get_option('page_on_front') == $post->ID )
			$url = trailingslashit(get_permalink()) . user_trailingslashit("$wp_rewrite->pagination_base/" . $i, 'single_paged');
		else
			$url = trailingslashit(get_permalink()) . user_trailingslashit($i, 'single_paged');
	}
	return '<a class="btn small" href="' . esc_url( $url ) . '">';
}

/*  Hook  */
function tp_content_more_link($value, $more_link_text) {
	global $post;
	return '<a href="' . get_permalink() . '#more-' . $post->ID . '" class="btn" rel="nofollow">Continue Reading...</a>';
}

function tp_pre_comment_content($comment_content) {
	return str_replace(PHP_EOL, " ", strip_tags($comment_content, '<a>'));
}

function tp_filter_pre($matches) {
	return '<pre' . $matches[1] . '>' . htmlspecialchars($matches[2]) . '</pre>';
}

function tp_filter_content($content) {
	$content = preg_replace_callback("/<pre(.+?)>(.+?)<\/pre>/is", "tp_filter_pre", $content);
	return $content;
}

/* post */
add_filter('the_content', 'tp_filter_content', 9);
add_filter('the_content_more_link', 'tp_content_more_link', 9, 2);
add_filter('protected_title_format', function($format) { return '%s'; });
add_filter('private_title_format', function($format) { return '%s'; });

/* comment */
add_filter('pre_comment_content', 'tp_pre_comment_content');

 //定义界面顶部区域内容,请注意修改您的主题目录
$email_headertop = '
<table width="800" border="0" cellspacing="0" cellpadding="0" style="padding:0;border:0;background:#f2f2f2;width:800px;margin:0px auto;font-size:13px;color: #999;white-space: normal;">
    <tbody>
		<tr>
			<td style="font-size:4px;line-height:4px;background:#D32D27;">&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table width="90%" border="0" cellspacing="0" cellpadding="0" style="padding:15px 40px;margin:0px auto;border:0;font-size:12px;background:#FFF;color: #999;">
					<tbody>
						<tr>
							<td colspan="3" align="center">
								<h1 style="color: #333;">' . get_option("blogname") . '</h1>
								<p style="text-indent: 1.3em;">分享个人心得和技巧、记录成长心路| 爱折腾、爱生活！</p>
							</td>
						</tr>';
			define ('emailheadertop', $email_headertop );
$email_footer = '
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3"><p style="font-size: 18px; center;">点击图片可以随机查看一篇有趣文章，试试又不会怀孕！</p></td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3">
								<p style="text-align: center;">
									<a href="' . get_option('home') . '/random" target="_blank">
										<img class="image aligncenter" width="100%" src="http://img.recordmind.com/random.png" alt="宣传图">
									</a>
								</p>
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<a style="display: block; width: 100%; height: 40px; background: #FF5E52; margin: 20px auto; font-size: 16px; line-height: 40px; 	letter-spacing: 3px; color: #f8f8f8; text-align: center; text-decoration: none;" href="' . get_option('home') . '" target="_blank">发现更多精彩>>
								</a>
							</td>
						</tr>
						<tr>
							<td colspan="3" height="1"  style="border-top:1px solid #ddd; line-height:5px;">&nbsp;</td>
						</tr>
						<tr>
							<td align="center">
								<p style="margin: 0 0 18px; line-height: 14px;">关于小编</p>
								<p style="margin: 0 auto; width: 250px; text-align: left;">
									90后，男，苦逼程序猿一枚。简单一切从简是我的座右铭，想要了解更多请点此查看 
									<a style="text-decoration: none; color: #c0392b;" href="' . get_option('home') . '/about" rel="nofollow">关于我</a> 
									，关注新浪微博 <a style="text-decoration: none; color: #c0392b;" href="http://weibo.com/wojiaxiaoxiaosha">请点击我</a>。
								</p>
							</td>
							<td bgcolor="#ddd" height="125" width="1"></td>
							<td align="center">
								<p style="margin: 0 0 18px; line-height: 14px;">关于本站</p>
								<p style="margin: 0 auto; text-align: left; width: 250px;">「' . get_option("blogname") . '」成立于2015年，分享个人心得和技巧、记录成长心路 | 珍爱生命，远离手机！</p>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td height="50" align="center" valign="middle" style="color:#868686;">
				<br>
				<span style="">请不要回复该邮件。你收到它，是因为你曾经在「' . get_option("blogname") . '」飘过。</span>
			</td>
		</tr>
		<tr>
			<td align="center" height="40" class="1" style="color:#fff;background:#D32D27;">      
				© World 2015. All Rights Reserved.		
			</td>
		</tr>
	</tbody>
</table>'	;
define ('emailfooter', $email_footer );
 

//评论通过通知评论者
add_action('comment_unapproved_to_approved', 'iwill_comment_approved');
function iwill_comment_approved($comment) {
	if( beauty_options('is_comment_pass') == 'Y' ) {
		if(is_email($comment->comment_author_email)) {
			$post_link = get_permalink($comment->comment_post_ID);   
			// 邮件标题，可自行更改
			$title = '您在 [' . get_option('blogname') . '] 的评论已通过审核';   
			// 邮件内容，按需更改。如果不懂改，可以给我留言
			$body = emailheadertop.'				
						<tr>
							<td colspan="3">
								<p style="font-size: 18px; color: #333;">' . trim($comment->comment_author) . ', 您好!</p>
								<br>
								您在《' . get_the_title($comment->comment_post_ID) . '》发表的评论:
								<br /> &nbsp;&nbsp;&nbsp;&nbsp;<p style="border: 1px solid #eee; padding: 20px; margin: 15px 0;"> '.$comment->comment_content.'</p>
								已通过管理员审核并显示!				
								<p class="footer" style="border-top: 1px solid #DDDDDD; padding-top: 6px; margin-top: 10px; color: #838383; text-align: center;">
									您可在此查看您的评论 
									<a href="'.get_comment_link( $comment->comment_ID ).'">前往查看</a>
									|欢迎再次来访 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a>
								</p>
							</td>
						</tr>	'.emailfooter;
			@wp_mail($comment->comment_author_email, $title, $body, "Content-Type: text/html; charset=UTF-8"); 
		}
	}
} 

/* 邮件评论回复美化版 */
function comment_mail_notify($comment_id) {
    $admin_email = get_bloginfo ('admin_email'); 
    $comment = get_comment($comment_id);
    $comment_author_email = trim($comment->comment_author_email);
    $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
    global $wpdb;
	if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '')
		$wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
	if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == '1'))
		$wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify='1' WHERE comment_ID='$comment_id'");
    $spam_confirmed = $comment->comment_approved;
	$notify = $parent_id ? get_comment($parent_id)->comment_mail_notify : '0';    
	if ($parent_id != '' && $spam_confirmed != 'spam' && $notify == '1' && $to != $admin_email) {  
		$wp_email = 'no-rely@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
		$to = trim(get_comment($parent_id)->comment_author_email);	
		$subject = '您在 [' . get_option("blogname") . '] 的留言有了新回复';
		$message = emailheadertop.
				'<tr>
					<td colspan="3">
						<p style="font-size: 18px; color: #333;">' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
							您曾在《' . get_the_title($comment->comment_post_ID) . '》上发表评论:<br /> &nbsp;&nbsp;&nbsp;&nbsp;<p style="border: 1px solid #eee; padding: 20px; margin: 15px 0;"> '
							. trim(get_comment($parent_id)->comment_content) . '
						</p>
						' . trim($comment->comment_author) . ' 给您的回应:
						<p style="border: 1px solid #eee; padding: 20px; margin: 10px 0;">
							<a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">'
								. trim($comment->comment_content) . '<br />
							</a>
						</p>
						<p class="footer" style="border-top: 1px solid #DDDDDD; padding-top: 6px; margin-top: 10px; color: #838383; text-align: center;">
							你可以点击此链接 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看完整內容</a>|欢迎再次来访 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a>
						</p>
					</td>
				</tr>	'.emailfooter;
		$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
		$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
		wp_mail( $to, $subject, $message, $headers );
    }
}
add_action('comment_post', 'comment_mail_notify');

/* 自动加勾选栏 */
function add_checkbox() {
  echo '<span class="mail_notify_box" id="mail_notify_box" title="是否接收评论回复邮件通知"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" style="margin-left:20px;" /><label for="comment_mail_notify">有人回复时邮件通知我</label></span>';
}
add_action('comment_form', 'add_checkbox');

// 博客后台登录失败时发送邮件通知管理员
function wp_login_failed_notify(){
    date_default_timezone_set('PRC');
    $admin_email = get_bloginfo('admin_email');
    $to = $admin_email;
    $subject = '【登录失败】有人使用了错误的用户名或密码登录' . get_bloginfo('name') . '！';
    $message =  '
	<div style="background: #f8f8f8; color: #666; font-size: 12px;">
		<div style="width: 570px; margin: 0 auto; background: #fff; padding: 25px 70px; border-top: 5px solid #FF5E52;">	
			<p style="font-size: 18px; color: #333;">' . get_bloginfo('name') . ', 账户登录失败通知!</p>
            <div style="padding:0;font-weight:bold;color:#6e6e6e;font-size:16px">尊敬的管理员您好！</div>
            <p style="color: red;font-size:13px;line-height:24px;">' . get_bloginfo('name') . '有一条登录失败的记录产生，若登录操作不是您产生的，请及时注意网站安全！</p>
            <table cellpadding="0" cellspacing="0" border="0" style="width:100%;border-top:1px solid #eee;border-left:1px solid #eee;color:#6e6e6e;font-size:16px;font-weight:normal">
                <thead><tr><th colspan="2" style="padding:10px 0;border-right:1px solid #eee;border-bottom:1px solid #eee;text-align:center;background:#f8f8f8;">失败信息如下</th></tr></thead>
                <tbody>
				    <tr>
                        <td style="padding:10px 0;border-right:1px solid #eee;border-bottom:1px solid #eee;text-align:center;width:100px">登录名</td>
                        <td style="padding:10px 20px 10px 30px;border-right:1px solid #eee;border-bottom:1px solid #eee;line-height:30px">' . $_POST['log'] . '</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 0;border-right:1px solid #eee;border-bottom:1px solid #eee;text-align:center">尝试的密码</td>
                        <td style="padding:10px 20px 10px 30px;border-right:1px solid #eee;border-bottom:1px solid #eee;line-height:30px">' . $_POST['pwd'] . '</td>
                    </tr>
				    <tr>
                        <td style="padding:10px 0;border-right:1px solid #eee;border-bottom:1px solid #eee;text-align:center;">登录时间</td>
                        <td style="padding:10px 20px 10px 30px;border-right:1px solid #eee;border-bottom:1px solid #eee;line-height:30px">' . date("Y-m-d H:i:s") . '</td>
                    </tr>  
				    <tr>
                        <td style="padding:10px 0;border-right:1px solid #eee;border-bottom:1px solid #eee;text-align:center;">登录IP</td>
                        <td style="padding:10px 20px 10px 30px;border-right:1px solid #eee;border-bottom:1px solid #eee;line-height:30px">' . $_SERVER['REMOTE_ADDR'] . '</td>
                    </tr>               
                </tbody>
            </table>
        </div>
	</div>';
    wp_mail( $to, $subject, $message, "Content-Type: text/html; charset=UTF-8" );
}
add_action('wp_login_failed', 'wp_login_failed_notify');
add_filter('logout_url', 'mk_logout_redirect_home', 10, 2);
function mk_logout_redirect_home($logouturl, $redir){
    $redir = home_url();
    return $logouturl . '&redirect_to=' . urlencode($redir);
}

//用户更新账户通知用户
function user_profile_update( $user_id ) {
    $site_url = get_bloginfo('wpurl');
    $site_name = get_bloginfo('wpname');
    $user_info = get_userdata( $user_id );
    $to = $user_info->user_email;
    $subject = "".$site_name."账户更新";
    $message = emailheadertop.'
		<tr>
			<td colspan="3">
				<p style="font-size: 18px; color: #333;">' . trim($comment->comment_author) . ', 您好!</p>
				您在' .$site_name. '账户资料修改成功！<br /> &nbsp;&nbsp;&nbsp;&nbsp;
				<p style="border-bottom: 1px solid #DDDDDD; padding: 20px; margin: 5px 0;">亲爱的 ' .$user_info->display_name . '您的资料修改成功.谢谢您的光临!</p>
			</td>
		</tr>'.emailfooter;
	wp_mail( $to, $subject, $message, "Content-Type: text/html; charset=UTF-8");
}
add_action( 'profile_update', 'user_profile_update', 10, 2);

//用户账户被删除通知用户
function iwilling_delete_user( $user_id ) {
    global $wpdb;
    $site_name = get_bloginfo('name');
    $user_obj = get_userdata( $user_id );
    $email = $user_obj->user_email;
    $subject = "帐号删除提示：".$site_name."";
    $message = emailheadertop.'
		<tr><td colspan="3"><p style="font-size: 18px; color: #333;">' . trim($comment->comment_author) . ', 您好!</p>
		您在' .$site_name. '的账户已被管理员删除！<br /> &nbsp;&nbsp;&nbsp;&nbsp;
		<p style="border-bottom: 1px solid #DDDDDD; padding: 20px; margin: 5px 0;">亲爱的 ' .$user_info->display_name . '如果您对本次操作有什么异议，请联系管理员反馈！<br/>我们会在第一时间处理您反馈的问题.</p></td></tr>'.emailfooter;
    wp_mail( $email, $subject, $message, "Content-Type: text/html; charset=UTF-8");
}
add_action( 'delete_user', 'iwilling_delete_user' );

// WordPress 发布新文章后邮件通知已注册的用户
function newPostNotify($post_ID) {
    if( wp_is_post_revision($post_ID) ) return;
    global $wpdb;
    $site_name = get_bloginfo('name');
    $post_contents = get_post($post_ID)->post_content;
    $get_post_info = get_post($post_ID);
    if ( $get_post_info->post_status == 'publish' && $_POST['original_post_status'] != 'publish' ) {
        // 读数据库，获取所有用户的email
        $wp_user_email = $wpdb->get_col("SELECT DISTINCT user_email FROM $wpdb->users");
        // 邮件标题
        $subject = 'Hi!'.$site_name.'发布新文章啦!';
        // 邮件内容
        $message = emailheadertop.'
			<tr><td colspan="3"><p style="font-size: 18px; color: #333;">'.$site_name. '发布新文章啦!</p>
			<br /> &nbsp;&nbsp;&nbsp;&nbsp;<p style="border: 1px solid #eee; padding: 20px; margin: 15px 0;line-height:24px;">文章标题：' . get_the_title($post_ID) . '<br />' . mb_strimwidth($post_contents, 0, 320,"...") . '</p>
			<p class="footer" style="border-top: 1px solid #DDDDDD; padding-top: 6px; margin-top: 10px; color: #838383; text-align: center;">				 
				<a href="' . get_permalink($post_ID) . '">查看全文</a>
				|点此访问首页 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a>
			</p></td></tr>
        '.emailfooter;
        // 发邮件
        $message_headers = "Content-Type: text/html; charset=\"utf-8\"\n";
        wp_mail($wp_user_email, $subject, $message, $message_headers); 
    }
}
add_action('publish_post', 'newPostNotify');
add_action('comment_post', 'comment_mail_notify');

//文章内容更新提示
function is_modified(){
    global $post;
    $punish_time = get_the_date('U');
    $modified_time = get_the_modified_date('U');
    $time = time();
    if( ( $modified_time > $punish_time) && ( $time - $modified_time < 3600*24*7 )  )
        return true;

}

// 禁止全英文评论
function ban_comment_post( $incoming_comment ) {
	$pattern = '/[一-龥]/u';
	if(!preg_match($pattern, $incoming_comment['comment_content'])) {
		err( "写点汉字吧，博主外语很捉急！ Please write some chinese words！" );
	}
	return( $incoming_comment );
}
add_filter('preprocess_comment', 'ban_comment_post');

// 排除文章内图片的外链
add_filter('the_content','baezone_the_go_url',999);
function baezone_the_go_url($content){
	preg_match_all('/href="((?:(?!\.jpg|jpeg|gif|bmp|png).)*)"/',$content,$matches);
	if($matches){
		foreach($matches[1] as $val){
			if( strpos($val,home_url())===false ) $content=str_replace("href=\"$val\"", "href=\"" . get_bloginfo('wpurl'). "/go?url=" .$val. "\"" ."target='_blank'",$content);
		}
	}
	return $content;
}

add_action('publish_post', 'kn007_new_post_weibo', 0);
function kn007_new_post_weibo($post_ID,$debug=false) {
	$access_token = "2.00OcP4ECxSqPXD1f89207a707Ov7hC";//这个是access_token，改为你自己的。
	if(!$debug){//如果不是DEBUG模式
		$post = get_post($post_ID);//提取实际ID页面
		if ( empty($post) ) return false;//不存在返回
		if ( 'post' != $post->post_type ) return false;//不是文章返回
		if ( 'publish' != $post->post_status ) return false;//这篇文章非新文章返回
	}
	if(!wp_is_post_revision($post_ID)){//确保这篇文章不是草稿之类的
		$pictmpfile = "";//图片临时文件变量定义
		$status = "我刚刚发布了新文章《".get_the_title($post_ID)."》，快来看看吧。详细内容请点击：".get_permalink($post_ID);//要发布的文字内容，可以自己改
		if (has_post_thumbnail()) {//如果存在特色图片
			$post_thumbnail_id = get_post_thumbnail_id($post_ID);
			$img_src = wp_get_attachment_url( $post_thumbnail_id );
		}else{//不存在取第一张图
			$content = get_post( $post_ID )->post_content; 
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$img_src = $strResult[1][0];
		}
		if( !empty($img_src) ){//如果获取到图片
			$url = "https://api.weibo.com/2/statuses/upload.json";
			$data = '';
			$data = array();
			$data["access_token"]=$access_token;
			$data["status"]=urlencode ($status);
			$picfile = str_replace(home_url(),$_SERVER["DOCUMENT_ROOT"],$img_src);//优先查看本地图片是否存在
			if( !empty($picfile)){
				$data["pic"]='@'.$picfile;
			}else{//如果本地图片不存在，取到本地临时目录
				$filecontent = file_get_contents($img_src);
				$array = explode( '?', basename($img_src) );
				$filename = $array[0];
				$pictmpfile = '/tmp/'.$filename;
				file_put_contents($pictmpfile,$filecontent);
				$data["pic"]='@'.$pictmpfile;
				$filecontent = null;
			}
		}else{//没有图片的动作
			$url = "https://api.weibo.com/2/statuses/update.json";
			$data = "access_token=" . $access_token . "&status=" . urlencode ($status);
		}
		$output = json_decode(kn007_post_by_curl($url,$data));//发布微博
		if ($pictmpfile!=''){unlink($pictmpfile);}//如果存在临时图片，删除它
		if($debug){//如果DEBUG模式，输出DEBUG信息
			var_dump($data);
			echo '<hr />';
			var_dump($output);
		}
		return true;
	}
	return false;
}
function kn007_post_by_curl($url, $data) {//负责POST的函数
	$ch = curl_init();
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
	curl_setopt ( $ch, CURLOPT_POST, TRUE );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	$body = curl_exec ( $ch );
	curl_close ( $ch );
	return $body;
}
?>
