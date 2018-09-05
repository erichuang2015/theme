<?php 
/**
 * Top Navigation
 */

if ( ! has_nav_menu( 'top_1' ) && ! has_nav_menu( 'top_2' ) ) 
{
	return;
}

?>
<nav id="top-navigation" class="nav nav-dark bg-dark d-none d-md-flex" role="navigation">
	<div class="container d-flex">
	<?php

		if ( has_nav_menu( 'top_1' ) )
		{
			wp_nav_menu( array
			(
				'theme_location' => 'top_1', 
				'menu_id'        => 'top-menu-left', 
				'menu_class'     => 'nav align-items-center mr-auto',
				'container'      => false,
				'walker'         => new Theme_Nav_Menu_Walker()
			));
		}

		if ( has_nav_menu( 'top_2' ) )
		{
			wp_nav_menu( array
			(
				'theme_location' => 'top_2',
				'menu_id'        => 'top-menu-right', 
				'menu_class'     => 'nav align-items-center ml-auto',
				'container'      => false,
				'walker'         => new Theme_Nav_Menu_Walker()
			));
		}

	?>
	</div><!-- .container -->
</nav><!-- .nav -->
