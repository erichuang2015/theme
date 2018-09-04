<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link sr-only" href="#content"><?php _e( 'Skip to content', 'theme' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<?php get_template_part( 'template-parts/nav-top' ); ?>
		<?php get_template_part( 'template-parts/nav-primary' ); ?>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
