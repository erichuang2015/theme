<?php 
/**
 * Page Header
 *
 * Advanced Custom Fields flexible content layout
 */

$title    = get_sub_field( 'title' );
$subtitle = get_sub_field( 'subtitle' );

?>

<header class="page-header">

	<?php if ( ! theme_has_container() ) : ?>
	<div class="container">
	<?php endif; ?>

	<?php  

		echo '<h1 class="page-title">' . esc_html( $title );

		if ( $subtitle ) 
		{
			printf( ' <small>%s</small>', esc_html( $subtitle ) );
		}

		echo '</h1>';

	?>

	<?php if ( ! theme_has_container() ) : ?>
	</div><!-- . container -->
	<?php endif; ?>

</header><!-- .page-header -->
