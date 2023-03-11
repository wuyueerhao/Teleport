<?php 
//判断是否移动设备
function is_mobile(){
    if (stristr($_SERVER['HTTP_USER_AGENT'], 'android') || stristr($_SERVER['HTTP_USER_AGENT'], 'WPDesktop') || stristr($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') || stristr($_SERVER['HTTP_USER_AGENT'], 'iphone')) {
        return true;
    } else {
        return false;
    }
}

// 启用友情链接
add_filter( 'pre_option_link_manager_enabled', '__return_true' );
// 友情链接
function get_the_link_items($id = null){
    $default_ico = get_template_directory_uri().'/assets/images/wordpress.png';
	$bookmarks = get_bookmarks('orderby=date&category=' .$id );
    $output = '';
    if ( !empty($bookmarks) ) {
        $output .= '<div class="link_item"><ul>';
        foreach ($bookmarks as $bookmark) {           						
			if (preg_match('/^(https?:\/\/)?([^\/]+)/i',$bookmark->link_url,$URI)) {//提取域名
				$domains = $URI[2];
			}else{//域名提取失败，显示默认小地球
				$domains = $bookmark->link_url;
			}
			$output .=  '
			<li>
				<a class="link-item-inner effect-apollo" href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank" >
				<img src="'. $bookmark->link_url . '/favicon.ico" onerror="javascript:this.src=\'' . $default_ico . '\'" />
				<span class="sitename">'. $bookmark->link_name .'</span>
				</a>
			</li>';
        }
        $output .= '</ul></div>';
    }
    return $output;
}

function get_link_items(){
    $linkcats = get_terms( 'link_category' );
    if ( !empty($linkcats) ) {
        foreach( $linkcats as $linkcat){            
			$result .=  '<div class="link_list"><h3>'.$linkcat->name.'';
            if( $linkcat->description ) $result .= '<span class="link_description">' . $linkcat->description . '</span></h3>';
            $result .= '</h3>' .get_the_link_items($linkcat->term_id).'</div>';

        }
    } else {
        $result = get_the_link_items();
    }
    return $result;
}

function shortcode_link(){
    return get_link_items();
}
add_shortcode('bigfalink', 'shortcode_link');

function img_the_content_nofollow($content){
	preg_match_all('/src="(http.*?)"/',$content,$matches);
	if($matches){
		foreach($matches[1] as $val){
			if( strpos($val,home_url())===false ) 
				$content=str_replace("src=\"$val\"", "rel=\"nofollow\" src=\"" . get_bloginfo('wpurl'). "/link?url=" .base64_encode($val). "\"",$content);
			}
		}
	return $content;
}

//菜单设置
add_theme_support( 'menus' );
if ( function_exists( 'register_nav_menus' ) ) {
    register_nav_menus(
        array(
            'header-menu' => '顶部菜单',
            'footer-menu' => '底部菜单',
			'mobile-menu' => '手机菜单',
        )
    );
}

//返回顶部
if ( ! function_exists( 'beauty_scroll_top_link' ) ) {
	function beauty_scroll_top_link() {		
		echo '<a id="gotop"></a>';
	}
}

//获取头像
function beauty_get_ssl_avatar($avatar) {
	$avatar = str_replace(array("www.gravatar.com","0.gravatar.com","1.gravatar.com","2.gravatar.com","en.gravatar.com"),"gravatar.com",$avatar);
   return $avatar;
}
add_filter('get_avatar', 'beauty_get_ssl_avatar');


//时间格式
function tp_human_time($time) {
	$current_time = time();
	$diff = $current_time - $time;
	if ($diff <= 60) {
		$since = $diff <= 5 ? 'about 5 seconds ago' : $diff . ' seconds ago';
	} else if ($diff <= 3600) {
		$mins = (int)($diff / 60);
		$since = $mins <= 1 ? 'about a minute ago': $mins . ' minutes ago';
	} else if ($diff <= 86400) {
		$hours = (int)($diff / 3600);
		$since = $hours <= 1 ? 'about an hour ago' : $hours . ' hours ago';
	} else if ($diff <= 604800) {
		$days = (int)($diff / 86400);
		$since = $days <= 1 ? 'about a day ago' : $days . ' days ago';
	} else if ($diff <= 4838400) {
		$weeks = (int)($diff / 604800);
		$since = $weeks <= 1 ? 'about a week ago' : $weeks . ' weeks ago';
	} else {
		$since = date('F jS, Y \a\t H:i', $time);
	}
	return $since;
}


//sidebar小工具启用
function teleport_widgets_init() {
	// Main Sidebar Widget Area
	register_sidebar( array(
		'name' => __( 'Sidebar', 'teleport' ),
		'id' => 'main_sidebar',
		'description' => __( 'The primary widget area', 'teleport' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<p class="widget-title">',
		'after_title' => '</p>',
	) );	
}
add_action( 'widgets_init', 'teleport_widgets_init' );



// Creating the widget 
class site_info extends WP_Widget {
	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'site_info', 
			// Widget name will appear in UI
			__('站点信息', 'wpb_widget_domain'), 
			// Widget description
			array( 'description' => __( '站点信息', 'wpb_widget_domain' ), ) 
		);
	}
	// Creating widget front-end
	public function widget( $args, $instance ) {
		extract($args);
        $title = apply_filters('widget_title',esc_attr($instance['title']));
        echo $before_widget.$before_title.$title.$after_title;
        ?>
        <ul class="website-anylists clearfix">
            <li class="articles">Articles：<?php $count_posts = wp_count_posts();echo $published_posts = $count_posts->publish;?>篇</li>
            <li class="comments">Comments：<?php $count_comments = get_comment_count();echo $count_comments['approved'];?>条</li>
            <li class="wpages">Pages Counts：<?php $count_pages = wp_count_posts('page'); echo $page_posts = $count_pages->publish; ?> 个</li>
            <li class="categorys">Categorys：<?php echo $count_categories = wp_count_terms('category'); ?>个</li>
			<li class="post-tags">Tags：<?php echo $count_tags = wp_count_terms('post_tag'); ?>个</li>
            <li class="uptime">Uptime：<?php echo floor((time()-strtotime(leon_get_firstpostdate()))/86400); ?>天</li>
        </ul>

        <?php
        echo $after_widget;
	}       
	// Widget Backend 
	
	public function form( $instance ) {
		global $wpdb;
        $instance = wp_parse_args((array) $instance, array('title'=> ''));
        $title = esc_attr($instance['title']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">标题：<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
        </p>
        <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
	<?php 
	}
     
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
// Class site_info ends here
} 
// Register and load the widget
function wpb_load_widget() {
	register_widget( 'site_info' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

//最新评论小工具
register_widget('widget_newcomments');
class widget_newcomments extends WP_Widget {
	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'widget_newcomments', 
			// Widget name will appear in UI
			__('最新评论小工具', 'wpb_widget_domain'), 
			// Widget description
			array( 'description' => __( '最新评论小工具', 'wpb_widget_domain' ), ) 
		);
	}
	function widget_newcomments() {
		$option = array('classname' => 'widget-recent-comments', 'description' => '显示网友最新评论（头像+名称+评论）' );
		$this->WP_Widget(false, '最新评论 ', $option);
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? '最新评论' : apply_filters('widget_title', $instance['title']);
		$count = empty($instance['count']) ? '5' : apply_filters('widget_count', $instance['count']);

		echo $before_title . $title . $after_title;
		echo '<ul class="widget-recent-comments">';
		echo tp_recent_comments( $count );
		echo '</ul>';
		echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
		return $instance;
	}
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => '' ) );
		$title = strip_tags($instance['title']);
		$count = strip_tags($instance['count']);
		echo '<p><label>标题：<input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.attribute_escape($title).'" size="24" /></label></p>';
		echo '<p><label>数目：<input id="'.$this->get_field_id('count').'" name="'.$this->get_field_name('count').'" type="text" value="'.attribute_escape($count).'" size="3" /></label></p>';
	}
}

function get_theme_header() {   
    ?>
<header id="header" role="banner" class="row" >
    <div class="beauty_canvas">
		<?php if(is_mobile()) :?> 
		<div class="header_logo">			
			<a class="menu-button"><span class="icon-list"></span></a>		
			<?php if (is_home() || is_front_page()) { ?>
			<h1 class="title white alignleft"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
			<?php } else { ?>
			<h2 class="title white alignleft"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h2>
			<?php } ?>
			<h2 class="tagline white alignleft"><?php bloginfo('description'); ?></h2>			
		</div>
		<?php endif;?>
		<div class="social alignright">
			<div class="social_links">
				<?php
					$the_sns = beauty_options('sns');
					if ( isset ($the_sns) ) {
						foreach ($the_sns as $name => $sns) {
							if ( $sns != '' ) {
								if ( $name == 'email' ) {
									echo '<a href="mailto:'.$sns.'" class="'.$name.' ease"><i class="icon-'.$name.'"></i></a>';
								} elseif( $name == 'linkedin' ) {
									echo '<a rel="nofollow" href="http://'.$name.'.com/in/'.$sns.'" target="_blank" class="'.$name.' ease">'.'<i class="icon-'.$name.'"></i></a>';
								} else {
									echo '<a rel="nofollow" href="http://'.$name.'.com/'.$sns.'" target="_blank" class="'.$name.' ease">'.'<i class="icon-'.$name.'"></i></a>';
								}
							}
						}
					}
				?>										
			</div>
		</div>
		<?php if(is_mobile()) :?> 
		<nav id="slide-menu" class="alignleft">	
			<ul id="navi_mobile">
				<li>
					<a href="<?php bloginfo('url');?>">首页</a>
				</li>
				<li class="dropdown">
					<h4><a class="dropdown-link">网站页面</a></h4>
					<div class="list-item none">																	
						<?php if( beauty_options('is_mobile_nav') == 'Y' ) { ?>
						<?php wp_nav_menu( array( 'theme_location' => 'mobile-menu','container'=>'ul')); ?>	
						<?php } else {?>
						<ul>
						<?php $pages = get_pages(array('post_type' => 'page','post_status' => 'publish')); 
							foreach($pages as $val){
								printf('<li><a href="%s">%s</a></li>', $val->guid, $val->post_title);
							}
						?>
						</ul>
						<?php } ?>
					</div>	
				</li>
				<li class="dropdown">
					<h4><a class="dropdown-link" href="javascript:;">分类目录</a></h4>
					<div class="list-item none">
					<ul>
						<?php wp_list_categories('&title_li='); ?>
					</ul>
					</div>
				</li>
			</ul>
			<script type="text/javascript" language="javascript">
				navList(12);
			</script>					
		</nav>
		<?php else :?>	
		<div class="caption alignleft">
			<?php if (is_home() || is_front_page()) { ?>
			<h1 class="title white alignleft"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
			<?php } else { ?>
			<h2 class="title white alignleft"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h2>
			<?php } ?>
			<h2 class="tagline white alignleft"><?php bloginfo('description'); ?></h2>
			
		</div>		
		<nav id="main-nav" class="alignleft">
            <?php wp_nav_menu( array( 'theme_location' => 'header-menu','menu_id'=>'main_menu','menu_class'=>'nav clearfix','container'=>'ul')); ?>
        </nav>
		<?php endif;?>
	</div>
</header>	
<?php
}


/* archive */
function beauty_archive_filter( $content ) {
	if ( strpos( $content, '&nbsp;(' ) !== false ) {
	    // 'show_post_counts' is active
		$new_content = str_replace( "&nbsp;" , "&nbsp;<cite class='count'>", $content );
		$new_content = str_replace( "</li>" , "</cite></li>", $new_content );
	}
	else {
		$new_content = $content;
	}
	return $new_content;	
}   
add_filter('get_archives_link', 'beauty_archive_filter');


/* categories */
function beauty_categories_filter( $content ) {
	if ( strpos( $content, '</a> (' ) !== false ) {
		// 'show_post_counts' is active
		$new_content = str_replace( "</a> (" , "</a> <cite class='count'>(", $content );
		$new_content = str_replace( ")" , ")</cite>", $new_content );
	}
	else {
		$new_content = $content;
	}	
	return $new_content;
}
add_filter( 'wp_list_categories', 'beauty_categories_filter' );





/* Widget Post Featured Image */
if ( ! function_exists( 'beauty_widget_post_featured_image' ) ) {
	function beauty_widget_post_featured_image() {		
		global $post;
		$width = WIDGET__PHOTO_WIDTH;
		$height = WIDGET__PHOTO_HEIGHT;	
		$post_id = $post->ID;		
		$image_url = wp_get_attachment_url( get_post_thumbnail_id(), 'full' );
		$original_image_url = $image_url;
		$force_resize = false;		
		if ( ! $image_url ) {
			$image_url = get_template_directory_uri() . '/assets/images/no_photo_small.jpg';
		}		
		$hyperlink_css = 'project_image';
		$image_css = 'attachment-post-thumbnail wp-post-image';			
		$html = '';	
		if ( $image_url ) {			
			$resized_image_url = thumb_resizer( $image_url, $width, $height, true, true, false);			
			if ( $resized_image_url ) {
				$image_url = $resized_image_url;
			}
			else {
				$force_resize = true;
			}				
			if ( ! $force_resize ) {
				$html .= '<img src="' . $image_url . '" alt="' . esc_attr( get_the_title($post_id) ) . '" class="' . $image_css . '" />';
			}
			else {
				$html .= '<img src="' . $image_url . '" alt="' . esc_attr( get_the_title($post_id) ) . '" class="' . $image_css . '" style="width:' . $width . 'px;" />';
			}			
			// link setup	
			$html = '<a href="' . get_permalink($post->ID) . '" class="' . $hyperlink_css . '">' . $html . '</a>';			
		}		
		return $html;		
	}
}

//获取建站日期
function leon_get_firstpostdate($format = "Y-m-d"){
    $ax_args = array(
        'numberposts' => -1,
        'post_status' => 'publish',
        'order' => 'ASC'
    );
    $ax_get_all = get_posts($ax_args);
    $ax_first_post = $ax_get_all[0];
    $ax_first_post_date = $ax_first_post->post_date;
    $output = date($format, strtotime($ax_first_post_date));
    return $output;
}

//禁用5.0古登堡编辑器
add_filter('use_block_editor_for_post', '__return_false');
remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );
add_filter( 'use_widgets_block_editor', '__return_false' );




//读者墙
if(!function_exists("deep_in_array")) {
    function deep_in_array($value, $array) { // 返还数组序号
        $i = -1;
        foreach($array as $item => $v) {
            $i++;
            if($v["email"] == $value){
                return $i;
            }
        }
        return -1;
    }
}

function get_active_friends($num = null,$size = null,$days = null) {
    $num = $num ? $num : 15;
    $size = $size ? $size : 34;
    $days = $days ? $days : 30;
    $array = array();
    $comments = get_comments( array('status' => 'approve','author__not_in'=>1,'date_query'=>array('after' => $days . ' days ago')) );
    if(!empty($comments))    {
        foreach($comments as $comment){
            $email = $comment->comment_author_email;
            $author = $comment->comment_author;
            $url = $comment->comment_author_url;
            $data = human_time_diff(strtotime($comment->comment_date));
            if($email!=""){
                $index = deep_in_array($email, $array);
                if( $index > -1){
                    $array[$index]["number"] +=1;
                }else{
                    array_push($array, array(
                        "email" => $email,
                        "author" => $author,
                        "url" => $url,
                        "date" => $data,
                        "number" => 1
                    ));
                }
            }
        }
        foreach ($array as $k => $v) {
            $edition[] = $v['number'];
        }
        array_multisort($edition, SORT_DESC, $array); // 数组倒序排列
    }
    $output = '<ul class="active-items">';
    if(empty($array)) {
        $output = '<li>none data.</li>';
    } else {
        $max = ( count($array) > $num ) ? $num : count($array);
        
        for($o=0;$o < $max;$o++) {
            $v = $array[$o];
            $active_avatar = get_avatar($v["email"],$size);
            $active_url = $v["url"] ? $v["url"] : "javascript:;";
            $active_alt = $v["author"] . ' - 共'. $v["number"]. ' 条评论，最后评论于'. $v["date"].'前。';
            $output .= '<li class="active-item" data-info="'.$active_alt.'"><a target="_blank" rel="external nofollow" href="'.$active_url.'">'.$active_avatar.'</a></li>';
        }
        
    }
    $output .= '</ul>';
    return  $output;
}

function active_shortcode( $atts, $content = null ) {
    extract( shortcode_atts( array(
            'num' => '',
            'size' => '',
            'days' => '',
        ),
        $atts ) );
    return get_active_friends($num,$size,$days);
}
add_shortcode('active', 'active_shortcode');
?>