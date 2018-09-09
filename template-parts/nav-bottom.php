<?php 
/**
 * Bottom Navigation
 */

// Check if locations have menus
if ( ! has_nav_menu( 'bottom_left' ) && ! has_nav_menu( 'bottom_right' ) ) 
{
	return;
}

?>

<nav id="footer-navigation" class="nav-light bg-light" role="navigation">
	<div class="container d-flex">
	<?php

		if ( has_nav_menu( 'bottom_left' ) ) 
		{
			wp_nav_menu( array
			(
				'theme_location' => 'bottom_left', 
				'menu_class'     => 'nav flex-column flex-md-row mr-md-auto',
				'container'      => false,
				'depth'          => 1
			));
		}
		
		if ( has_nav_menu( 'bottom_right' ) ) 
		{
			wp_nav_menu( array
			(
				'theme_location' => 'bottom_right', 
				'menu_class'     => 'nav flex-column flex-md-row ml-md-auto',
				'container'      => false,
				'depth'          => 1
			));
		}
		

	?>
	</div><!-- .container -->
</nav><!-- #footer-navigation -->
