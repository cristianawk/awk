<?php
/**
 * Class to add meta box for page.
 *
 */

// Do if is admin only.
if( is_admin() )
{
    add_action( 'load-post.php', 'business_press_page_meta_box_first_func' );
    add_action( 'load-post-new.php', 'business_press_page_meta_box_first_func' );
}

/**
 * Calls the class on the post edit screen.
 */
function business_press_page_meta_box_first_func()
{
    new Business_Press_Page_Meta_Box_Main_Class();
}
 
/**
 * The Class.
 */
class Business_Press_Page_Meta_Box_Main_Class
{
 
    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct()
    {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post',      array( $this, 'save' ) );
    }
 
    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type )
    {
        if ( $post_type == 'page' )
        {
            add_meta_box(
                'business_press_page_meta_box_cntnr',
                __( 'Business Press Options for this Page', 'business-press' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'normal',
                'default'
            );
        }
    }

     /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post )
    {
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'business_press_page_meta_bx_key', 'business_press_page_meta_bx_key_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $slider_scode 			= get_post_meta( $post->ID, 'business_press_slider_scode', true );
        $hide_titlebar          = get_post_meta( $post->ID, 'business_press_hide_titlebar', true );
        $hide_title 			= get_post_meta( $post->ID, 'business_press_hide_title', true );
        $hide_footer_widgets 	= get_post_meta( $post->ID, 'business_press_hide_footer_widgets', true );

        ?>


        <p>
        <label style="padding-right: 75px;" for="slider_scode">
            <?php _e( 'Slider Shortcode : ', 'business-press' ); ?>
        </label>
        <input type="text" id="slider_scode" name="slider_scode_val" value="<?php echo htmlspecialchars( wp_kses_post( $slider_scode ) ); ?>" /> <?php _e( 'Info: Use any slider plugin to create slider and copy slider shortcode.', 'business-press' ); ?>
        </p>

        <p>
        <label style="padding-right: 48px;" for="hide_titlebar">
            <?php _e( 'Want to hide Title Bar? ', 'business-press' ); ?>
        </label>
        <input type="checkbox" id="hide_titlebar" name="hide_titlebar_val"  <?php checked( $hide_titlebar, '1' ); ?> /> <?php _e( 'Info: This will hide title bar in header, If you are using title bar.', 'business-press' ); ?>
        </p>

        <p>
        <label style="padding-right: 16px;" for="hide_title">
            <?php _e( 'Want to hide Headline/Title? ', 'business-press' ); ?>
        </label>
        <input type="checkbox" id="hide_title" name="hide_title_val"  <?php checked( $hide_title, '1' ); ?> /> <?php _e( 'Info: This will hide page title above content.', 'business-press' ); ?>
        </p>

        <p>
        <label style="padding-right: 6px;" for="hide_footer_widgets">
            <?php _e( 'Want to hide Footer Widgets? ', 'business-press' ); ?>
        </label>
        <input type="checkbox" id="hide_footer_widgets" name="hide_footer_widgets_val" <?php checked( $hide_footer_widgets, '1' ); ?> /> <?php _e( 'Info: This will hide footer widget section, if you are using footer widget.', 'business-press' ); ?>
        </p>

        <?php
    }
 
    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id )
    {
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if( ! isset( $_POST['business_press_page_meta_bx_key_nonce'] ) )
        {
            return $post_id;
        }
 
        $nonce = wp_unslash( $_POST['business_press_page_meta_bx_key_nonce'] );
 
        // Verify that the nonce is valid.
        if( ! wp_verify_nonce( $nonce, 'business_press_page_meta_bx_key' ) )
        {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        {
            return $post_id;
        }
 
        // Check the post type.
        if( $_POST['post_type'] != 'page' )
        {
        	return $post_id;
        }

        // Check current user permission.
        if( ! current_user_can( 'edit_page', $post_id ) )
        {
            return $post_id;
        }
 
        /* OK, it's safe for us to save the data now. */

        /*
        * Do not store slider shortcode by default otherwise it will consume database space.
        * Also delete shortcode meta if now it is blank and used before. 
        */
        // Proceed if set slider_scode_val.
        if( isset( $_POST['slider_scode_val'] ) )
        {
        	// delete stored meta if stored and now it is blank
        	if( empty( $_POST['slider_scode_val'] ) )
        	{
        		// Delete meta if there is
        		if( get_post_meta( $post_id, 'business_press_slider_scode', true ) )
        		{
        			delete_post_meta( $post_id, 'business_press_slider_scode' );
        		}
        	}
        	else
        	{
        		$slider_scode = sanitize_text_field( $_POST['slider_scode_val'] );
        		update_post_meta( $post_id, 'business_press_slider_scode', $slider_scode );
        	}
        }

         // Store 0 or 1 for title bar.
        $hide_titlebar = ( isset( $_POST['hide_titlebar_val'] ) && 'on' === $_POST['hide_titlebar_val'] ) ? '1' : '0';
        update_post_meta( $post_id, 'business_press_hide_titlebar', $hide_titlebar );

        // Store 0 or 1 for title.
        $hide_title = ( isset( $_POST['hide_title_val'] ) && 'on' === $_POST['hide_title_val'] ) ? '1' : '0';
        update_post_meta( $post_id, 'business_press_hide_title', $hide_title );

        // Store 0 or 1 for footer_widgets.
        $hide_title = ( isset( $_POST['hide_footer_widgets_val'] ) && 'on' === $_POST['hide_footer_widgets_val'] ) ? '1' : '0';
        update_post_meta( $post_id, 'business_press_hide_footer_widgets', $hide_title );

    }

}
