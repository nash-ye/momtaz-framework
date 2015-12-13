<?php
/**
 * Index Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */

Momtaz_Zones::add_callback( 'content', 'momtaz_template_content' );

/**
 * Outputs the current template content.
 *
 * @return void
 * @since 1.3
 */
function momtaz_template_content() { ?>

	<section id="primary-content">
		<?php momtaz_context_template( 'loop' ) ?>
	</section> <!-- #primary-content --><?php

}

// Load the structure template.
momtaz_context_template( 'structure' );
