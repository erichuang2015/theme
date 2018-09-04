<?php if ( ! function_exists( 'get_field' ) ) return; // Dependency check
/**
 * Main Visual
 *
 * Use `theme_main_visual()` to load this template.
 */

$title          = get_field( 'main_visual_title' );
$content        = get_field( 'main_visual_content' );
$image          = get_field( 'main_visual_image' );
$image_position = get_field( 'main_visual_image_position' );

$atts = array
(
	'class' => 'main-visual py-5 bg-light d-flex align-items-center vh-60'
);

if ( $image )
{
	list( $image_url ) = wp_get_attachment_image_src( $image, 'theme-full-width' );

	if ( $image_url ) 
	{
		$atts['style'] = sprintf( 'background-image:url(%s);', esc_url( $image_url ) );
		$atts['class'] .= " bg-size-cover bg-position-$image_position";
	}
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
</div><!-- .main-visual -->