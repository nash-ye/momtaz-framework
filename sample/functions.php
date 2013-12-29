<?php

add_action( 'before_momtaz_setup', 'sample_before_momtaz_setup' );

/**
 * Load all the necessary code before Momtaz setup.
 *
 * @since Momtaz Sample 0.1
 * @return void
 */
function sample_before_momtaz_setup() {

	// Define the theme prefix.
	define( 'THEME_PREFIX', 'sample' );

}