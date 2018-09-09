<?php 
/**
 * Footer Widgets
 */

// Check if widgets
if ( ! is_active_sidebar( 'sidebar-3' ) 
  && ! is_active_sidebar( 'sidebar-4' ) 
  && ! is_active_sidebar( 'sidebar-5' ) )
{
	return;
}

?>

<div id="footer-widgets">
	<div class="container">
		<div class="row">
			
			<aside class="widget-area col-md" role="complementary">
				<?php dynamic_sidebar( 'sidebar-3' ); ?>
			</aside><!-- .widget-area -->

			<aside class="widget-area col-md" role="complementary">
				<?php dynamic_sidebar( 'sidebar-4' ); ?>
			</aside><!-- .widget-area -->

			<aside class="widget-area col-md" role="complementary">
				<?php dynamic_sidebar( 'sidebar-5' ); ?>
			</aside><!-- .widget-area -->

		</div><!-- .row -->
	</div><!-- .container -->
</div><!-- #footer-widgets -->
