<?php

/* Display a notice that can be dismissed */

// display notice and link for dismiss, if not pressed dismiss
function business_press_admin_notice()
{
	global $current_user ;
	$user_id = $current_user->ID;
	
	/* Check that the user hasn't already clicked to ignore the message */
	if( !get_user_meta( $user_id, 'business_press_ignore_notice' ) )
	{
		echo '<div class="updated"><p>';
		
		printf( __( 'Thank you for activating Business Press theme. Let start from <a target="_blank" href="%1$s">Documentation</a> | <a target="_blank" href="%2$s">Visit Demo</a> | <a href="%3$s">Hide it</a>', 'business-press' ), 'https://ewptheme.com/business-press-free-wordpress-theme/', 'http://business-press.ewptheme.com/', '?business_press_notics_ignore=0' );
		
		echo "</p></div>";
	}
}
add_action( 'admin_notices', 'business_press_admin_notice' );

// if link of ignore notice clicked, store user meta
function business_press_handle_notic()
{
	global $current_user;
	$user_id = $current_user->ID;
	if( isset( $_GET['business_press_notics_ignore']) && '0' == $_GET['business_press_notics_ignore'] )
	{
		add_user_meta( $user_id, 'business_press_ignore_notice', 'true', true );
	}
}
add_action( 'admin_init', 'business_press_handle_notic' );

//delete handle_notic user meta data on theme switch
function business_press_delete_user_meta_ignore_notice()
{
	global $current_user;
	$user_id = $current_user->ID;
	if( get_user_meta( $user_id, 'business_press_ignore_notice' ) )
	{
		delete_user_meta( $user_id, 'business_press_ignore_notice' );
	}
}
add_action('switch_theme', 'business_press_delete_user_meta_ignore_notice');
/* Display a notice that can be dismissed END */

//custom excerpt length
function business_press_custom_excerpt_length( $length )
{
	return absint( get_theme_mod( 'business_press_excerpt_length', '57' ) );
}
add_filter( 'excerpt_length', 'business_press_custom_excerpt_length', 999 );

//custom excerpt last ...... replace
function business_press_excerpt_more( $more )
{
	global $post;
	return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '"> ' . esc_attr__( 'Read more', 'business-press' ) . '&#8230;</a>';
}
add_filter( 'excerpt_more', 'business_press_excerpt_more', 1001 );

//add class="table table-bordered" to default calendar
function business_press_calendar_modify( $html )
{
	return str_replace( 'id="wp-calendar"', 'id="wp-calendar" class="table table-bordered"', $html );
}
add_filter( 'get_calendar', 'business_press_calendar_modify' );


function business_press_comment_form_fields( $fields )
{
	$commenter = wp_get_current_commenter();
	$req      = esc_attr( get_option( 'require_name_email' ) );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$html5    = current_theme_supports( 'html5', 'comment-form' ) ? 1 : 0;
		
	$fields   =  array(

		'author' => '<div class="form-group comment-form-author">' . '<label for="author">' . esc_attr__( 'Name', 'business-press' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		'<input class="form-control" placeholder="' . esc_attr__( 'Your name', 'business-press' ) . '" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
		
		'email'  => '<div class="form-group comment-form-email"><label for="email">' . esc_attr__( 'Email', 'business-press' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		'<input class="form-control" placeholder="' . esc_attr__( 'Your email', 'business-press' ) . '" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>',
		
		'url'    => '<div class="form-group comment-form-url"><label for="url">' . esc_attr__( 'Website', 'business-press' ) . '</label> ' .
		'<input class="form-control" placeholder="' . esc_attr__( 'Your website', 'business-press' ) . '" id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>'   
		
		);
return $fields;
}
add_filter( 'comment_form_default_fields', 'business_press_comment_form_fields' );


function business_press_comment_form( $args )
{
	$args['comment_field'] = '<div class="form-group comment-form-comment">
	<label for="comment">' . _x( 'Comment', 'noun' , 'business-press' ) . '<span class="required"> *</span></label> 
	<textarea class="form-control" placeholder="' . esc_attr__( 'Your comment', 'business-press' ) . '" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
	</div>';
	
	$args['class_submit'] = 'bpressbtn'; // since WP 4.1
	
	return $args;
}
add_filter( 'comment_form_defaults', 'business_press_comment_form' );

/*
* @ add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $function );
*/
function business_press_theme_options_page()
{
	add_theme_page( esc_attr__( 'Business Press Options', 'business-press') , esc_attr__( 'Business Press Options', 'business-press') , 'edit_theme_options', 'customize.php?autofocus[panel]=business_press_options', '' );
}
add_action( 'admin_menu', 'business_press_theme_options_page' );

/*
* Add Custom Shortcut Links to WordPress Toolbar
*/
function business_press_toolbar_link( $wp_admin_bar )
{
	// Don't display menu in admin bar if current_user_can not manage_options
	if( !current_user_can( 'manage_options' ) )
	{
		return;
	}
			
	// Add main link 
	$args = array(
		'id' => 'business_press_toolbar_link_main',
		'title' => __( 'Business Press Options', 'business-press' ), 
		'href' => get_dashboard_url() . 'customize.php?autofocus[panel]=business_press_options', 
	);
	$wp_admin_bar->add_node( $args );

	// Add the first child link 
	$args = array(
		'id' => 'business_press_toolbar_link_pro',
		'title' => 'Try Business Press Pro', 
		'href' => 'http://ewptheme.com/product/business-press-pro-wordpress-theme/',
		'parent' => 'business_press_toolbar_link_main', 
		'meta' => array(
			'target' => '_blank'
			)
	);
	$wp_admin_bar->add_node($args);		
}
add_action( 'admin_bar_menu', 'business_press_toolbar_link', 999 );


function business_press_the_head_callbk()
{
	?>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	
	<style type="text/css">
	.load-icon
	{
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999999;
		background: url( "<?php if( get_theme_mod( 'business_press_loading_icon_img' ) ) { echo esc_url( get_theme_mod( 'business_press_loading_icon_img' ) ); } else { echo esc_url( get_template_directory_uri() . '/images/Preloader_2.gif' ); } ?>" ) center no-repeat #fff;
	}
	</style>
	<?php
}
add_action( 'business_press_the_head', 'business_press_the_head_callbk' );

// Refeash screen on logo switch
function business_press_logo_refresh( $wp_customize ) {
	$wp_customize->get_setting( 'custom_logo' )->transport 	= 'refresh';
}
add_action( 'customize_register', 'business_press_logo_refresh' );
