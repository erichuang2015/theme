<?php 
/**
 * Top Navigation
 */

// Check if locations have menus
if ( ! has_nav_menu( 'top_left' ) && ! has_nav_menu( 'top_right' ) ) 
{
	return;
}

?>

<nav id="top-navigation" class="nav-dark bg-dark d-none d-md-flex" role="navigation">
	<div class="container d-flex">
	<?php

		if ( has_nav_menu( 'top_left' ) ) 
		{
			wp_nav_menu( array
			(
				'theme_location' => 'top_left', 
				'menu_class'     => 'nav align-items-center mr-auto',
				'container'      => false
			));
		}
		
		if ( has_nav_menu( 'top_right' ) ) 
		{
			wp_nav_menu( array
			(
				'theme_location' => 'top_right', 
				'menu_class'     => 'nav align-items-center ml-auto',
				'container'      => false
			));
		}

	?>
	</div><!-- .container -->
</nav><!-- #top-navigation -->
