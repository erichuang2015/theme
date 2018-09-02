<?php
/**
 * The Primary Sidebar
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) 
{
	return;
}

?>

<aside id="primary-sidebar" class="widget-area col-md-12 col-lg-3 order-lg-first" role="complementary" aria-label="<?php esc_attr_e( 'Primary Sidebar', 'theme' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #primary-sidebar -->
