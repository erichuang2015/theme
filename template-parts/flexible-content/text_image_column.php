<?php 
/**
 * Text-image Column
 *
 * Advanced Custom Fields flexible content layout
 */

$content        = get_sub_field( 'content' );
$image          = get_sub_field( 'image' );
$image_position = get_sub_field( 'image_position' );

// Attributes

$image_column = array
( 
	'class' => 'col-md-6',
);

$text_column = array
( 
	'class' => 'col-md-6',
);

if ( $image_position == 'right' ) 
{
	$image_column['class'] .= ' order-md-2';
	$text_column['class'] .= ' order-md-1';
}

?>

<div class="text-image-column">

	<?php if ( ! theme_has_container() ) : ?>
	<div class="container">
	<?php endif; ?>

		<div class="row">

			<div<?php echo theme_esc_attr( $image_column ); ?>>
				<?php 

				echo wp_get_attachment_image( $image, 'large', false, array
				( 
					'class' => 'mb-3 mb-md-0' 
				)); 

				?>
			</div>

			<div<?php echo theme_esc_attr( $text_column ); ?>>
				<?php echo $content; ?>
			</div>

		</div><!-- .row -->
		

	<?php if ( ! theme_has_container() ) : ?>
	</div><!-- . container -->
	<?php endif; ?>


</div><!-- .text-image-column -->
