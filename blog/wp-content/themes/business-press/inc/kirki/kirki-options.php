<?php
//set Kirki config
Kirki::add_config( 'business_press_config', array(
	'capability'    => 'edit_theme_options',
	'option_type'   => 'theme_mod',
) );

//set logo in customizer header
function business_press_configuration_sample_styling( $config )
{
	return wp_parse_args( array(
		'logo_image'   => get_template_directory_uri() . '/images/logo_for_kirki.png',
		'description'  => __( 'Business Press Theme by <a target="_blank" href="http://ewptheme.com/">EWP Theme</a>.', 'business-press' ),
	), $config );
}
add_filter( 'kirki/config', 'business_press_configuration_sample_styling' );

//the main panel
Kirki::add_panel( 'business_press_options', array(
    'priority'    => 10,
    'title'       => esc_attr__( 'Business Press Options', 'business-press' ),
    'description' => esc_attr__( 'All options of Business Press theme', 'business-press' ),
) );

//business_press pro info section
Kirki::add_section( 'business_press_pro_sec', array(
	'title'          => 'Try Business Press Pro',
	'panel'          => 'business_press_options', // Not typically needed.
	'priority'       => 10,
	'capability'     => 'edit_theme_options',
	'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'business_press_pro_custom_fld',
	'label'       => 'Business Press Pro is Available',
	'section'     => 'business_press_pro_sec',
	'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">Business Press Pro version is fully compatible with premium plugins like Visual Composer Page Builder, Massive Addons for Visual Composer, Revolution Slider etc and also have advance theme options like options to change credit text / link, all color options etc. so let <a target="_blank" href="http://ewptheme.com/product/business-press-pro-wordpress-theme/">Try Business Press Pro</a> Version.</div>',
	'priority'    => 10,
) );

//business_press pro info section END

//typography
Kirki::add_section( 'typography_options', array(
	'title'          => esc_attr__( 'Typography Options', 'business-press' ),
	'panel'          => 'business_press_options', // Not typically needed.
	'priority'       => 10,
	'capability'     => 'edit_theme_options',
	'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_body_typog',
	'label'       => esc_attr__( 'Body Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Lora',
		'variant'        => 'regular'
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => 'body',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_body_h1_typog',
	'label'       => esc_attr__( 'H1 / Headline 1 Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '22px',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-transform' => 'uppercase'
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => 'body h1, .h1',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_body_h2_typog',
	'label'       => esc_attr__( 'H2 / Headline 2 Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '22px',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-transform' => 'uppercase'
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => 'body h2, .h2',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_body_h3_typog',
	'label'       => esc_attr__( 'H3 / Headline 3 Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '22px',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-transform' => 'uppercase'
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => 'body h3, .h3',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_body_h4_typog',
	'label'       => esc_attr__( 'H4 / Headline 4 Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '20px',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-transform' => 'inherit'
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => 'body h4, .h4',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_body_h5_typog',
	'label'       => esc_attr__( 'H5 / Headline 5 Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '20px',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-transform' => 'inherit'
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => 'body h5, .h5',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_body_h6_typog',
	'label'       => esc_attr__( 'H6 / Headline 6 Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Arvo',
		'variant'        => 'regular',
		'font-size'      => '20px',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-transform' => 'inherit'
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => 'body h6, .h6',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_body_p_typog',
	'label'       => esc_attr__( 'Paragraph Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Droid Sans',
		'variant'        => 'regular',
		'font-size'      => '16px',
		'line-height'    => '1.7',
		'letter-spacing' => '0',
		'subsets'        => array( 'latin-ext' ),
		'text-transform' => 'inherit',
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => '#maincontainer p',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_top_bar_typog',
	'label'       => esc_attr__( 'Top Bar Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Roboto',
		'variant'        => 'regular',
		'font-size'      => '15px',
		'line-height'    => '22px',
		'letter-spacing' => '0px',
		'text-transform' => 'inherit',
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => '#topbar',
		),
	),
	'transport' => 'auto',
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_display_top_bar',
			'operator' => '==',
			'value'    => 1,
		)
	)
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_top_menu_typog',
	'label'       => esc_attr__( 'Main Menu Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Rajdhani',
		'variant'        => '500',
		'font-size'      => '17px',
		'text-transform' => 'uppercase'
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => '#navbar ul.navbar-nav li a',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'sb_menu_typo',
	'label'       => esc_attr__( 'Sidebar Menu Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Rajdhani',
		'variant'        => '500',
		'font-size'      => '18px',
		'line-height'    => '25px',
		'letter-spacing' => '0.1px',
		'text-transform' => 'inherit',
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => '.side-menu-menu-wrap ul li a',
		),
	),
	'transport' => 'auto',
	'active_callback'  => array(
		array(
			'setting'  => 'sb_menu_onoff',
			'operator' => '==',
			'value'    => 1,
		)
	)
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_widget_ul_ol_typog',
	'label'       => esc_attr__( 'Widgets UL/OL Typography', 'business-press' ),
	'description' => esc_attr__( 'Widgets Unordered List / Ordered List Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Roboto',
		'variant'        => 'regular',
		'font-size'      => '15px',
		'line-height'    => '1.5',
		'letter-spacing' => '0.1px',
		'text-transform' => 'inherit'
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => '.widget_sidebar_main ul li, .widget_sidebar_main ol li, .widgets_footer ul li, .widgets_footer ol li',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'body_ul_ol_li_typog',
	'label'       => esc_attr__( 'Container UL/OL Typography', 'business-press' ),
	'description' => esc_attr__( 'Typography for list in main contents.', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Droid Sans',
		'variant'        => 'regular',
		'font-size'      => '15px',
		'line-height'    => '1.2',
		'letter-spacing' => '0',
		'subsets'        => array( 'latin-ext' ),
		'text-transform' => 'inherit',
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => '.entry-content ul li, .entry-content ol li',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_mn_footer_typog',
	'label'       => esc_attr__( 'Footer Widgets Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Roboto',
		'variant'        => 'regular',
		'font-size'      => '15px',
		'line-height'    => '1.7',
		'letter-spacing' => '0',
		'text-transform' => 'inherit'
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => '#footer',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'typography',
	'settings'    => 'business_press_cprt_footer_typog',
	'label'       => esc_attr__( 'Footer Copyright Typography', 'business-press' ),
	'section'     => 'typography_options',
	'default'     => array(
		'font-family'    => 'Roboto',
		'variant'        => 'regular',
		'font-size'      => '15px',
		'line-height'    => '1',
		'letter-spacing' => '0',
		'text-transform' => 'inherit'
	),
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => '#copyright',
		),
	),
	'transport' => 'auto',
) );
//typography END

//color options
Kirki::add_section( 'color_options', array(
	'title'          => esc_attr__( 'Color Options', 'business-press' ),
	'panel'          => 'business_press_options', // Not typically needed.
	'priority'       => 10,
	'capability'     => 'edit_theme_options',
	'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'color',
	'settings'    => 'business_press_default_a_color',
	'label'       => esc_attr__( 'Default Color of Links', 'business-press' ),
	'section'     => 'color_options',
	'default'     => '#44C47E',
	'choices'     => array(
		'alpha' => true,
	),
	'priority'    => 10,
	'output' => array(
		array(
			'element'	=> 'body a, .woocommerce .star-rating span, .navbar-default .navbar-brand',
			'property'	=> 'color',
		),
		array(
			'element'  => '.widget_sidebar_main ul li::before',
			'property' => 'color',
		),
		array(
			'element'  => '.navigation.pagination .nav-links .page-numbers, .navigation.pagination .nav-links .page-numbers:last-child',
			'property' => 'border-color',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'color',
	'settings'    => 'business_press_default_a_hover_color',
	'label'       => esc_attr__( 'Default Mouse Over Color of Links', 'business-press' ),
	'section'     => 'color_options',
	'default'     => '#00c44b',
	'choices'     => array(
		'alpha' => true,
	),
	'priority'    => 10,
	'output' => array(
		array(
			'element'  => 'body a:hover, body a:focus, .widget_sidebar_main ul li:hover::before, .navbar-default .navbar-brand:hover, .navbar-default .navbar-brand:focus',
			'property' => 'color',
		),
	),
	'transport' => 'auto',
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'business_press_info_pro_clr',
	'label'       => esc_attr__( 'More color options', 'business-press' ),
	'section'     => 'color_options',
	'default'     => '<div style="padding: 10px;background-color: #333; color: #fff; border-radius: 8px;">More color options like Title / Headline Color, Button Color, Menu Color, Title Bar Color, Top Bar color, Header color, Footer Widget / Copyright color etc are available in <a target="_blank" href="http://ewptheme.com/product/business-press-pro-wordpress-theme/">Business Press Pro Version</a>.</div>',
	'priority'    => 10,
) );
//color options END

//top bar
Kirki::add_section( 'top_bar', array(
	'title'          => esc_attr__( 'Top Bar Options', 'business-press' ),
	'panel'          => 'business_press_options', // Not typically needed.
	'priority'       => 10,
	'capability'     => 'edit_theme_options',
	'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'business_press_display_top_bar',
	'label'       => esc_attr__( 'Top Bar', 'business-press' ),
	'description' => esc_attr__( 'Enable/Disable Top Bar', 'business-press' ),
	'section'     => 'top_bar',
	'default'     => '1',
	'priority'    => 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'business_press_display_sicons_top_bar',
	'label'       => esc_attr__( 'Social Icons', 'business-press' ),
	'description' => esc_attr__( 'Enable/Disable Social Icons', 'business-press' ),
	'section'     => 'top_bar',
	'default'     => '1',
	'priority'    => 10,
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_display_top_bar',
			'operator' => '==',
			'value'    => 1,
		),
	)
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 's_link_open',
	'label'       => esc_attr__( 'Social Links in New Tab?', 'business-press' ),
	'description' => esc_attr__( 'Open social links in new tab or same.', 'business-press' ),
	'section'     => 'top_bar',
	'default'     => '1',
	'priority'    => 10,
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_display_top_bar',
			'operator' => '==',
			'value'    => 1,
		),
		array(
			'setting'  => 'business_press_display_sicons_top_bar',
			'operator' => '==',
			'value'    => 1,
		),
	)
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'select',
	'settings'    => 'business_press_tpbr_left_view',
	'label'       => esc_attr__( 'Top Bar Left Content View', 'business-press' ),
	'description' => esc_attr__( 'Simply phone, email or Text/HTML or Disable ?', 'business-press' ),
	'section'     => 'top_bar',
	'default'     => '1',
	'priority'    => 10,
	'choices'     => array(
		'1' => esc_attr__( 'Text / HTML', 'business-press' ),
		'2' => esc_attr__( 'Phone and Email', 'business-press' ),
		'3' => esc_attr__( 'Disable', 'business-press' ),
	),
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_display_top_bar',
			'operator' => '==',
			'value'    => 1,
		),
	)
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'business_press_tpbr_lft_phne',
	'label'			=> esc_attr__( 'Phone Number', 'business-press' ),
	'description' 	=> esc_attr__( 'Leave empty for disable.', 'business-press' ),
	'section'		=> 'top_bar',
	'default'		=> '0123456789',
	'priority'		=> 10,
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_display_top_bar',
			'operator' => '==',
			'value'    => 1,
		),
		array(
			'setting'  => 'business_press_tpbr_left_view',
			'operator' => '==',
			'value'    => 2,
		),
	)
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'business_press_tpbr_lft_email',
	'label'			=> esc_attr__( 'Email Address', 'business-press' ),
	'description' 	=> esc_attr__( 'Leave empty for disable.', 'business-press' ),
	'section'		=> 'top_bar',
	'default'		=> 'info@example.com',
	'priority'		=> 10,
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_display_top_bar',
			'operator' => '==',
			'value'    => 1,
		),
		array(
			'setting'  => 'business_press_tpbr_left_view',
			'operator' => '==',
			'value'    => 2,
		),
	)
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'editor',
	'settings'    => 'business_press_top_bar_left_content',
	'label'       => esc_attr__( 'Top Bar Left Content', 'business-press' ),
	'description' => esc_attr__( 'Content/HTML of Top Bar Left Side | Leave empty for disable', 'business-press' ),
	'section'     => 'top_bar',
	'default'     => '<p><span class="fa fa-phone"></span> ' . __( 'Call:', 'business-press') . ' <a href="tel:0123456789">0123456789</a> | <span class="fa fa-at"></span> ' . __( 'Email:', 'business-press') . ' <a href="mailto:info@example.com">info@example.com</a></p>',
	'priority'    => 10,
	'transport' => 'postMessage',
	'js_vars'   => array(
		array(
			'element'  => '.topbar_ctmzr',
			'function' => 'html',
		),
	),
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_display_top_bar',
			'operator' => '==',
			'value'    => 1,
		),
		array(
			'setting'  => 'business_press_tpbr_left_view',
			'operator' => '==',
			'value'    => 1,
		),
	)
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'top_bar_seach_icon',
	'label'       => esc_attr__( 'Search Icon', 'business-press' ),
	'description' => esc_attr__( 'Enable/Disable Search Icon', 'business-press' ),
	'section'     => 'top_bar',
	'default'     => '1',
	'priority'    => 10,
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_display_top_bar',
			'operator' => '==',
			'value'    => 1,
		),
	)
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'bpress_info_topbar',
	'label'       => esc_attr__( 'More Options', 'business-press' ),
	'section'     => 'top_bar',
	'default'     => '<div style="padding: 10px;background-color: #333; color: #fff; border-radius: 8px;">More options are available in <a target="_blank" href="http://ewptheme.com/product/business-press-pro-wordpress-theme/">Business Press Pro Version</a>.</div>',
	'priority'    => 10,
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_display_top_bar',
			'operator' => '==',
			'value'    => 1,
		)
	)
) );
//top bar END

//header
Kirki::add_section( 'header_settings', array(
	'title'          => esc_attr__( 'Header Options', 'business-press' ),
	'panel'          => 'business_press_options', // Not typically needed.
	'priority'       => 10,
	'capability'     => 'edit_theme_options',
	'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'business_press_stickyheader',
	'label'       => esc_attr__( 'Sticky Header', 'business-press' ),
	'description' => esc_attr__( 'Enable/Disable Sticky Header (Due to small screen, we have disabled it for small screen devices)', 'business-press' ),
	'section'     => 'header_settings',
	'default'     => '1',
	'priority'    => 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'bpress_info_hdr',
	'label'       => esc_attr__( 'More Options', 'business-press' ),
	'section'     => 'header_settings',
	'default'     => '<div style="padding: 10px;background-color: #333; color: #fff; border-radius: 8px;">More options are available in <a target="_blank" href="http://ewptheme.com/product/business-press-pro-wordpress-theme/">Business Press Pro Version</a>.</div>',
	'priority'    => 10,
) );
//header END

//title bar
Kirki::add_section( 'titlbar_options', array(
	'title'          => esc_attr__( 'Title Bar Options', 'business-press' ),
	'panel'          => 'business_press_options', // Not typically needed.
	'priority'       => 10,
	'capability'     => 'edit_theme_options',
	'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'business_press_titlebar_showhide',
	'label'       => esc_attr__( 'Title bar', 'business-press' ),
	'description' => esc_attr__( 'Enable/Disable Title bar', 'business-press' ),
	'section'     => 'titlbar_options',
	'default'     => '1',
	'priority'    => 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'business_press_breadcrumbx_setting',
	'label'       => esc_attr__( 'Breadcrumb', 'business-press' ),
	'description' => esc_attr__( 'Enable/Disable Breadcrumb on title bar', 'business-press' ),
	'section'     => 'titlbar_options',
	'default'     => '1',
	'priority'    => 10,
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_titlebar_showhide',
			'operator' => '==',
			'value'    => 1,
			),
		)
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'textarea',
	'settings'    => 'business_press_frntpg_hdlin',
	'label'       => esc_attr__( 'Front page headline', 'business-press' ),
	'section'     => 'titlbar_options',
	'default'     => __( 'Headline: Customizable', 'business-press' ),
	'priority'    => 10,
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_titlebar_showhide',
			'operator' => '==',
			'value'    => 1,
			),
		),
	'transport' => 'postMessage',
	'js_vars'   => array(
		array(
			'element'  => '.frnt_pg_hdline_ctmzr',
			'function' => 'html',
		),
	),
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'bpress_info_titlebar',
	'label'       => esc_attr__( 'More Options', 'business-press' ),
	'section'     => 'titlbar_options',
	'default'     => '<div style="padding: 10px;background-color: #333; color: #fff; border-radius: 8px;">More options are available in <a target="_blank" href="http://ewptheme.com/product/business-press-pro-wordpress-theme/">Business Press Pro Version</a>.</div>',
	'priority'    => 10,
) );
//title bar END

//social profile
Kirki::add_section( 'social_options', array(
	'title'          => esc_attr__( 'Social Profile', 'business-press' ),
	'panel'          => 'business_press_options', // Not typically needed.
	'priority'       => 10,
	'capability'     => 'edit_theme_options',
	'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'business_press_social_profile_link_facebook',
	'label'			=> esc_attr__( 'Facebook Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> 'http://facebook.com',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'business_press_social_profile_link_twitter',
	'label'			=> esc_attr__( 'Twitter Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> 'http://twitter.com',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'business_press_social_profile_link_youtube',
	'label'			=> esc_attr__( 'YouTube Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> 'http://youtube.com',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'social_profile_link_vk',
	'label'			=> esc_attr__( 'VK Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'business_press_social_profile_link_googleplus',
	'label'			=> esc_attr__( 'Google Plus Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'business_press_social_profile_link_linkedin',
	'label'			=> esc_attr__( 'Linkedin Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'business_press_social_profile_link_pinterest',
	'label'			=> esc_attr__( 'Pinterest Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_instagram',
	'label'			=> esc_attr__( 'Instagram Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_telegram',
	'label'			=> esc_attr__( 'Telegram Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_snapchat',
	'label'			=> esc_attr__( 'Snapchat Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_flickr',
	'label'			=> esc_attr__( 'Flickr Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_reddit',
	'label'			=> esc_attr__( 'Reddit Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_tumblr',
	'label'			=> esc_attr__( 'Tumblr Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_yelp',
	'label'			=> esc_attr__( 'Yelp Link', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'sprofile_link_whatsappno',
	'label'			=> esc_attr__( 'WhatsApp Number', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'text',
	'settings'		=> 'business_press_social_profile_link_skype',
	'label'			=> esc_attr__( 'Skype Id', 'business-press' ),
	'description'	=> esc_attr__( 'Leave empty for disable', 'business-press' ),
	'section'		=> 'social_options',
	'default'		=> '',
	'priority'		=> 10,
) );
//social profile END


// Blog
Kirki::add_section( 'blog_options', array(
	'title'          => esc_attr__( 'Blog Options', 'business-press' ),
	'panel'          => 'business_press_options', // Not typically needed.
	'priority'       => 10,
	'capability'     => 'edit_theme_options',
	'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'			=> 'toggle',
	'settings'		=> 'business_press_archive_post_thumbnail',
	'label'			=> esc_attr__( 'Thumbnail on Archive Post', 'business-press' ),
	'description'	=> esc_attr__( 'Enable/Disable Thumbnail on Archive/Loop Page', 'business-press' ),
	'section'		=> 'blog_options',
	'default'		=> '1',
	'priority'		=> 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'business_press_single_post_thumbnail',
	'label'       => esc_attr__( 'Thumbnail on Single Post', 'business-press' ),
	'description' => esc_attr__( 'Enable/Disable Thumbnail on Single Post', 'business-press' ),
	'section'     => 'blog_options',
	'default'     => '1',
	'priority'    => 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'business_press_posts_meta_disply',
	'label'       => esc_attr__( 'Display Post Meta on Archive', 'business-press' ),
	'description' => esc_attr__( 'Show/Hide post meta on archive / loop like: author, category, date.', 'business-press' ),
	'section'     => 'blog_options',
	'default'     => '1',
	'priority'    => 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'business_press_post_meta_disply',
	'label'       => esc_attr__( 'Display Post Meta on Single Post', 'business-press' ),
	'description' => esc_attr__( 'Show/Hide post meta on single post like: author, category, date.', 'business-press' ),
	'section'     => 'blog_options',
	'default'     => '1',
	'priority'    => 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'business_press_sticky_dt_disply',
	'label'       => esc_attr__( 'Display Sticky Post Date', 'business-press' ),
	'description' => esc_attr__( 'Show/Hide date of sticky post on archive/loop page.', 'business-press' ),
	'section'     => 'blog_options',
	'default'     => '1',
	'priority'    => 10,
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_posts_meta_disply',
			'operator' => '==',
			'value'    => 1,
			),
		)
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'select',
	'settings'    => 'business_press_post_date_view',
	'label'       => esc_attr__( 'Post Date View', 'business-press' ),
	'section'     => 'blog_options',
	'default'     => '1',
	'priority'    => 10,
	'choices'     => array(
		'1' => esc_attr__( 'Display Updated Date', 'business-press' ),
		'2' => esc_attr__( 'Display Publish Date', 'business-press' ),
	),
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'select',
	'settings'    => 'excerpt_or_content',
	'label'       => esc_attr__( 'Display Excerpt or Content on Archive', 'business-press' ),
	'section'     => 'blog_options',
	'default'     => 'excerpt',
	'priority'    => 10,
	'choices'     => array(
		'excerpt' => esc_attr__( 'Display Excerpt', 'business-press' ),
		'content' => esc_attr__( 'Display Content', 'business-press' ),
	),
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'number',
	'settings'    => 'business_press_excerpt_length',
	'label'       => esc_attr__( 'Excerpt Length', 'business-press' ),
	'description' => esc_attr__( 'How much words you want to display on Archive page?', 'business-press' ),
	'section'     => 'blog_options',
	'default'     => 57,
	'choices'     => array(
		'min'  => 1,
		'step' => 1,
	),
	'active_callback'  => array(
		array(
			'setting'  => 'excerpt_or_content',
			'operator' => '==',
			'value'    => 'excerpt',
		),
	),
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'select',
	'settings'    => 'display_archive_pagination',
	'label'       => esc_attr__( 'Display Pagination on Archive', 'business-press' ),
	'description' => esc_attr__( 'Which type of pagination, you want to display?', 'business-press' ),
	'section'     => 'blog_options',
	'default'     => 'nextprev',
	'priority'    => 10,
	'choices'     => array(
		'nextprev'	=> esc_attr__( 'Next Previous Pagination', 'business-press' ),
		'number' 	=> esc_attr__( 'Number Pagination', 'business-press' ),
	),
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'radio-image',
	'settings'    => 'business_press_blog_archive_layout',
	'label'       => esc_attr__( 'Archive / Loop Layout', 'business-press' ),
	'section'     => 'blog_options',
	'default'     => 'rights',
	'priority'    => 10,
	'choices'     => array(
		'fullw'	  => get_template_directory_uri() . '/images/fullw.png',
		'rights'  => get_template_directory_uri() . '/images/rights.png',
		'lefts'   => get_template_directory_uri() . '/images/lefts.png',
	),
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'radio-image',
	'settings'    => 'business_press_blog_single_layout',
	'label'       => esc_attr__( 'Single Post Layout', 'business-press' ),
	'section'     => 'blog_options',
	'default'     => 'rights',
	'priority'    => 10,
	'choices'     => array(
		'fullw'	  => get_template_directory_uri() . '/images/fullw.png',
		'rights'  => get_template_directory_uri() . '/images/rights.png',
		'lefts'   => get_template_directory_uri() . '/images/lefts.png',
	),
) );

Kirki::add_field( 'business_press_config', array(
	'type'     => 'textarea',
	'settings' => 'business_press_comment_panel_title',
	'label'    => esc_attr__( 'Comment Section Headline', 'business-press' ),
	'section'  => 'blog_options',
	'default'  => __( 'Have any Question or Comment?', 'business-press' ),
	'priority' => 10,
	'transport' => 'postMessage',
	'js_vars'   => array(
		array(
			'element'  => '.cmnthdlne_ctmzr',
			'function' => 'html',
		),
	),
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'bpress_info_bblog',
	'label'       => esc_attr__( 'More Options', 'business-press' ),
	'section'     => 'blog_options',
	'default'     => '<div style="padding: 10px;background-color: #333; color: #fff; border-radius: 8px;">More options are available in <a target="_blank" href="http://ewptheme.com/product/business-press-pro-wordpress-theme/">Business Press Pro Version</a>.</div>',
	'priority'    => 10,
) );
// Blog END

//Sidebar menu Options
Kirki::add_section( 'sidebarmenu_options', array(
	'title'          => esc_attr__( 'Sidebar Menu Options', 'business-press' ),
	'panel'          => 'business_press_options', // Not typically needed.
	'priority'       => 10,
	'capability'     => 'edit_theme_options',
	'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'sb_menu_onoff',
	'label'       => esc_attr__( 'Side Bar Menu', 'business-press' ),
	'description' => esc_attr__( 'Enable/Disable Side Bar Menu', 'business-press' ),
	'section'     => 'sidebarmenu_options',
	'default'     => '1',
	'priority'    => 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'bp_info_sb_menu',
	'label'       => esc_attr__( 'Color Options', 'business-press' ),
	'section'     => 'sidebarmenu_options',
	'default'     => '<div style="padding: 10px;background-color: #333; color: #fff; border-radius: 8px;">Color options are available in <a target="_blank" href="http://ewptheme.com/product/business-press-pro-wordpress-theme/">Business Press Pro Version</a>.</div>',
	'priority'    => 10,
) );
//Sidebar menu Options END

//woocommerce section
if( class_exists( 'WooCommerce' ) )
{
	Kirki::add_section( 'woocommerce_options', array(
		'title'          => esc_attr__( 'Woocommerce Options', 'business-press' ),
		'panel'          => 'business_press_options', // Not typically needed.
		'priority'       => 10,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '', // Rarely needed.
	) );
	
	Kirki::add_field( 'business_press_config', array(
		'type'        => 'toggle',
		'settings'    => 'business_press_display_shop_link_top_bar',
		'label'       => esc_attr__( 'Display shop icon in Top Bar?', 'business-press' ),
		'description' => esc_attr__( 'Enable/Disable shop icon in Top Bar', 'business-press' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
		'priority'    => 10,
		'active_callback'  => array(
		array(
			'setting'  => 'business_press_display_top_bar',
			'operator' => '==',
			'value'    => 1,
			),
		)
	) );
	
	Kirki::add_field( 'business_press_config', array(
		'type'        => 'toggle',
		'settings'    => 'business_press_display_cart_link_top_bar',
		'label'       => esc_attr__( 'Display cart icon in Top Bar?', 'business-press' ),
		'description' => esc_attr__( 'Enable/Disable cart icon in Top Bar', 'business-press' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
		'priority'    => 10,
		'active_callback'  => array(
		array(
			'setting'  => 'business_press_display_top_bar',
			'operator' => '==',
			'value'    => 1,
			),
		)
	) );
	
	Kirki::add_field( 'business_press_config', array(
		'type'        => 'toggle',
		'settings'    => 'business_press_display_myaccount_link_top_bar',
		'label'       => esc_attr__( 'Display My Account icon in Top Bar?', 'business-press' ),
		'description' => esc_attr__( 'Enable/Disable My Account icon in Top Bar', 'business-press' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
		'priority'    => 10,
		'active_callback'  => array(
		array(
			'setting'  => 'business_press_display_top_bar',
			'operator' => '==',
			'value'    => 1,
			),
		)
	) );

	Kirki::add_field( 'business_press_config', array(
		'type'        => 'toggle',
		'settings'    => 'display_related_prdkt',
		'label'       => esc_attr__( 'Related Products', 'business-press' ),
		'description' => esc_attr__( 'Enable/Disable WooCommerce Related Products.', 'business-press' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
		'priority'    => 10,
	) );

	Kirki::add_field( 'business_press_config', array(
		'type'        => 'toggle',
		'settings'    => 'support_gallery_zoom',
		'label'       => esc_attr__( 'Gallery Zoom', 'business-press' ),
		'description' => esc_attr__( 'Enable/Disable gallery zoom support on single product.', 'business-press' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
		'priority'    => 10,
	) );

	Kirki::add_field( 'business_press_config', array(
		'type'        => 'toggle',
		'settings'    => 'support_gallery_lightbox',
		'label'       => esc_attr__( 'Gallery Light Box', 'business-press' ),
		'description' => esc_attr__( 'Enable/Disable gallery light box support on single product.', 'business-press' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
		'priority'    => 10,
	) );

	Kirki::add_field( 'business_press_config', array(
		'type'        => 'toggle',
		'settings'    => 'support_gallery_slider',
		'label'       => esc_attr__( 'Gallery Slider', 'business-press' ),
		'description' => esc_attr__( 'Enable/Disable gallery slider support on single product.', 'business-press' ),
		'section'     => 'woocommerce_options',
		'default'     => '1',
		'priority'    => 10,
	) );

	Kirki::add_field( 'business_press_config', array(
		'type'        => 'number',
		'settings'    => 'business_press_product_per_page',
		'label'       => esc_attr__( 'Number of products display on loop page', 'business-press' ),
		'description' => esc_attr__( 'How much products you want to display on loop page?', 'business-press' ),
		'section'     => 'woocommerce_options',
		'default'     => 12,
		'choices'     => array(
			'min'  => 0,
			'max'  => 100,
			'step' => 1,
		),
	) );
	
	Kirki::add_field( 'business_press_config', array(
		'type'        => 'slider',
		'settings'    => 'business_press_product_per_column',
		'label'       => esc_attr__( 'Number of products display per column', 'business-press' ),
		'description' => esc_attr__( 'How much products you want to display in single line?', 'business-press' ),
		'section'     => 'woocommerce_options',
		'default'     => 4,
		'priority'    => 10,
		'choices'     => array(
			'min'  => '2',
			'max'  => '5',
			'step' => '1',
			),
	) );

	Kirki::add_field( 'business_press_config', array(
		'type'        => 'radio-image',
		'settings'    => 'business_press_woo_layout',
		'label'       => esc_attr__( 'WooCommerce Page Layout', 'business-press' ),
		'description' => esc_attr__( 'This layout will apply on Shop, Single Product and Product Search Pages. If you want to set full width for Cart, Checkout and My Account pages, then edit these pages and select Full Width No Sidebar Template.', 'business-press' ),
		'section'     => 'woocommerce_options',
		'default'     => 'fullw',
		'priority'    => 10,
		'choices'     => array(
			'fullw'	  => get_template_directory_uri() . '/images/fullw.png',
			'rights'  => get_template_directory_uri() . '/images/rights.png',
			'lefts'   => get_template_directory_uri() . '/images/lefts.png',
		),
	) );
}
//woocommerce section END

//footer section
Kirki::add_section( 'footer_options', array(
    'title'          => esc_attr__( 'Footer Widget Options', 'business-press' ),
    'panel'          => 'business_press_options', // Not typically needed.
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'slider',
	'settings'    => 'business_press_number_of_footer_widgets',
	'label'       => esc_attr__( 'Footer Widgets', 'business-press' ),
	'description' => esc_attr__( 'How much Widgets Columns you want? (please save and go to Widgets page to add.)', 'business-press' ),
	'section'     => 'footer_options',
	'default'     => '0',
	'priority'    => 10,
	'choices'     => array(
		'min'  => '0',
		'max'  => '4',
		'step' => '1',
	),
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'bpress_info_ftrwdgt',
	'label'       => esc_attr__( 'Color Options', 'business-press' ),
	'section'     => 'footer_options',
	'default'     => '<div style="padding: 10px;background-color: #333; color: #fff; border-radius: 8px;">Color options are available in <a target="_blank" href="http://ewptheme.com/product/business-press-pro-wordpress-theme/">Business Press Pro Version</a>.</div>',
	'priority'    => 10,
) );
//footer section END

//copyright section
Kirki::add_section( 'copyright_options', array(
    'title'          => esc_attr__( 'Footer Copyright Options', 'business-press' ),
    'panel'          => 'business_press_options', // Not typically needed.
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'editor',
	'settings'    => 'business_press_copryt_left',
	'label'       => esc_attr__( 'Copyright Left Content', 'business-press' ),
	'description' => esc_attr__( 'Content of Copyright Left Side', 'business-press' ),
	'section'     => 'copyright_options',
	'default'     => '<p>' . __( '&copy; Copyright text, All right reserved.', 'business-press' ) . '</p>',
	'priority'    => 10,
	'transport' => 'postMessage',
	'js_vars'   => array(
		array(
			'element'  => '.cprtlft_ctmzr',
			'function' => 'html',
		),
	),
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'business_press_copryt_right',
	'label'       => esc_attr__( 'Copyright Right Content', 'business-press' ),
	'description' => esc_attr__( 'Content of Copyright Right Side', 'business-press' ),
	'section'     => 'copyright_options',
	'default'     => '<div style="padding: 10px;background-color: #333; color: #fff; border-radius: 8px;">This option is available in <a target="_blank" href="http://ewptheme.com/product/business-press-pro-wordpress-theme/">Business Press Pro Version</a>.</div>',
	'priority'    => 10,
) );
//copyright section END

//misc section
Kirki::add_section( 'misc_options', array(
    'title'          => esc_attr__( 'MISC Options', 'business-press' ),
    'panel'          => 'business_press_options', // Not typically needed.
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'business_press_back_to_top',
	'label'       => esc_attr__( 'Back To Top Button', 'business-press' ),
	'description' => esc_attr__( 'Enable/Disable Back To Top Button', 'business-press' ),
	'section'     => 'misc_options',
	'default'     => '1',
	'priority'    => 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'toggle',
	'settings'    => 'business_press_loading_icon',
	'label'       => esc_attr__( 'Page Loading Icon', 'business-press' ),
	'description' => esc_attr__( 'Enable/Disable Page Loading Icon', 'business-press' ),
	'section'     => 'misc_options',
	'default'     => '0',
	'priority'    => 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'image',
	'settings'    => 'business_press_loading_icon_img',
	'label'       => esc_attr__( 'Upload Custom Loading Icon', 'business-press' ),
	'description' => esc_attr__( 'It will replace default loading icon.', 'business-press' ),
	'section'     => 'misc_options',
	'default'     => '',
	'priority'    => 10,
	'active_callback'  => array(
		array(
			'setting'  => 'business_press_loading_icon',
			'operator' => '==',
			'value'    => 1,
		),
	)
) );
//misc section END

//Theme Info section
Kirki::add_section( 'business_press_theme_info', array(
    'title'          => esc_attr__( 'Theme Info', 'business-press' ),
    'panel'          => 'business_press_options', // Not typically needed.
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'business_press_docs',
	'label'       => __( 'Business Press DOCS', 'business-press' ),
	'section'     => 'business_press_theme_info',
	'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">You can check Business Press Free theme <a target="_blank" href="http://ewptheme.com/business-press-free-wordpress-theme-documentation/">DOCS Here</a>.</div>',
	'priority'    => 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'business_press_custom_dmo',
	'label'       => __( 'Business Press Demo', 'business-press' ),
	'section'     => 'business_press_theme_info',
	'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">You can check Business Press Free theme <a target="_blank" href="http://business-press.ewptheme.com/">Demo Here</a>.</div>',
	'priority'    => 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'business_press_custom_sprt',
	'label'       => __( 'Business Press Support', 'business-press' ),
	'section'     => 'business_press_theme_info',
	'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">Do you want our support? Please <a target="_blank" href="http://ewptheme.com/support/">Create a support ticket</a>.</div>',
	'priority'    => 10,
) );

Kirki::add_field( 'business_press_config', array(
	'type'        => 'custom',
	'settings'    => 'business_press_custom_crddit',
	'label'       => 'Business Press Pro',
	'section'     => 'business_press_theme_info',
	'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">Do you want missing options and compatibility with premium plugins? Please <a target="_blank" href="http://ewptheme.com/product/business-press-pro-wordpress-theme/">Install Business Press Pro version</a>.</div>',
	'priority'    => 10,
) );

//Theme Info section END

