<?php 
/**
 * Main Navigation
 */

?>

<nav id="main-navigation" class="navbar navbar-expand-md navbar-light bg-light" role="navigation">
	<div class="container">
		<a class="navbar-brand" href="<?php echo esc_url( get_home_url( null, '/' ) ); ?>" rel="home"><?php theme_site_logo(); ?></a>
		<?php if ( has_nav_menu( 'main' ) ) : ?>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-nav" aria-controls="navbar-nav" aria-expanded="false" aria-label="<?php esc_html_e( 'Toggle navigation', 'theme' ); ?>">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div id="navbar-nav" class="collapse navbar-collapse">
			<?php

				if ( has_nav_menu( 'main' ) )
				{
					wp_nav_menu( array
					(
						'theme_location' => 'main', 
						'menu_class'     => 'navbar-nav align-items-md-center ml-auto',
						'container'      => false
					));
				}

			?>
		</div><!-- #navbar-nav -->
		<?php endif; ?>

	</div><!-- .container -->
</nav><!-- #main-navigation -->