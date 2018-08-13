<form class="form-inline" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
	<div class="form-group">
		<input type="text" name="s" class="form-control mr5" id="terms" placeholder="<?php esc_attr_e( 'Keywords', 'business-press');  ?>" value="<?php echo get_search_query(); ?>" />
		<button type="submit" class="bpressbtn"><?php esc_attr_e( 'Search &raquo;', 'business-press');  ?></button>
	</div>
</form>