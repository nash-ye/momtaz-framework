<?php

add_action( 'before_momtaz_setup', 'sample_before_momtaz_setup' );

/**
 * Load all the necessary code before Momtaz setup.
 *
 * @return void
 * @since 0.1
 */
function sample_before_momtaz_setup() {

	// Define the theme prefix.
	define( 'THEME_PREFIX', 'sample' );

}

add_action( 'after_momtaz_setup', 'sample_after_momtaz_setup' );

/**
 * Load all the necessary code after Momtaz setup.
 *
 * @return void
 * @since 0.1
 */
function sample_after_momtaz_setup() {

	/*
	 * You might use this function to configure
	 * Momtaz settings like Layout, Stacks...etc
	 *
	 * Remove this comment, and write your code!
	 */

}