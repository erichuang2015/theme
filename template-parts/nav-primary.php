<?php
/**
 * Primary Navigation
 */
?>
<nav id="primary-navigation" class="navbar navbar-expand-md navbar-light bg-light" role="navigation">
	<div class="container">
		<a class="navbar-brand" href="<?php echo esc_url( get_home_url( null, '/' ) ); ?>" rel="home"><?php theme_site_logo(); ?></a>
		<?php if ( has_nav_menu( 'primary_1' ) || has_nav_menu( 'primary_2' ) ) : ?>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-nav" aria-controls="navbar-nav" aria-expanded="false" aria-label="<?php esc_html_e( 'Toggle navigation', 'theme' ); ?>">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div id="navbar-nav" class="collapse navbar-collapse">
			<?php

				if ( has_nav_menu( 'primary_1' ) )
				{
					wp_nav_menu( array
					(
						'theme_location' => 'primary_1', 
						'menu_id'        => 'primary-nav-1', 
						'menu_class'     => 'nav navbar-nav mr-auto',
						'container'      => false,
						'walker'         => new Theme_Nav_Menu_Walker()
					));
				}

				if ( has_nav_menu( 'primary_2' ) )
				{
					wp_nav_menu( array
					(
						'theme_location' => 'primary_2',
						'menu_id'        => 'primary-nav-2', 
						'menu_class'     => 'nav navbar-nav ml-auto',
						'container'      => false,
						'walker'         => new Theme_Nav_Menu_Walker()
					));
				}

			?>
		</div><!-- #navbar-nav -->
		<?php endif; ?>

	</div><!-- .container -->
</nav><!-- #primary-navigation -->
