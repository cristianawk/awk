<?php

/**
 * Enqueue scripts and styles.
 */
function business_press_scripts()
{
	
	global $business_press_version;
	
	//wp_enqueue_style( $handle, $src, $deps, $ver, $media );
	
	//Load bootstrap css
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '3.3.6', 'all' );
	
	//Load font-awesome file
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array(), '4.7.0', 'all' );
	
	// Load default css file
	wp_enqueue_style( 'business-press-style-default', get_stylesheet_uri(), array( 'bootstrap', 'font-awesome' ), $business_press_version, 'all' );
	
	
	//Load css/style.css file
	wp_enqueue_style( 'business-press-style-core', get_template_directory_uri() . '/css/style.css', array( 'bootstrap', 'font-awesome' ), $business_press_version, 'all' );
	
	//Load woo css file
	wp_enqueue_style( 'business-press-style-woo', get_template_directory_uri() . '/css/woo-css.css', array( 'bootstrap', 'font-awesome' ), $business_press_version, 'all' );
	
	/* Load scripts
	* @ https://codex.wordpress.org/Function_Reference/wp_enqueue_script
	* Usages wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
	*/
	
	// Load bootstrap js
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ), '3.3.6', true );
	
	// Load business-press script file
	wp_enqueue_script( 'business-press-script', get_template_directory_uri() . '/js/script.js', array( 'jquery' ), $business_press_version, true );

	// Load html5shiv
	wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/js/html5shiv.js', array(), '3.7.3', false );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );

	// Load respond js
	wp_enqueue_script( 'respond', get_template_directory_uri() . '/js/respond.js', array(), $business_press_version, false );
	wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );
	
	//load comment-reply js
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
	{
		wp_enqueue_script( 'comment-reply' );
	}
	
	
	// Load stickyheader js depends on jquery and if enabled by customizer and if we are not on landing page template
	if( get_theme_mod( 'business_press_stickyheader', '1' ) == 1 && !is_page_template( 'template-landing-page.php' ) )
	{
		wp_enqueue_script( 'business-press-stickyheader', get_template_directory_uri() . '/js/stickyheader.js', array( 'jquery' ), $business_press_version, true );
	}
	
	// Load loadingicon js depends on jquery and if enabled by customizer
	if( get_theme_mod( 'business_press_loading_icon', '0' ) == 1 )
	{
		wp_enqueue_script( 'business-press-loadingicon', get_template_directory_uri() . '/js/loadingicon.js', array( 'jquery' ), $business_press_version, false );
	}
	
	// Load backtotop js depends on jquery and if enabled by customizer
	if( get_theme_mod( 'business_press_back_to_top', '1' ) == 1 )
	{
		wp_enqueue_script( 'business-press-backtotop', get_template_directory_uri() . '/js/backtotop.js', array( 'jquery' ), $business_press_version, true );
	}

	// Load side bar menu js depends on jquery and if enabled by customizer and not on landing page
	if( get_theme_mod( 'sb_menu_onoff', '1' ) == 1 && !is_page_template( 'template-landing-page.php' ) )
	{
		wp_enqueue_script( 'business-press-sidebarmenu', get_template_directory_uri() . '/js/sidebarmenu.js', array( 'jquery' ), $business_press_version, true );
	}

	// CSP Search js depends on jquery and if enabled by customizer and not on landing page
	if( get_theme_mod( 'top_bar_seach_icon', '1' ) == 1 && get_theme_mod( 'business_press_display_top_bar', '1' ) == 1 && !is_page_template( 'template-landing-page.php' ) )
	{
		wp_enqueue_script( 'business-press-csp-search', get_template_directory_uri() . '/js/scpsearch.js', array( 'jquery' ), $business_press_version, true );
	}
	
}
add_action( 'wp_enqueue_scripts', 'business_press_scripts' );

function business_press_wp_admin_style()
{
	global $business_press_version;
	
	// Load page meta box control - hide meta box section for landing page template
	wp_enqueue_script( 'business-press-admin-page-metabox-control', get_template_directory_uri() . '/js/admin-page-meta-box-control.js', array( 'jquery' ), $business_press_version, true );

}
add_action( 'admin_enqueue_scripts', 'business_press_wp_admin_style' );

