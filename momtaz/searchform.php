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

static $search_num = 0; ++$search_num ?>

<div id="search-form-container-<?php echo $search_num ?>" class="search-form-container">

	<?php

		Nmwdhj\create_element(
			array(
				'type'      => 'form',
				'atts'      => array(
					'method'    => 'GET',
					'role'      => 'search',
					'class'     => 'search-form',
					'action'    => home_url( '/' ),
				),
			)
		)->add(
			array(
				'name'      => 's',
				'type'      => 'input_search',
				'value'     => get_search_query(),
				'atts'      => array(
					'required'      => true,
					'class'         => 'search-text',
					'title'         => _x( 'Search for:', 'label', 'momtaz' ),
					'placeholder'   => _x( 'Search this site...', 'placeholder', 'momtaz' ),
				),
			),
			array( 'key' => 'search', 'priority' => 10 )
		)->add(
			array(
				'type'      => 'input_submit',
				'value'     => __( 'Search', 'momtaz' ),
				'atts'      => array( 'class' => 'search-submit' ),
			),
			array( 'key' => 'submit', 'priority' => 5 )
		)->output();

	?>

</div> <!-- .search-form-container -->
