<?php

function business_press_inline_css()
{
	$business_press_product_per_column = absint( get_theme_mod( 'business_press_product_per_column', '4' ) );
	
	$business_press_custom_css = "";
	
	//for sticky post
	if( get_theme_mod( 'business_press_sticky_dt_disply', '1' ) == 0 )
	{
		$business_press_custom_css .= "
		.sticky .post-date {
			display: none;
		}
		";
	}
	//for sticky post END

	// for nav toggle button position
	if( has_custom_logo() )
	{
		$business_press_custom_css .= "
		.navbar-toggle {
			margin-top: 23px;
		}
		";
	}
	// for nav toggle position END
	
	//for woo
	if( $business_press_product_per_column == 2 )
	{
		$business_press_custom_css .= "
		@media (min-width: 768px) {
			.woocommerce ul.products li.product, .woocommerce-page ul.products li.product {
				width: 46%;
			}
		}
		";
	}
	
	if( $business_press_product_per_column == 3 )
	{
		$business_press_custom_css .= "
		@media (min-width: 768px) {
			.woocommerce ul.products li.product, .woocommerce-page ul.products li.product {
				width: 30%;
				}
		}
		";
	}
	
	if( $business_press_product_per_column == 4 )
	{
		$business_press_custom_css .= "
		@media (min-width: 768px) {
			.woocommerce ul.products li.product, .woocommerce-page ul.products li.product {
				width: 22%;
			}
		}
		";
	}
	
	if( $business_press_product_per_column == 5 )
	{
		$business_press_custom_css .= "
		@media (min-width: 768px) {
			.woocommerce ul.products li.product, .woocommerce-page ul.products li.product {
				width: 16.9%;
			}
		}
		";
	}

	wp_add_inline_style( 'business-press-style-core', $business_press_custom_css );
}
add_action( 'wp_enqueue_scripts', 'business_press_inline_css' );

