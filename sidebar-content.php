<?php
/**
 * The Content Sidebar
 */

if ( ! is_active_sidebar( 'sidebar-2' ) ) 
{
	return;
}

?>

<aside id="content-sidebar" class="widget-area col-md-3 order-lg-last" role="complementary" aria-label="<?php esc_attr_e( 'Content Sidebar', 'theme' ); ?>">
	<?php dynamic_sidebar( 'sidebar-2' ); ?>
</aside><!-- #content-sidebar -->
