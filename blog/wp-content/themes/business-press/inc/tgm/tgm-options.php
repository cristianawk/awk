<?php

/**
 * Include the TGM_Plugin_Activation class. (included in init.php)
 */
function business_press_register_required_plugins()
{
	$plugins = array(
		
		array(
			'name'      => __( 'Meta Slider (for Slider)', 'business-press' ),
			'slug'      => 'ml-slider',
			'required'  => false,
		),
		
		array(
			'name'      => __( 'Regenerate Thumbnails', 'business-press' ),
			'slug'      => 'regenerate-thumbnails',
			'required'  => false,
		),
		
		array(
			'name'      => __( 'Page Builder by SiteOrigin (Page Builder)', 'business-press' ),
			'slug'      => 'siteorigin-panels',
			'required'  => false,
		),
		
		array(
			'name'      => __( 'SiteOrigin Widgets Bundle (Page Builder Widgets)', 'business-press' ),
			'slug'      => 'so-widgets-bundle',
			'required'  => false,
		),
		
		array(
			'name'      => __( 'WooCommerce (For E-Commerce)', 'business-press' ),
			'slug'      => 'woocommerce',
			'required'  => false,
		),
		
		array(
			'name'      => __( 'Contact Form 7 (For Forms)', 'business-press' ),
			'slug'      => 'contact-form-7',
			'required'  => false,
		),

		array(
			'name'      => __( 'SEO (Search Engine Optimization)', 'business-press' ),
			'slug'      => 'wordpress-seo',
			'required'  => false,
		),

	);
	
	
	$config = array(
		'id'           => 'business-press',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'business-press-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'business_press_register_required_plugins' );

