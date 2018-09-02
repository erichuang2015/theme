<?php
/**
 * The Before Content Sidebar
 */

if ( ! is_active_sidebar( 'sidebar-before-content' ) ) 
{
	return;
}

?>

<div class="container">
	<?php dynamic_sidebar( 'sidebar-before-content' ); ?>
</div><!-- .container -->
