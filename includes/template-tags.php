<?php
/**
 * Template Tags
 */

if ( ! function_exists( 'theme_posts_ajax_pagination' ) ) :
/**
 * The Posts Ajax Pagination
 */
function theme_posts_ajax_pagination( $wp_query, $args = array() )
{
	// Arguments

	$defaults = array
	(
		'mid_size'  => 2,
		'prev_text' => sprintf( '%2$s<span class="sr-only">%1$s</span>', __( 'Previous page', 'theme' ), theme_get_icon( 'arrow-left' ) ),
		'next_text' => sprintf( '<span class="sr-only">%1$s</span>%2$s', __( 'Next page', 'theme' ), theme_get_icon( 'arrow-right' ) ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Check if pagination is needed

	$paged = $wp_query->get( 'paged' );

	if ( ! $paged || $wp_query->max_num_pages == 1 ) 
	{
		return;
	}

	// Set size

	$size_start = $paged - $args['mid_size'];
	$size_end   = $paged + $args['mid_size'];

	if ( $size_start < 1 ) 
	{
		$size_start = 1;
	}

	if ( $size_end > $wp_query->max_num_pages ) 
	{
		$size_end = $wp_query->max_num_pages;
	}

	// Output

	?>

	<nav class="pagination-nav show-if-js">
		<ul class="pagination">

			<?php if ( $paged > 1 ) : ?>
			<li class="page-item"><a class="page-link" data-page="<?php echo $paged - 1; ?>" href="#" tabindex="-1"><?php echo $args['prev_text']; ?></a></li>
			<?php endif; ?>

			<?php for ( $page = $size_start; $page <= $size_end; $page++ ) : 

				$class   = 'page-item';
				$content = $page;

				if ( $page == $paged ) 
				{
					$class   .= ' active';
					$content = sprintf( '%d <span class="sr-only">%s</span>', $page, esc_html__( '(current)', 'theme' ) );
				}

			?>
			<li class="<?php echo $class; ?>"><a class="page-link" href="#" data-page="<?php echo $page; ?>"><?php echo $content; ?></a></li>
			<?php endfor; ?>
			
			<?php if ( $paged < $wp_query->max_num_pages ) : ?>
			<li class="page-item"><a class="page-link" data-page="<?php echo $paged + 1; ?>" href="#" tabindex="-1"><?php echo $args['next_text']; ?></a></li>
			<?php endif; ?>

		</ul><!-- .pagination -->
	</nav><!-- .pagination-nav -->

	<?php
}

endif; // theme_posts_ajax_pagination

if ( ! function_exists( 'theme_the_posts_pagination' ) ) :
/**
 * The Posts Pagination
 */
function theme_the_posts_pagination( $args = array() )
{
	$defaults = apply_filters( 'theme/the_posts_pagination_defaults', array
	(
		'prev_text'          => sprintf( '%2$s<span class="sr-only">%1$s</span>', __( 'Previous page', 'theme' ), theme_get_icon( 'arrow-left' ) ),
		'next_text'          => sprintf( '<span class="sr-only">%1$s</span>%2$s', __( 'Next page', 'theme' ), theme_get_icon( 'arrow-right' ) ),
		'before_page_number' => sprintf( '<span class="sr-only">%s</span>', __( 'Page', 'theme' ) ),
	));

	$args = wp_parse_args( $args, $defaults );

	the_posts_pagination( $args );
}

endif; // theme_the_posts_pagination

if ( ! function_exists( 'theme_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function theme_posted_on()
{
	// Get the author name; wrap it in a link.
	$byline = sprintf(
		/* translators: %s: post author */
		__( 'by %s', 'theme' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a></span>'
	);

	// Finally, let's write all of this to the page.
	echo '<span class="posted-on">' . theme_time_link() . '</span><span class="byline"> ' . $byline . '</span>';
}
endif; // theme_posted_on

if ( ! function_exists( 'theme_time_link' ) ) :
/**
 * Gets a nicely formatted string for the published date.
 */
function theme_time_link()
{
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) 
	{
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		get_the_date( DATE_W3C ),
		get_the_date(),
		get_the_modified_date( DATE_W3C ),
		get_the_modified_date()
	);

	// Wrap the time string in a link, and preface it with 'Posted on'.
	return sprintf(
		/* translators: %s: post date */
		__( '<span class="sr-only">Posted on</span> %s', 'theme' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);
}
endif; // theme_time_link

if ( ! function_exists( 'theme_site_logo' ) ) :
/**
 * Site Logo
 *
 * Outputs the site's logo if available.
 * If not, the blog name will be displayed.
 */
function theme_site_logo()
{
	$logos = array
	(
		'dark'        => array( 'url' => get_theme_mod( 'site_logo_dark' )	     , 'type' => array( 'dark', 'large' ) ),
		'dark_small'  => array( 'url' => get_theme_mod( 'site_logo_dark_small' ) , 'type' => array( 'dark', 'small' ) ),
		'light'       => array( 'url' => get_theme_mod( 'site_logo_light' )	     , 'type' => array( 'light', 'large' ) ),
		'light_small' => array( 'url' => get_theme_mod( 'site_logo_light_small' ), 'type' => array( 'light', 'small' ) )
	);

	// Filter logo's with attachments

	$logos = array_filter( $logos, function( $logo )
	{
		return $logo['url'];
	});

	// Large logos are also small logos when small version is not set.
	
	if ( isset( $logos['dark'] ) && ! isset( $logos['dark_small'] ) ) 
	{
		$logos['dark']['type'] = array_merge( $logos['dark']['type'], $logos['dark_small']['type'] );
	}

	if ( isset( $logos['light'] ) && ! isset( $logos['light_small'] ) ) 
	{
		$logos['dark']['type'] = array_merge( $logos['light']['type'], $logos['light_small']['type'] );
	}

	// Check if there are any logos

	if ( count( $logos ) ) 
	{
		// Prints images

		foreach ( $logos as $logo ) 
		{
			$atts = array
			(
				'src'   => $logo['url'],
				'class' => 'site-logo'
			);

			foreach ( $logo['type'] as $type ) 
			{
				$atts['class'] .= " logo-$type";
			}

			printf( '<img%s>', theme_esc_attr( $atts ) );
		}
	}

	// Displays blog name

    else
    {
        echo esc_html( get_bloginfo( 'name' ) );
    }
}
endif; // theme_site_logo

if ( ! function_exists( 'theme_carousel' ) ) :
/**
 * Carousel
 *
 * Renders an Owl Carousel with the looks of a Bootstrap carousel.
 * 
 * Dependencies:
 * wp_enqueue_script( 'owl-carousel' );
 * wp_enqueue_style( 'owl-carousel' );
 *
 * @link https://owlcarousel2.github.io/OwlCarousel2
 */
function theme_carousel( $args )
{
	static $counter = 0;

	/**
	 * Arguments
	 *
	 * @param id 		 string The carousel id (optional) 
	 * @param query 	 string|array|WP_Query WP Query arguments (required) 
	 * @param items 	 integer|array The number of items to see on the screen. (optional, default: 3)
	 *					 e.g.: `3` or per grid breakpoint: `array( 'xs' => 1,'sm' => 3 )`
	 * @param autoplay 	 boolean (optional, default: true)
	 * @param loop 		 boolean (optional, default: true)
	 * @param controls 	 boolean Show dots navigation (optional, default: true)
	 * @param indicators boolean Show next/prev buttons (optional, default: false)
	 * @param template   string The item template. (optional, default: (empty))
	 * 					 Filename: `carousel-item-{template}.php`. default: `carousel-item.php`
	 */

	$defaults = array
	(
		'id'         => sprintf( 'carousel-%d', ++$counter ),
		'query'      => '',
		'items'      => 3,
		'autoplay'   => true,
		'loop'       => true,
		'controls'   => true,
		'indicators' => false,
		'template'   => ''
	);

	$args = wp_parse_args( $args, $defaults );

	extract( $args, EXTR_SKIP );

	// Sanitize arguments

	if ( ! is_array( $items ) ) 
	{
		$items = array( 'xs' => $items );
	}

	else
	{
		$items = wp_parse_args( $items );
	}

	if ( $query instanceof WP_Query ) 
	{
		$the_query = $query;
	}

	else
	{
		$the_query = new WP_Query( $query );
	}

	// Stop when no posts are found

	if ( ! $the_query->have_posts() ) 
	{
		return;
	}

	// Set the amount of items to show per grid breakpoint

	$breakpoints = theme_get_grid_breakpoints();

	$responsive = array();

	foreach ( $breakpoints as $breakpoint => $width ) 
	{
		if ( ! isset( $items[ $breakpoint ] ) ) 
		{
			continue;
		}

		$amount = intval( $items[ $breakpoint ] );

		if ( ! $amount ) 
		{
			continue;
		}

		$responsive[ $width ] = array
		(
			'items' => $amount
		);
	}

	// Template

	?>

	<div id="<?php echo esc_attr( $id ); ?>" class="theme-carousel">
	
  		<div class="owl-carousel">
        	<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	    	<?php get_template_part( 'template-parts/carousel-item', $template ); ?>
	    	<?php endwhile; wp_reset_postdata(); ?>
		</div>

		<?php if ( $controls ) : ?>

		<a class="carousel-control-prev" href="#" role="button">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only"><?php esc_html_e( 'Previous', 'theme' ); ?></span>
		</a>

		<a class="carousel-control-next" href="#" role="button">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only"><?php esc_html_e( 'Next', 'theme' ); ?></span>
		</a>

		<?php endif; ?>

	</div>

	<script type="text/javascript">

		<?php 

			$options = array
			(
				'responsive' => $responsive,
				'loop'       => $loop ? 1 : 0,
				'autoplay'   => $autoplay ? 1 : 0,
				'nav'        => 0, // we use a custom navigation
				'dots'       => $indicators ? 1 : 0
			);

			// TODO : js file

		?>

		jQuery( document ).ready( function( $ )
		{
			var $elem = $( '#<?php echo esc_attr( $id ); ?>' );

	  		$elem.find( '.owl-carousel' ).owlCarousel( <?php echo json_encode( $options ); ?> );
			
			$elem.on( 'click', '.carousel-control-next', function( event )
			{
				event.preventDefault();

				$elem.find( '.owl-carousel' ).trigger( 'next.owl.carousel' );
			});

			$elem.on( 'click', '.carousel-control-prev', function( event )
			{
				event.preventDefault();

				$elem.find( '.owl-carousel' ).trigger( 'prev.owl.carousel' );
			});
		});

	</script>

	<?php
}
endif; // theme_carousel
