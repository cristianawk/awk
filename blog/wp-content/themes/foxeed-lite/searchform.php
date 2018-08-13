<?php 
/*
 *  Search Form 
 */
?>
<form method="get" id="searchform" class="searchform" action="<?php echo esc_url(home_url('/')); ?>">
	<input type="text" value="" placeholder="<?php _e('Search','foxeed-lite'); ?>" name="s" id="searchbox" class="searchinput" />
	<i class="fa fa-search submitsearch"></i>
</form>