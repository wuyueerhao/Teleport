<form role="search" method="get" class="searchform" action="<?php echo home_url( '/' ); ?>">
    <input type="text" value="<?php if(!empty($_GET['s'])) echo get_search_query(); ?>" name="s" id="s" placeholder="<?php _e( 'Search the site...' , 'beauty_dictionary' )?>" />
    <button type="submit" id="searchsubmit" class="accentcolor-text-on_hover inherit-color">
	    <i class="icon-search"></i>
    </button>
</form>