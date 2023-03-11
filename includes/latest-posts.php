<?php 

/*
== ------------------------------------------------------------------- ==
== @@ Widget Registering
== ------------------------------------------------------------------- ==
*/

//add_action( 'widgets_init', 'beauty_register_latest_posts_widget' );

function beauty_register_latest_posts_widget() {
	register_widget( 'latest_posts_widget' );
}


/*
== ------------------------------------------------------------------- ==
== @@ Widget Class
== ------------------------------------------------------------------- ==
*/

class latest_posts_widget extends WP_Widget {
	
	function latest_posts_widget() {
		
		$args = array(
			'classname'		=> 'widget_latest_posts',
			'description'	=> __( 'A widget to display latest post entries', 'beauty_dictionary' )
		);
		
		$this->WP_Widget( 'latest_posts_widget', Beauty . ' ' . __( 'Latest Posts', 'beauty_dictionary' ), $args );
	}
	
	function form( $instance ) {
		
		$defaults = array(
			'title'			=> '',
			'count'			=> '',
			'category'		=> '',
			'show_photo'	=> 1
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		?>
		<!-- title -->
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'beauty_dictionary' ); ?>: </label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		<!-- count -->
		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'How many entries do you want to display', 'beauty_dictionary' ); ?>: </label>
			<select id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" class="widefat">
				<?php 
				for ( $i = 1; $i <= 10; $i ++ ) {
					$selected = '';
					if ( $instance['count'] == $i ) {
						$selected = 'selected="selected" ';
					}
					echo '<option ' . $selected . 'value="' . $i . '">' . $i . '</option>';
				}
				?>
			</select>
		</p>
		<!-- category -->
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e( 'Restrict to a category', 'beauty_dictionary' ); ?>: </label>
			<?php wp_dropdown_categories('show_option_all=All&hierarchical=1&orderby=name&selected=' . $instance['category'] . '&name=' . $this->get_field_name('category') . '&class=widefat'); ?>
		</p>
		<!-- show photo -->
		<p>
			<label for="<?php echo $this->get_field_id('show_photo'); ?>">
				<input type="checkbox" <?php checked( '1', $instance['show_photo'] ); ?> id="<?php echo $this->get_field_id('show_photo'); ?>" name="<?php echo $this->get_field_name('show_photo'); ?>" value="1" />
				<?php _e( 'Show Featured Image', 'beauty_dictionary' ); ?>
			</label>
		</p>
		<?php
	}
	
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['count'] = $new_instance['count'];
		$instance['category'] = $new_instance['category'];
		$instance['show_photo'] = $new_instance['show_photo'];
		
		return $instance;
		
	}
	
	function widget( $args, $instance ) {
	
		global $post;
		global $beauty_show_photo;
		
		extract( $args );
		
		$title = ( ! empty( $instance['title'] ) ) ? apply_filters( 'widget_title', $instance['title'] ) : __( 'Latest Posts', 'beauty_dictionary' );
		$count = ( ! empty( $instance['count'] ) ) ? absint( $instance['count'] ) : 10;
		$category = ( ! empty ( $instance['category'] ) ) ? $instance['category'] : '';
		
		$beauty_show_photo = $instance['show_photo'];
		
		$args = array(
			'posts_per_page' 	=> $count,
			'cat'				=> $category,
			//'post__not_in'		=> ( isset( $post->ID ) ) ? array( $post->ID ) : array(),
			'post_status'		=> 'publish',
			'ignore_sticky_posts' => 1,
			'tax_query' => array(
				array(
					'taxonomy' => 'post_format',
					'field' => 'slug',
					'terms' => array('post-format-link', 'post-format-quote', 'post-format-aside', 'post-format-status' ),
					'operator' => 'NOT IN'
				)
			)
		);
		
		$latest_posts_query = new WP_Query( $args );
		
		if ( $latest_posts_query->have_posts() ) {
		
			echo $before_widget;
			
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			
			?>
			<ul>
				<?php while ( $latest_posts_query->have_posts() ) : $latest_posts_query->the_post(); ?>
					<?php locate_template( 'article-widget.php', true, false ); ?>
				<?php endwhile; ?>
			</ul>
			<?php
			
			echo $after_widget;
			
		}	
		wp_reset_postdata();
		
	}
	
}
	
?>