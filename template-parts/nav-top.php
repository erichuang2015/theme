<?php 
/**
 * Top Navigation
 */

// Check if menu
if ( ! has_nav_menu( 'top' ) ) 
{
	return;
}

?>

<nav id="top-navigation" class="nav-dark bg-dark d-none d-md-flex" role="navigation">
	<div class="container d-flex">
	<?php

		wp_nav_menu( array
		(
			'theme_location' => 'top', 
			'menu_class'     => 'nav align-items-center ml-auto',
			'container'      => false
		));
	?>
	</div><!-- .container -->
</nav><!-- #top-navigation -->
