<?php 
/**
 * Bottom Navigation
 */

// Check if menu
if ( ! has_nav_menu( 'bottom' ) ) 
{
	return;
}

?>

<nav id="footer-navigation" class="nav-light bg-light d-none d-md-flex" role="navigation">
	<div class="container d-flex">
	<?php

		wp_nav_menu( array
		(
			'theme_location' => 'bottom', 
			'menu_class'     => 'nav align-items-center',
			'container'      => false,
			'depth'          => 1
		));
	?>
	</div><!-- .container -->
</nav><!-- #footer-navigation -->
