<?php
/**
 * Search Form Template
 *
 * The search form template displays the search form.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */

static $search_num = 0; ++$search_num; ?>

<div id="<?php echo "search-form-container-{$search_num}"; ?>" class="search-form-container">

	<?php do_action( momtaz_format_hook( 'before_search_form' ) ); ?>

	<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form" role="search">

		<?php

			// Load the Momtaz Nmwdhj module.
			Momtaz_Modules::load_module( 'momtaz-nmwdhj' );

			// Create and output the search text input.
			Nmwdhj\create_element( 'input_search' )
				->set_atts( array(
					'placeholder' => _x( 'Search this site...', 'placeholder', 'momtaz' ),
					'title' => _x( 'Search for:', 'label', 'momtaz' ),
					'class' => 'search-text',
					'required' => true,
				  ) )
				->set_value( get_search_query( false ) )
				->set_name( 's' )
				->output();

			// Create and output the search submit button.
			Nmwdhj\create_element( 'input_submit' )
				->set_atts( array( 'class' => 'search-submit' ) )
				->set_value( __( 'Search', 'momtaz' ) )
				->output();

		?>

	</form> <!-- .search-form -->

	<?php do_action( momtaz_format_hook( 'after_search_form' ) ); ?>

</div> <!-- .search-form-container -->