<?php
/************************************************
*  ENQUQUE CSS, FONTS AND JAVASCRIPT
************************************************/
function foxeed_lite_theme_stylesheet()
{
	global $is_IE;
	$theme = wp_get_theme();
		
	//ENQUEUE CUSTOM JS
	wp_enqueue_script('componentssimple_slide', get_template_directory_uri() .'/js/custom.js',array('jquery'),'1.0',1 );
	//ENQUEUE WoedPress JS 
	wp_enqueue_script("comment-reply");
	wp_enqueue_script("hoverIntent");
	//ENQUEUE SUPERFISH JS
	wp_enqueue_script('superfish', get_template_directory_uri().'/js/superfish.js',array('jquery'),true,'1.0');
	wp_enqueue_script('AnimatedHeader', get_template_directory_uri().'/js/cbpAnimatedHeader.js',array('jquery'),true,'1.0');
	wp_enqueue_script('waypoints',get_template_directory_uri().'/js/waypoints.js',array('jquery'),'1.0',true );
	
	//ENQUEUE CSS FOR IE BROWSER	
	if($is_IE ) {
		wp_enqueue_style( 'foxeed-lite-ie-style', get_template_directory_uri().'/css/ie-style.css', false, $theme->Version );
		wp_enqueue_style( 'font-awesome-ie7', get_template_directory_uri().'/css/font-awesome-ie7.css', false, $theme->Version );
	}
	//ENQUEUE THEME CSS
	wp_enqueue_style( 'foxeed-lite-style', get_stylesheet_uri() );
	//ENQUEUE ANIMATION AND FONT CSS
	wp_enqueue_style('animation', get_template_directory_uri().'/css/foxeed-animation.css', false, $theme->Version);
	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/css/font-awesome.css', false, $theme->Version);
	//ENQUEUE SUPERFISH  CSS
	wp_enqueue_style( 'superfish', get_template_directory_uri().'/css/superfish.css', false, $theme->Version);
	wp_enqueue_style( 'bootstrap-responsive', get_template_directory_uri().'/css/bootstrap-responsive.css', false, $theme->Version);
	//ENQUEUE GOOGLE FONTS
	wp_enqueue_style('google-Fonts-Oxygen','//fonts.googleapis.com/css?family=Oxygen:400,700,300&subset=latin,latin-ext', false, $theme->Version);
	wp_enqueue_style('google-Fonts-RobotoCondensed','//fonts.googleapis.com/css?family=Roboto+Condensed:400,700italic,700,400italic,300italic,300&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic', false, $theme->Version);
}
add_action('wp_enqueue_scripts', 'foxeed_lite_theme_stylesheet');

// ENQUEUE STYLE FOR HEADER
function foxeed_lite_head() {
	if ( ! function_exists( 'has_site_icon' ) ) {
		// Output old, custom favicon feature.
		if( get_theme_mod( 'foxeed_lite_favicon', '' ) != '' ) {
			$foxeed_lite_favicon =  esc_url( get_theme_mod( 'foxeed_lite_favicon' ) );
			echo "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"$foxeed_lite_favicon[0]\"/>\n";
		}
	}
	require_once(get_template_directory().'/includes/foxeed-custom-css.php');
}
add_action('wp_head', 'foxeed_lite_head');
?>