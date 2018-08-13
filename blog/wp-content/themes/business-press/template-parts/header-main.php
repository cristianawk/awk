<nav id="navbar" class="navbar navbar-default headermain">
  <div class="container">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only"><?php esc_attr_e( 'Toggle navigation', 'business-press' ); ?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      	<?php
		if( has_custom_logo() )
		{
		?>
			<div id="logoasimg" itemscope itemtype="http://schema.org/Organization" >
				<?php the_custom_logo(); ?>
			</div>
		<?php
		}
		else
		{
		?>
			<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<p id='logoastext'><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></p>
			</a>
		<?php
		}
		?>
    </div>

    <?php
	wp_nav_menu( array(
		'theme_location'    => 'primary',
		'depth'             =>  2,
		'container'         => 'div',
		'container_id'      => 'bs-example-navbar-collapse-1',
		'container_class'   => 'collapse navbar-collapse',
		'menu_id' 			=> '',
		'menu_class'        => 'nav navbar-nav navbar-right nav-sticky-selector',
		'fallback_cb'       => 'business_press_nav_fallback',
		'walker'            => new business_press_bootstrap_navwalker()
		) );
		?>

  </div><!-- /.container-fluid -->
</nav>