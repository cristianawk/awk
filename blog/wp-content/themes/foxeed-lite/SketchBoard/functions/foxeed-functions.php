<?php
/***************** EXCERPT LENGTH ************/
function foxeed_lite_excerpt_length($length) {
	return 50;
}
add_filter('excerpt_length', 'foxeed_lite_excerpt_length');


/***************** READ MORE ****************/
function foxeed_lite_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'foxeed_lite_excerpt_more');