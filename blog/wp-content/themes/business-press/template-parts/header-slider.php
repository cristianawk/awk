<?php
if( get_the_ID() )
{
	if( get_post_meta( get_the_id(), 'business_press_slider_scode', true ) )
	{
		echo do_shortcode( wp_kses_post( get_post_meta( get_the_id(), 'business_press_slider_scode', true ) ) );
	}
}
