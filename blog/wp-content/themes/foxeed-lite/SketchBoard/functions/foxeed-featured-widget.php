<?php

/******************************************** 
FEATURED BOX WIDGET START
*********************************************/
class FoxeedLiteFeaturedBox extends WP_Widget {
    
    /**
    * Register widget with WordPress.
    */
    function __construct() {
        $widget_ops = array('classname' => 'mid-box span3','description' => __('Foxeed Lite Themes widget for Featured Box','foxeed-lite') );
        parent::__construct(
            'FoxeedLiteFeaturedBox', // Base ID
            __('Featured Box - Foxeed Lite','foxeed-lite'), // Name
           $widget_ops ); // Args
    }
    

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
    */
    function widget($args, $instance) {	
		extract( $args );
        if(isset($instance['title'])){ $title = wp_kses_post($instance['title']); } else { $title = ''; }
        if(isset($instance['fb_icon_class'])){ $fb_icon_class = esc_html($instance['fb_icon_class']);} else { $fb_icon_class = ''; }
        if(isset($instance['fb_content'])){$fb_content = wp_kses_post($instance['fb_content']);} else { $fb_content = ''; }      
        if(isset($instance['fb_link'])){$fb_link = esc_url($instance['fb_link']);} else { $fb_link = ''; }

        ?>
        <?php echo $before_widget; ?>
        					
        <!-- Featured Box  -->  

        <div class="foxeed-iconbox iconbox-top">      
            <div class="iconbox-icon ">
                <i class="fa <?php echo $fb_icon_class; ?>"></i>
                <h4><a href="<?php echo $fb_link; ?>"><?php if($title) { echo $title; } ?></a></h4>
            </div>
            <div class="iconbox-content"><?php if($fb_content) { echo $fb_content; } ?></div>          
            <div class="clearfix"></div>        
        </div>
        <?php echo $after_widget;
    }
    

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
    */
    function update($new_instance, $old_instance) {				
    	$instance = $old_instance;
    	$instance['title'] = wp_kses_post($new_instance['title']);
    	$instance['fb_icon_class'] = esc_html($new_instance['fb_icon_class']);
    	$instance['fb_content'] = wp_kses_post($new_instance['fb_content']);
    	$instance['fb_link'] = esc_url($new_instance['fb_link']);
        return $instance;
    }
    

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
    */
    function form($instance) {
		if(isset($instance['title'])){ $title = wp_kses_post($instance['title']); }
		if(isset($instance['fb_icon_class'])){ $fb_icon_class = esc_html($instance['fb_icon_class']);}
		if(isset($instance['fb_content'])){$fb_content = wp_kses_post($instance['fb_content']);}			
		if(isset($instance['fb_link'])){$fb_link = esc_url($instance['fb_link']);}
        ?>
         <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','foxeed-lite'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php if(isset($title)){echo $title;} else { echo '';}  ?>" /></label></p>

         <p><label for="<?php echo $this->get_field_id('fb_icon_class'); ?>"><?php _e('Featured Box Icon Class:','foxeed-lite'); ?> <input class="widefat" id="<?php echo $this->get_field_id('fb_icon_class'); ?>" name="<?php echo $this->get_field_name('fb_icon_class'); ?>" type="text" value="<?php if(isset($fb_icon_class)){echo $fb_icon_class;} else { echo '';} ?>" /></label></p>

         <p><label for="<?php echo $this->get_field_id('fb_content'); ?>"><?php _e('Featured Box Content:','foxeed-lite'); ?> <textarea rows="4" cols="50" class="widefat" id="<?php echo $this->get_field_id('fb_content'); ?>" name="<?php echo $this->get_field_name('fb_content'); ?>"><?php if(isset($fb_content)){echo $fb_content;} else { echo '';} ?></textarea></label></p>

		 <p><label for="<?php echo $this->get_field_id('fb_link'); ?>"><?php _e('Featured Box Link:','foxeed-lite'); ?> <input class="widefat" id="<?php echo $this->get_field_id('fb_link'); ?>" name="<?php echo $this->get_field_name('fb_link'); ?>" type="text" value="<?php if(isset($fb_link)){echo $fb_link;} else { echo '';}?>" /></label></p>	

         <?php 
    }
}

function foxeed_lite_register_widgets() {
    register_widget('FoxeedLiteFeaturedBox');
}

add_action( 'widgets_init', 'foxeed_lite_register_widgets' );
/********************************************
FEATURED BOX WIDGET END
*********************************************/