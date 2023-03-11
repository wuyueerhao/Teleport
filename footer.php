		<footer id="footer" class="wrapper">
			<div class="copyright alignleft">
				<?php echo beauty_options('footer_pages'); ?>			
				<span class="theme-generator">Theme is <span title="So simple, so beautiful"><a href="http://www.nichijou.com" rel="author" title="Design & Code By Key" target="_blank">Teleport</a></span></span> |
				<span class="wp-generator" title="Semantic Personal Publishing Platform">By <a href="http://wordpress.org/" target="_blank" rel="external">WordPress !</a></span>				
			</div>
			<div class="foot_nav alignright">
				<?php wp_nav_menu( array( 'theme_location' => 'footer-menu','menu_id'=>'footer_menu','menu_class'=>'menu','container'=>'ul')); ?>
			</div>
		</footer>		
		<div style="display:none;">
			<?php echo beauty_options('statistics_code'); ?>
		</div>
		<script src="<?php bloginfo('template_url'); ?>/assets/js/common.js"></script>
		<?php if( beauty_options('is_web_pjax') == 'Y' ) { ?>				
		<script src="<?php bloginfo('template_url'); ?>/assets/js/pjax.js"></script>		
		<?php } ?>			
		<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/comments-ajax.js"></script>				
		<?php wp_footer(); ?>
	</body>
</html>
