<?php
/**
 * Index Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */

add_action( momtaz_format_hook( 'primary_content' ), 'momtaz_template_primary_content' );

/**
 * Outputs the current template primary content.
 *
 * @return void
 * @since 1.1
 */
function momtaz_template_primary_content() {

	// Load the posts loop template.
	momtaz_context_template( 'loop' );

}

// Load the structure template.
momtaz_context_template( 'structure' );