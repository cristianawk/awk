<?php

function business_press_widgets_init()
{
	/* register widgets / sidebar
	* @ https://codex.wordpress.org/Function_Reference/register_sidebar
	* Usages register_sidebar( $args );
	*/
	register_sidebar( array(
		'name'			=> esc_attr__( 'Blog Sidebar', 'business-press' ),
		'id'			=> 'sidebar-1',
		'description'	=> esc_attr__( 'Widgets for Blog sidebar. If you are using Full Width Layout in customize, then this sidebar will not display.', 'business-press' ),
		'before_widget'	=> '<div id="%1$s" class="widget_sidebar_main clearfix %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h3 class="right-widget-title">',
		'after_title'	=> '</h3>',
	) );
	
	register_sidebar( array(
		'name'			=> esc_attr__( 'Page Sidebar', 'business-press' ),
		'id'			=> 'business_press_sidebar_page',
		'description'	=> esc_attr__( 'Widgets for Page sidebar. If you are using Full Width Template, then this sidebar will not display.', 'business-press' ),
		'before_widget'	=> '<div id="%1$s" class="widget_sidebar_main clearfix %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h3 class="right-widget-title">',
		'after_title'	=> '</h3>',
	) );
	
	if( class_exists( 'WooCommerce' ) )
	{
		register_sidebar( array(
			'name'			=> esc_attr__( 'Woocommerce Sidebar', 'business-press' ),
			'id'			=> 'business_press_sidebar_woo',
			'description'	=> esc_attr__( 'Widgets for Woocommerce Pages (Loop, Search and Single Product Page). If you are using Full Width Layout in customize, then this sidebar will not display.', 'business-press' ),
			'before_widget'	=> '<div id="%1$s" class="widget_sidebar_main clearfix %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h3 class="right-widget-title">',
			'after_title'	=> '</h3>',
		) );
	}

	
	$business_press_number_of_footer_widgets = absint( get_theme_mod( 'business_press_number_of_footer_widgets', '0' ) );
	if( $business_press_number_of_footer_widgets != 0 )
	{
		for( $i = 1; $i <= $business_press_number_of_footer_widgets; $i++ )
		{
			register_sidebar( array(
				'name' 			=> esc_attr__( 'Footer Widget ', 'business-press' ) . $i,
				'id'			=> 'business_press_footer_' . $i,
				'description'	=> esc_attr__( 'Widgets for footer ', 'business-press' ) . $i,
				'before_widget'	=> '<div id="%1$s" class="widgets_footer clearfix %2$s">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<h3 class="widgets_footer_title">',
				'after_title'	=> '</h3>',
			) );
		}
	}

}
add_action( 'widgets_init', 'business_press_widgets_init' );

