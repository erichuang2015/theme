<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	
	<div id="page" class="site">

		<header id="masthead" class="site-header" role="banner">
			<?php get_template_part( 'template-parts/nav-top' ); ?>
			<?php get_template_part( 'template-parts/nav-main' ); ?>
		</header><!-- #masthead -->

		<div id="content" class="site-content">

			<?php 

				if ( function_exists( 'theme_main_visual' ) ) :

					theme_main_visual();
				
				endif;
			?>
