<?php
/**
 * The After Content Sidebar
 */

if ( ! is_active_sidebar( 'sidebar-after-content' ) ) 
{
	return;
}

?>

<div class="container">
	<?php dynamic_sidebar( 'sidebar-after-content' ); ?>
</div><!-- .container -->
