<?php

// Theme setup addition.
require_once get_template_directory() . '/inc/core/theme-setup.php';

// Styles and scripts addition.
require_once get_template_directory() . '/inc/core/style-scripts.php';

// Add inline css in front head.
require_once get_template_directory() . '/inc/core/inline-css.php';

// Register sidebars addition.
require_once get_template_directory() . '/inc/core/register-sidebars.php';

// Social custom widget addition.
require_once get_template_directory() . '/inc/core/custom-widget-social.php';

// Action and filter addition.
require_once get_template_directory() . '/inc/core/action-filters.php';

// Individual functions addition.
require_once get_template_directory() . '/inc/core/individual-functions.php';

// Navwalker.
require_once get_template_directory() . '/inc/core/navwalker.php';

// Tgm class.
require_once get_template_directory() . '/inc/tgm/class-tgm-plugin-activation.php';

// Tgm options.
require get_template_directory() . '/inc/tgm/tgm-options.php';

// Include kirki plugin files if it is not activated.
if( ! class_exists( 'Kirki' ) )
{
	require get_template_directory() . '/inc/kirki/kirki/kirki.php';
}

// Include the kirki options file.
require get_template_directory() . '/inc/kirki/kirki-options.php';

// Include page metabox options file.
require get_template_directory() . '/inc/core/page-metabox.php';

// Include post metabox options file.
require get_template_directory() . '/inc/core/post-metabox.php';

// Include custom woocommerce file.
require get_template_directory() . '/inc/core/custom-woocommerce.php';

/*
* Do not edit any code of theme, use child theme instead
*/

