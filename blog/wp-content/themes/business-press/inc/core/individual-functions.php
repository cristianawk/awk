<?php

function business_press_post_pagination()
{
	if( get_previous_post_link() || get_next_post_link() )
	{
	?>
		<div class="content-first">
			<nav>
				 <ul class="pager">

				 	<?php
					if( get_previous_post_link() )
					{
						previous_post_link( '<li class="previous"> %link </li>', '&larr; %title' );
					}
					?>

				 	<?php
					if( get_next_post_link() )
					{
						next_post_link( '<li class="next"> %link </li>', '%title &rarr;' );
					}
					?>

				</ul>
			</nav>	
		</div>
	<?php
	}
}


function business_press_posts_pagination()
{
	if( get_theme_mod( 'display_archive_pagination', 'nextprev' ) == 'nextprev' )
	{
		if( get_next_posts_link() || get_previous_posts_link() ) {
		?>
			<div class="content-nav-next-pre">
				<nav>
					<ul class="pager">

						<?php
						if( get_next_posts_link() )
						{
						?>
							<li class="previous"><?php next_posts_link( __( '&larr; Older Entries ', 'business-press' ) ); ?></li>
						<?php
						}
						?>
						
						<?php
						if( get_previous_posts_link() )
						{
						?>
							<li class="next"><?php previous_posts_link( __( 'Newer Entries &rarr;', 'business-press' ) ); ?></li>
						<?php
						}
						?>
								
					</ul>
				</nav>
			</div>
		<?php
		}
	}
	else
	{
		the_posts_pagination( array(
			'prev_text' => __( '&laquo;', 'business-press' ),
			'next_text' => __( '&raquo;', 'business-press' ),
			) );
	}
}

// business_press Breadcrumb
function business_press_breadcrumbs()
{
	$custom_taxonomy = '';

    // Get the query & post information
    global $post,$wp_query;
       
    // Do not display on the homepage
    if( !is_front_page() )
	{
       
        // Build the breadcrums
        echo '<ul class="breadcrumb">';
           
        // Home page
        echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_attr__( 'Home', 'business-press' ) . '</a></li>';
           
       if( is_home() )
	   {
		   echo '<li class="active">' . esc_attr__( 'Blog', 'business-press' ) . '</li>';
	   }
	   elseif( is_archive() && !is_tax() && !is_category() && !is_tag() && !is_year() && !is_month() && !is_day() && !is_author() )
	   {
		   // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if( $post_type != 'post' )
			{
                  
                $post_type_object = get_post_type_object( $post_type );
                $post_type_archive = get_post_type_archive_link( $post_type );
              
                echo '<li class="active">' . esc_attr( $post_type_object->labels->name ) . '</li>';
              
            }

	   }
	   elseif( is_archive() && is_tax() && !is_category() && !is_tag() )
	   {
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if( $post_type != 'post' )
			{
                  
                $post_type_object = get_post_type_object( $post_type );
                $post_type_archive = get_post_type_archive_link( $post_type );
              
                echo '<li><a href="' . esc_url( $post_type_archive ) . '">' . esc_attr( $post_type_object->labels->name ) . '</a></li>';
              
            }
              
            $custom_tax_name = get_queried_object()->name;
            echo '<li class="active">' . esc_attr( $custom_tax_name ) . '</li>';
              
        }
		elseif( is_single() )
		{
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if( $post_type != 'post' )
			{
                  
                $post_type_object = get_post_type_object( $post_type );
                $post_type_archive = get_post_type_archive_link( $post_type );
              
                echo '<li><a href="' . esc_url( $post_type_archive ) . '">' . esc_attr( $post_type_object->labels->name ) . '</a></li>';
              
            }
              
            // Get post category info
            $category = get_the_category();
            $last_category = '';
			 
            if( !empty( $category ) )
			{
              
                // Get last category post is in
				$pre_last_category = array_values( $category );
                $last_category = end( $pre_last_category );
                  
                // Get parent any categories and create array
                $get_cat_parents = rtrim( get_category_parents( $last_category->term_id, true, ',' ), ',' );
                $cat_parents = explode( ',', $get_cat_parents );
                  
                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach( $cat_parents as $parents )
				{
                    $cat_display .= '<li>'.  wp_kses_post( $parents ) .'</li>';
                }
             
            }
              
            // If it's a custom post type within a custom taxonomy
			
            $taxonomy_exists = taxonomy_exists( $custom_taxonomy );
            if( empty( $last_category ) && !empty( $custom_taxonomy ) && $taxonomy_exists )
			{
                   
                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link( $taxonomy_terms[0]->term_id, $custom_taxonomy );
                $cat_name       = $taxonomy_terms[0]->name;
               
            }
              
            // Check if the post is in a category
            if( !empty( $last_category ) )
			{
                echo $cat_display; //already escaped
				
				//get_the_title intentionally allows for HTML and should not be escaped.
                echo '<li class="active">' . get_the_title() . '</li>';
                  
            // Else if post is in a custom taxonomy
            }
			elseif( !empty( $cat_id ) )
			{
                  
                echo '<li><a href="' . esc_url( $cat_link ) . '">' . esc_attr( $cat_name ) . '</a></li>';
                echo '<li class="active">' . get_the_title() . '</li>';
              
            }
			else
			{
                  
                echo '<li class="active">' . get_the_title() . '</li>';
                  
            }
              
        }
		elseif( is_category() )
		{
               
            // Category page
            echo '<li class="active">' . esc_attr__( 'Category: ', 'business-press' ) . single_cat_title( '', false ) . '</li>';
               
        }
		elseif( is_page() )
		{
               
            // Standard page
            if( $post->post_parent )
			{
                   
                // If child page, get parents 
                $anc = get_post_ancestors( $post->ID );
                   
                // Get parents in the right order
                $anc = array_reverse( $anc );
                   
                // Parent page loop
				$parents = '';
                foreach ( $anc as $ancestor )
				{
                    $parents .= '<li><a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . get_the_title( $ancestor ) . '</a></li>';
                }
                   
                // Display parent pages
                echo $parents;
                   
                // Current page
                echo '<li class="active">' . get_the_title() . '</li>';
                   
            }
			else
			{   
                // Just display current page if not parents
                echo '<li class="active">' . get_the_title() . '</li>';
                   
            }
               
        }
		elseif( is_tag() )
		{
               
            // Tag page
               
            // Get tag information
            $term_id        = get_query_var( 'tag_id' );
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;
               
            // Display the tag name
            echo '<li class="active">' . esc_attr__( 'Tag: ', 'business-press' ) . esc_attr( $get_term_name ) . '</li>';
           
        }
		elseif( is_day() )
		{
               
            // Day archive
               
            // Year link
            echo '<li><a href="' . get_year_link( get_the_time('Y') ) . '">' . get_the_time('Y') . '</a></li>';
               
            // Month link
            echo '<li><a href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '">' . get_the_time('F') . '</a></li>';
               
            // Day display
            echo '<li class="active">' . get_the_time('jS') . ' ' . get_the_time('F') . '</li>';
               
        }
		elseif( is_month() )
		{
               
            // Month Archive
               
            // Year link
            echo '<li><a href="' . get_year_link( get_the_time('Y') ) . '">' . get_the_time('Y') . '</a></li>';
               
            // Month display
            echo '<li class="active">' . get_the_time('F') . '</li>';
               
        }
		elseif( is_year() )
		{

            // Display year archive
            echo '<li class="active">' . get_the_time('Y') . '</li>';
               
        }
		elseif( is_author() )
		{
               
            // Auhor archive
			
            // Display author name
            echo '<li class="active">' . esc_attr__( 'Author: ', 'business-press' ) . get_the_author() . '</li>';
           
        }
		elseif( get_query_var( 'paged' ) )
		{
            // Paginated archives
            echo '<li class="active">' . esc_attr__( 'Page: ', 'business-press' ) . get_query_var( 'paged' ) . '</li>';
        }
		elseif( is_search() )
		{
           
            // Search results page
            echo '<li class="active">' . esc_attr__( 'Search: ', 'business-press' ) . get_search_query() . '</li>'; 
        }
		elseif( is_404() )
		{
			//error 404 page
            echo '<li class="active">' . esc_attr__( 'Error 404', 'business-press' ) . '</li>';
        }
		else
		{
			echo '<li class="active">' . esc_attr__( 'Untitled', 'business-press' ) . '</li>';
		}
       
        echo '</ul>';
           
    }
}

// page headline on title bar
function business_press_page_headline()
{

    // Get the query & post information
    global $post,$wp_query;
       
    // Do not display on the homepage
    if( !is_front_page() )
	{
           
		if( is_home() )
		{
			esc_attr_e( 'Blog', 'business-press' );
		}
		elseif( is_archive() )
		{
            echo get_the_archive_title();       
        }
		elseif( is_single() )
		{
			echo get_the_title();
        }
		elseif( get_query_var( 'paged' ) )
		{
            // Paginated archives
            echo esc_attr__( 'Page ', 'business-press' ) . get_query_var('paged');
        }
		elseif( is_search() )
		{
           
            // Search results page
            echo esc_attr__( 'Search results for: ', 'business-press' ) . get_search_query(); 
        }
		elseif( is_404() )
		{
			//error 404 page
            echo esc_attr__( 'Error 404: Page not found', 'business-press' );
        }
		else
		{
			echo get_the_title();
		}
           
    }
	else
	{
        echo '<span class="frnt_pg_hdline_ctmzr">'. esc_attr( get_theme_mod( 'business_press_frntpg_hdlin', esc_attr__( 'Headline: Customizable', 'business-press' ) ) ) . '</span>';
	}
}


function business_press_entry_meta() {
?>
<span class="bpress-entry-meta">
	
	<span class="vcard author" itemprop="author" itemscope itemtype="http://schema.org/Person"><span class="fn"> <a class="url authorurl" rel="author" itemprop="url" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" ><span itemprop="name"><?php the_author(); ?></span></a></span></span>
	
	<?php if( has_category() ) { ?>
	<span class="categoryurl"><?php the_category( ', ' ); ?></span>
	<?php } ?>
	
	<a href="<?php the_permalink(); ?>" ><span class="post-date updated" itemprop="dateModified"><?php if( get_theme_mod( 'business_press_post_date_view', '1' ) == 1 ) { the_modified_date(); } else { the_date(); } ?></span></a>
	
</span>
<hr class="mrt10 mrb10" />
<?php
}


function business_press_post_thumbnail()
{
	if( has_post_thumbnail() )
	{
	?>
		<div class="alignc pdt10 pdb10">
		<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'aligncenter' ) ); ?>
		</div>
	<?php
	}
}


function business_press_comment_panel_headline()
{
	return wp_kses_post( get_theme_mod( 'business_press_comment_panel_title', esc_attr__( 'Have any Question or Comment?', 'business-press' ) ) );
}


/**
* Menu Fallback
* =============
*/
function business_press_nav_fallback( $args )
{
	extract( $args );
	$business_press_output = null;
	if( $container )
	{
		$business_press_output = '<' . $container;
		if ( $container_id )
		{
			$business_press_output .= ' id="' . $container_id . '"';
		}
		if ( $container_class )
		{
			$business_press_output .= ' class="' . $container_class . '"';
		}
		$business_press_output .= '>';
	}
	
	$business_press_output .= '<ul';
	if( $menu_id )
	{
		$business_press_output .= ' id="' . $menu_id . '"';
	}
	if( $menu_class )
	{
		$business_press_output .= ' class="' . $menu_class . '"';
	}
	$business_press_output .= '>';
	
	$business_press_output .= '<li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item current_page_item active"><a href="' . esc_url( home_url( '/' ) ) . '">'. esc_attr__( 'Home', 'business-press' ) .'</a></li>';
	
	$business_press_output .= '<li class="menu-item menu-item-type-custom"><a href="#">'. esc_attr__( 'Responsive', 'business-press' ) .'</a></li>';
	
	$business_press_output .= '<li class="menu-item menu-item-type-custom"><a href="#">'. esc_attr__( 'Fast', 'business-press' ) .'</a></li>';
	
	$business_press_output .= '<li class="menu-item menu-item-type-custom"><a href="#">'. esc_attr__( 'SEO', 'business-press' ) .'</a></li>';
	
	$business_press_output .= '<li class="menu-item menu-item-type-custom"><a href="#">'. esc_attr__( 'Page Builder', 'business-press' ) .'</a></li>';
	
	$business_press_output .= '<li class="menu-item menu-item-type-custom"><a href="#">'. esc_attr__( 'Slider', 'business-press' ) .'</a></li>';
	
	$business_press_output .= '</ul>';
	if( $container )
	{
		$business_press_output .= '</' . $container . '>';
	}
	echo $business_press_output;
}


function business_press_comments( $comment, $args, $depth )
{
?>
	<div <?php comment_class(); ?>>
		<div class="comment-author vcard" itemtype="http://schema.org/Comment" itemscope itemprop="comment">
			<div id="comment-<?php comment_ID(); ?>" class="media" >
				
				<div class="media-left">
					<?php echo get_avatar( $comment, 60 ); ?>
				</div>
						
				<div class="media-body">
					
					<?php if( get_comment_author_url() ) { ?>
						<h6 class="media-heading fn" itemtype="http://schema.org/Person" itemscope itemprop="author">
							<a class="url" target="_blank" rel="external nofollow" itemprop="url" href="<?php echo get_comment_author_url(); ?>"><span itemprop="name"><?php echo get_comment_author(); ?></span></a>
						</h6>
					<?php } else { ?>
						<h6 class="media-heading fn"><span itemprop="name"><?php echo get_comment_author(); ?></span></h6>
					<?php } ?>
					
					<?php if ( $comment->comment_approved == '0' ) { ?>
					<p class="alert alert-info" ><?php esc_attr_e( 'Your comment is awaiting approval.', 'business-press' ); ?></p>
					<?php } ?>
							
					<small>
						<a itemprop="url" href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"> <?php printf( __( '%1$s at %2$s', 'business-press' ), '<span itemprop="datePublished">' . get_comment_date() . '</span>',  get_comment_time() ); ?></a>
					</small>
					
					<div itemprop="text"><?php comment_text(); ?></div>
					
					<small>
					<?php comment_reply_link( array_merge( $args,
						array(
							'depth' => $depth,
							'max_depth' => $args['max_depth'],
							'reply_text' => esc_attr__( 'Reply', 'business-press' ),
							'before' => '',
							'after' => ''
							)
					) );
					?>
					
					<?php edit_comment_link( esc_attr__( 'Edit', 'business-press' ), '', '' ) ?>
					</small>
					
				</div>
			</div>
		</div>
<!--</div> is added by wordpress automatically -->
<?php
}

