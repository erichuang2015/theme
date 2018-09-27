<?php if ( ! function_exists( 'get_field' ) ) return; // Check dependency
/**
 * Main Visual
 *
 * Use `theme_main_visual()` to include this template.
 */

// To Get field data other than pages and posts. e.g. terms, …
$queried_object = get_queried_object();

// Vars

$title          = get_field( 'main_visual_title'	     , $queried_object );
$content        = get_field( 'main_visual_content'		 , $queried_object );
$image          = get_field( 'main_visual_image'		 , $queried_object );
$image_position = get_field( 'main_visual_image_position', $queried_object );

// Attributes

$atts = array
(
	'id'    => 'main-visual',
	'class' => 'py-6 d-flex align-items-center bg-light vh-60'
);

list( $image_url ) = wp_get_attachment_image_src( $image, 'theme-full-width' );

if ( $image_url ) 
{
	$atts['style'] = sprintf( 'background-image: url(%s);', esc_url( $image_url ) );
	$atts['class'] .= " bg-size-cover bg-position-$image_position";
}

?>

<div<?php echo theme_esc_attr( $atts ); ?>>
	<div class="container">
		<div class="row">
			<div class="col-lg-7">

				<?php if ( $title ) : ?>
				<h1 class="display-4"><?php echo esc_html( $title ); ?></h1>
				<?php endif; ?>

				<?php if ( $content ) : ?>
				<div class="lead">
					<?php echo $content; ?>
				</div>
				<?php endif; ?>

			</div><!-- .col-lg-7 -->
		</div><!-- .row -->
	</div><!-- .container -->
</div><!-- #main-visual -->
