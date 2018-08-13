<?php

function foxeed_lite_customize_register( $wp_customize ) {

	// define image directory path
	$imagepath =  get_template_directory_uri() . '/images/';

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	
	// ====================================
	// = Background Image Size for custom-background
	// ====================================
	$wp_customize->add_setting( 'background_size', array(
		'type'				=> 'theme_mod',
		'capability' 		=> 'edit_theme_options',
		'default'       	=> 'cover',
		'theme_supports'	=> 'custom-background',
		'sanitize_callback' => 'foxeed_lite_sanitize_textarea',
	));
	$wp_customize->add_control('background_size', array(
		'section'		=> 'background_image',
		'label' 		=> __('Background Image Size','foxeed-lite'),
		'description' 	=> '',
	));
	// ====================================
	// = Foxeed Lite Theme Pannel
	// ====================================
	$wp_customize->add_panel( 'sketchthemes', array(
		'title' 	=> __( 'Foxeed Lite Options','foxeed-lite'),
		'description' => __( 'This section allows you to preview theme options that you change before saving them.','foxeed-lite'),
		'priority' 	=> 10,
	) );
	// ====================================
	// = Foxeed Lite Theme Sections
	// ====================================
	$wp_customize->add_section( 'general_section' , array(
		'title' 		=> __('General Settings','foxeed-lite'),
		'description'	=> '',
		'priority'		=> 1,
		'panel' 		=> 'sketchthemes',
	) );
	$wp_customize->add_section( 'header_section' , array(
		'title' 		=> __('Header Settings','foxeed-lite'),
		'description'	=> '',
		'priority' 		=> 2,
		'panel' 		=> 'sketchthemes',
	) );
	$wp_customize->add_section( 'home_page_section' , array(
		'title' 		=> __('Front Page Settings','foxeed-lite'),
		'description' 	=> '',
		'priority'		=> 4,
		'panel' 		=> 'sketchthemes',
	) );
	$wp_customize->add_section( 'blog_page_section' , array(
		'title' 		=> __('Blog Page Settings','foxeed-lite'),
		'description' 	=> '',
		'priority' 		=> 5,
		'panel' 		=> 'sketchthemes',
	) );
	$wp_customize->add_section( 'footer_section' , array(
		'title' 		=> __('Footer Settings','foxeed-lite'),
		'description' 	=> '',
		'priority' 		=> 6,
		'panel' 		=> 'sketchthemes',
	) );

	// ====================================
	// = General Settings Sections
	// ====================================
	$wp_customize->add_setting( 'foxeed_lite_pri_color', array(
		'type'				=> 'theme_mod',
		'capability' 		=> 'edit_theme_options',
		'default'           => '#f98b6e' ,
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'foxeed_lite_pri_color', array(
		'priority' 		=> 1,
		'section'     	=> 'general_section',
		'settings' 		=> 'foxeed_lite_pri_color',
		'label'       	=> __( 'Primary Color Scheme', 'foxeed-lite' ),
		'description' 	=> __( 'Theme Primary Color.', 'foxeed-lite' ),
	) ) );
	$wp_customize->add_setting( 'foxeed_lite_sec_color', array(
		'type'				=> 'theme_mod',
		'capability' 		=> 'edit_theme_options',
		'default'           => '#2eb9d0',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'foxeed_lite_sec_color', array(
		'priority' 		=> 2,
		'section'     	=> 'general_section',
		'settings' 		=> 'foxeed_lite_sec_color',
		'label'       	=> __( 'Secondary Color Scheme', 'foxeed-lite' ),
		'description' 	=> __( 'Theme Secondary Color.', 'foxeed-lite' ),
	) ) );
	
	if ( ! function_exists( 'has_site_icon' ) ) {

		$wp_customize->add_setting( 'foxeed_lite_favicon', array(
			'type'				=> 'theme_mod',
			'capability' 		=> 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'foxeed_lite_favicon', array(
			'priority' 		=> 3,
			'section' 		=> 'general_section',
			'settings' 		=> 'foxeed_lite_favicon',
			'label' 		=> __( 'Favicon Image', 'foxeed-lite' ),
			'description' 	=> __('An icon associated with website, typically displayed in the address bar of a browser accessing the site or next to the site name in list of bookmarks.', 'foxeed-lite' ),
		) ) );
		
	}

	// ====================================
	// = Header Settings Sections
	// ====================================
	$wp_customize->add_setting( 'foxeed_lite_logo_img', array(
		'type'				=> 'theme_mod',
		'capability' 		=> 'edit_theme_options',
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, 'foxeed_lite_logo_img', array(
		'priority' 		=> 1,
		'section' 		=> 'header_section',
		'settings' 		=> 'foxeed_lite_logo_img',
		'label' 		=> __( 'Logo Image', 'foxeed-lite' ),
		'description' 	=> __('Uplaod your beautiful logo image from here. Recomended size 220x40 px.', 'foxeed-lite' ),
	) ) );
	$wp_customize->add_setting( 'foxeed_lite_pinterest_url', array(
		'type'				=> 'theme_mod',
		'capability' 		=> 'edit_theme_options',
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'foxeed_lite_pinterest_url', array(
		'type'     		=> 'url',
		'priority' 		=> 2,
		'section'  		=> 'header_section',
		'settings' 		=> 'foxeed_lite_pinterest_url',
		'label'    		=> __( 'Pinterest URL', 'foxeed-lite' ),
		'description' 	=> '',
	) );
	$wp_customize->add_setting( 'foxeed_lite_twitter_url', array(
		'type'				=> 'theme_mod',
		'capability' 		=> 'edit_theme_options',
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'foxeed_lite_twitter_url', array(
		'type'     		=> 'url',
		'priority'	 	=> 3,
		'section'  		=> 'header_section',
		'settings' 		=> 'foxeed_lite_twitter_url',
		'label'    		=> __( 'Twitter URL', 'foxeed-lite' ),
		'description' 	=> '',
	) );
	$wp_customize->add_setting( 'foxeed_lite_tumblr_url', array(
		'type'				=> 'theme_mod',
		'capability'		=> 'edit_theme_options',
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'foxeed_lite_tumblr_url', array(
		'type'     => 'url',
		'priority' => 4,
		'section'  => 'header_section',
		'settings' 		=> 'foxeed_lite_tumblr_url',
		'label'    => __( 'Tumblr URL', 'foxeed-lite' ),
		'description' => '',
	) );
	$wp_customize->add_setting( 'foxeed_lite_facebook_url', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'foxeed_lite_facebook_url', array(
		'type'     => 'url',
		'priority' => 5,
		'section'  => 'header_section',
		'settings' 		=> 'foxeed_lite_facebook_url',
		'label'    => __( 'Facebook URL', 'foxeed-lite' ),
		'description' => '',
	) );
	$wp_customize->add_setting( 'foxeed_lite_skype_url', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'foxeed_lite_skype_url', array(
		'type'     => 'url',
		'priority' => 5,
		'section'  => 'header_section',
		'settings' 		=> 'foxeed_lite_skype_url',
		'label'    => __( 'skype URL', 'foxeed-lite' ),
		'description' => '',
	) );
	$wp_customize->add_setting( 'foxeed_lite_instagram_url', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'foxeed_lite_instagram_url', array(
		'type'     => 'url',
		'priority' => 5,
		'section'  => 'header_section',
		'settings' 		=> 'foxeed_lite_instagram_url',
		'label'    => __( 'instagram URL', 'foxeed-lite' ),
		'description' => '',
	) );
	$wp_customize->add_setting( 'foxeed_lite_vk_url', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'foxeed_lite_vk_url', array(
		'type'     => 'url',
		'priority' => 5,
		'section'  => 'header_section',
		'settings' 		=> 'foxeed_lite_vk_url',
		'label'    => __( 'vk URL', 'foxeed-lite' ),
		'description' => '',
	) );
	$wp_customize->add_setting( 'foxeed_lite_flickr_url', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'foxeed_lite_flickr_url', array(
		'type'     => 'url',
		'priority' => 5,
		'section'  => 'header_section',
		'settings' 		=> 'foxeed_lite_flickr_url',
		'label'    => __( 'flickr URL', 'foxeed-lite' ),
		'description' => '',
	) );
	$wp_customize->add_setting( 'foxeed_lite_whatsapp_url', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'foxeed_lite_whatsapp_url', array(
		'type'     => 'url',
		'priority' => 5,
		'section'  => 'header_section',
		'settings' 		=> 'foxeed_lite_whatsapp_url',
		'label'    => __( 'whatsapp URL', 'foxeed-lite' ),
		'description' => '',
	) );
	$wp_customize->add_setting( 'foxeed_lite_linkedin_url', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'foxeed_lite_linkedin_url', array(
		'type'     => 'url',
		'priority' => 5,
		'section'  => 'header_section',
		'settings' 		=> 'foxeed_lite_linkedin_url',
		'label'    => __( 'linkedin URL', 'foxeed-lite' ),
		'description' => '',
	) );
	$wp_customize->add_setting( 'foxeed_lite_googleplus_url', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'foxeed_lite_googleplus_url', array(
		'type'     => 'url',
		'priority' => 5,
		'section'  => 'header_section',
		'settings' 		=> 'foxeed_lite_googleplus_url',
		'label'    => __( 'googleplus URL', 'foxeed-lite' ),
		'description' => '',
	) );
	$wp_customize->add_setting( 'foxeed_lite_persistent_on_off', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => 'on',
		'sanitize_callback' => 'foxeed_lite_sanitize_on_off',
	) );
	$wp_customize->add_control( 'foxeed_lite_persistent_on_off', array(
		'type' => 'radio',
		'priority' => 6,
		'section' => 'header_section',
		'settings' 		=> 'foxeed_lite_persistent_on_off',
		'label' => __( 'Persistent (sticky) Header Navigation ON/OFF', 'foxeed-lite' ),
		'choices' => array(
			'on' => __('ON', 'foxeed-lite' ),
			'off'=> __('OFF', 'foxeed-lite' ),
		),
		'description' => '',
	) );
	// ====================================
	// = Home Page Settings Sections
	// ====================================
	$wp_customize->add_setting( 'foxeed_lite_home_blog_sec', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => 'on',
		'sanitize_callback' => 'foxeed_lite_sanitize_on_off',
	) );
	$wp_customize->add_control( 'foxeed_lite_home_blog_sec', array(
		'type' => 'radio',
		'priority' => 1,
		'section' => 'home_page_section',
		'label' => __( 'Blog Section ON/OFF', 'foxeed-lite' ),
		'description' => __('Enable/Disable the Blog Section on Front Page.', 'foxeed-lite' ),
		'choices' => array(
			'on' => __('ON', 'foxeed-lite' ),
			'off'=> __('OFF', 'foxeed-lite' ),
		),
		'active_callback' => 'is_front_page',
	) );
	$wp_customize->add_setting( 'foxeed_lite_home_blog_num', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'        => '8',
		'sanitize_callback' => 'foxeed_lite_sanitize_textarea',
	));
	$wp_customize->add_control('foxeed_lite_home_blog_num', array(
		'priority' => 2,
		'section' => 'home_page_section',
		'label' => __('Number of Blogs','foxeed-lite'),
		'description' => 'select number of blog post to show. Leave field empty for to show all.',
		'active_callback' => 'is_front_page',
	));
	$wp_customize->add_setting( 'foxeed_lite_home_service_sec', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => 'on',
		'sanitize_callback' => 'foxeed_lite_sanitize_on_off',
	) );
	$wp_customize->add_control( 'foxeed_lite_home_service_sec', array(
		'type' => 'radio',
		'priority' => 3,
		'section' => 'home_page_section',
		'settings' 		=> 'foxeed_lite_home_service_sec',
		'label' => __( 'Our Services Section ON/OFF', 'foxeed-lite' ),
		'description' => __('Add the "Featured Box - Foxeed Lite" widget in Home Featured Sidebar. They will be shown in this services section when it is ON', 'foxeed-lite' ),
		'choices' => array(
			'on' => __('ON', 'foxeed-lite' ),
			'off'=> __('OFF', 'foxeed-lite' ),
		),
		'active_callback' => 'is_front_page',
	) );
	// Service Section Title
	$wp_customize->add_setting( 'foxeed_lite_home_service_sec_title', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'        => __('OUR SERVICES', 'foxeed-lite'),
		'sanitize_callback' => 'foxeed_lite_sanitize_textarea',
	));
	$wp_customize->add_control('foxeed_lite_home_service_sec_title', array(
		'priority' => 4,
		'section' => 'home_page_section',
		'settings' 		=> 'foxeed_lite_home_service_sec_title',
		'label' => __('Our Services Section Title','foxeed-lite'),
		'description' => '',
		'active_callback' => 'foxeed_lite_active_home_service_sec',
	));
	// Service Section Description
	$wp_customize->add_setting( 'foxeed_lite_home_service_sec_desc', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'        => __('Lorem ipsum dolor sit amet, conscteture edipiscing elit.', 'foxeed-lite'),
		'sanitize_callback' => 'foxeed_lite_sanitize_textarea',
	));
	$wp_customize->add_control('foxeed_lite_home_service_sec_desc', array(
		'type'	=> 'textarea',
		'priority' => 5,
		'section' => 'home_page_section',
		'settings' 		=> 'foxeed_lite_home_service_sec_desc',
		'label' => __('Our Services Section Content','foxeed-lite'),
		'description' => '',
		'active_callback' => 'foxeed_lite_active_home_service_sec',
	));
	// Service Section Image
	$wp_customize->add_setting( 'foxeed_lite_home_service_sec_img', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => $imagepath.'services-bg.png',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'foxeed_lite_home_service_sec_img', array(
		'priority' => 6,
		'section' => 'home_page_section',
		'settings' 		=> 'foxeed_lite_home_service_sec_img',
		'label' => __( 'Our Services Section Background Image', 'foxeed-lite' ),
		'description' => '',
		'active_callback' => 'foxeed_lite_active_home_service_sec',
	) ) );
	// == Home Our Clients Sections ==
	$wp_customize->add_setting( 'foxeed_lite_home_client_sec', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'           => 'on',
		'sanitize_callback' => 'foxeed_lite_sanitize_on_off',
	) );
	$wp_customize->add_control( 'foxeed_lite_home_client_sec', array(
		'type' => 'radio',
		'priority' => 7,
		'section' => 'home_page_section',
		'label' => __( 'Our Clients Section ON/OFF', 'foxeed-lite' ),
		'choices' => array(
			'on' => __('ON', 'foxeed-lite' ),
			'off'=> __('OFF', 'foxeed-lite' ),
		),
		'active_callback' => 'is_front_page',
	) );
	// Client Section Title
	$wp_customize->add_setting( 'foxeed_lite_home_client_sec_title', array(
		'default'        => __('OUR SERVICES', 'foxeed-lite'),
		'sanitize_callback' => 'foxeed_lite_sanitize_textarea',
	));
	$wp_customize->add_control('foxeed_lite_home_client_sec_title', array(
		'section' => 'home_page_section',
		'label' => __('Client Section Title','foxeed-lite'),
		'active_callback' => 'foxeed_lite_active_home_client_sec',
	));
	// Client Section Description
	$wp_customize->add_setting( 'foxeed_lite_home_client_sec_desc', array(
		'default'        => __('Lorem ipsum dolor sit amet, conscteture edipiscing elit.', 'foxeed-lite'),
		'sanitize_callback' => 'foxeed_lite_sanitize_textarea',
	));
	$wp_customize->add_control('foxeed_lite_home_client_sec_desc', array(
		'type'	=> 'textarea',
		'section' => 'home_page_section',
		'label' => __('Client Section Content','foxeed-lite'),
		'active_callback' => 'foxeed_lite_active_home_client_sec',
	));
	$wp_customize->add_setting( 'foxeed_lite_home_client1_img', array(
		'default'           => $imagepath.'client-logo.png',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'foxeed_lite_home_client1_img', array(
		'section' => 'home_page_section',
		'label' => __( 'First Client Image', 'foxeed-lite' ),
		'active_callback' => 'foxeed_lite_active_home_client_sec',
	) ) );
	$wp_customize->add_setting( 'foxeed_lite_home_client2_img', array(
		'default'           => $imagepath.'client-logo.png',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'foxeed_lite_home_client2_img', array(
		'section' => 'home_page_section',
		'label' => __( 'Second Client Image', 'foxeed-lite' ),
		'active_callback' => 'foxeed_lite_active_home_client_sec',
	) ) );
	$wp_customize->add_setting( 'foxeed_lite_home_client3_img', array(
		'default'           => $imagepath.'client-logo.png',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'foxeed_lite_home_client3_img', array(
		'section'  		=> 'home_page_section',
		'label' => __( 'Third Client Image', 'foxeed-lite' ),
		'active_callback' => 'foxeed_lite_active_home_client_sec',
	) ) );
	$wp_customize->add_setting( 'foxeed_lite_home_client4_img', array(
		'default'           => $imagepath.'client-logo.png',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'foxeed_lite_home_client4_img', array(
		'section'  		=> 'home_page_section',
		'label' => __( 'Fourth Client Image', 'foxeed-lite' ),
		'active_callback' => 'foxeed_lite_active_home_client_sec',
	) ) );
	// ====================================
	// = Blog Page Settings Sections
	// ====================================
	$wp_customize->add_setting( 'foxeed_lite_blogpage_heading', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'        => __('Blog', 'foxeed-lite'),
		'sanitize_callback' => 'foxeed_lite_sanitize_textarea',
	));
	$wp_customize->add_control('foxeed_lite_blogpage_heading', array(
		'priority' => 1,
		'section' => 'blog_page_section',
		'settings' 		=> 'foxeed_lite_blogpage_heading',
		'label' => __('Blog Page Title','foxeed-lite'),
		'description' => '',
	));
	// ====================================
	// = Footer Settings Sections
	// ====================================
	$wp_customize->add_setting( 'foxeed_lite_copyright', array(
		'type'	=> 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'        => __('Copyright Text', 'foxeed-lite'),
		'transport' => 'postMessage',
		'sanitize_callback' => 'foxeed_lite_sanitize_textarea',
	));
	$wp_customize->add_control('foxeed_lite_copyright', array(
		'priority' => 1,
		'section' => 'footer_section',
		'settings' 		=> 'foxeed_lite_copyright',
		'label' => __('Copyright Text','foxeed-lite'),
		'description' => '',
	));	

}
add_action( 'customize_register', 'foxeed_lite_customize_register' );

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Foxeed Lite 1.0
 */
function foxeed_lite_customize_preview_js() {
	wp_enqueue_script( 'foxeed-lite-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20141216', true );
}
add_action( 'customize_preview_init', 'foxeed_lite_customize_preview_js' );


// sanitize textarea
function foxeed_lite_sanitize_textarea( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

function foxeed_lite_sanitize_on_off( $input ) {
	$valid = array(
		'on' => __('ON', 'foxeed-lite' ),
		'off'=> __('OFF', 'foxeed-lite' ),
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

function foxeed_lite_active_home_service_sec( $control ) {
	if ( $control->manager->get_setting('foxeed_lite_home_service_sec')->value() == 'on' && is_front_page() ) {
		return true;
	} else {
		return false;
	}
}

function foxeed_lite_active_home_client_sec( $control ) {
	if ( $control->manager->get_setting('foxeed_lite_home_client_sec')->value() == 'on' && is_front_page() ) {
		return true;
	} else {
		return false;
	}
}
?>