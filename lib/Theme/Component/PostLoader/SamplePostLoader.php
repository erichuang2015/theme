<?php 

namespace Theme\Component\PostLoader;

/**
 * Post Loader Example
 *
 * - Set `autoload` css class on form fields to automatically load on change.
 * - <label> `active` css class is set when containing radio or checkbox is checked.
 */
class SamplePostLoader extends \Theme\Core\PostLoader\PostLoader
{
	/**
	 * Construct
	 */
	public function __construct()
	{
		parent::__construct( 'sample' );
	}

	/**
	 * Inside
	 */
	public function inside()
	{
		echo '<div class="row">';
			echo '<div class="col-md-4">';
				$this->form();
			echo '</div>';
			echo '<div class="col">';
				$this->content();
			echo '</div>';
		echo '</div>';
	}

	/**
	 * Form Inside
	 */
	public function form_inside()
	{
		$this->settings_fields();

		$this->nav( 'category', array
		(
			'before'       => '<nav class="%1$s">',
			'title'        => null,
			'before_items' => '<div class="items d-flex flex-column">',
			'before_item'  => '',
			'after_item'   => '',
			'after_items'  => '</div>',
			'after'        => '</nav>',
			'type'         => 'checkbox',
			'radio_all'    => __( 'Show all', 'theme' ),
		));

		$this->nav( 'post_tag', array
		(
			'before'       => '<nav class="%1$s">',
			'title'        => null,
			'before_items' => '<div class="items d-flex flex-column">',
			'before_item'  => '',
			'after_item'   => '',
			'after_items'  => '</div>',
			'after'        => '</nav>',
			'type'         => 'checkbox',
			'radio_all'    => __( 'Show all', 'theme' ),
		));
	}

	/**
	 * Query Arguments
	 *
	 * @param array $query_args
	 */
	public function query_args( &$query_args = array() )
	{
		$query_args['tax_query'] = array
		(
			'relation' => 'AND',
		);

		$this->apply_nav( 'category', $query_args );
		$this->apply_nav( 'post_tag', $query_args );
	}
}
