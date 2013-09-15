<?php
/**
 * Index Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */

add_action( momtaz_format_hook( 'primary_content' ), 'momtaz_index_template_primary_content' );

/**
 * Outputs the index.php template primary content.
 *
 * @return void
 * @since 1.1
 */
function momtaz_index_template_primary_content() {

	// Load the posts loop template.
	momtaz_context_template( 'loop' );

} // end momtaz_index_template_primary_content()

// Load the structure template.
momtaz_context_template( 'structure' );