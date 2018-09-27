<?php 
/**
 * Heading
 *
 * Advanced Custom Fields flexible content layout
 */

$text   = get_sub_field( 'text' );
$text_2 = get_sub_field( 'text_2' );

?>

<div class="heading">

	<?php if ( ! theme_has_container() ) : ?>
	<div class="container">
	<?php endif; ?>

	<?php  

		echo '<h2>' . esc_html( $text );

		if ( $text_2 ) 
		{
			printf( ' <small>%s</small>', esc_html( $text_2 ) );
		}

		echo '</h2>';

	?>

	<?php if ( ! theme_has_container() ) : ?>
	</div><!-- . container -->
	<?php endif; ?>

</div><!-- .heading -->
