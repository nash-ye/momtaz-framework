<?php
/**
 * Rather than lumping all theme functions into a single file, this functions file is used for
 * initializing the theme framework, which activates files in the order that it needs. Users
 * should create a child theme and make changes to its functions.php file (not this one).
 *
 * @package Momtaz
 * @subpackage Functions
 * @since Momtaz Theme 1.0
 */

add_action( 'before_momtaz_setup', 'momtaz_support_theme_features' );

/**
 * Add support for the needed features.
 *
 * @return void
 * @since 1.0
 */
function momtaz_support_theme_features() {

	// Add support for WordPress features.
	add_theme_support( 'automatic-feed-links' );

	// Add support for the Momtaz features.
	add_theme_support( 'momtaz-core-drop-downs' );
	add_theme_support( 'momtaz-core-menus', array( 'primary' ) );
	add_theme_support( 'momtaz-core-sidebars', array( 'primary' ) );
	add_theme_support( 'momtaz-core-theme-settings', array( 'about' ) );

} // end momtaz_support_theme_features()

add_action( 'after_momtaz_setup', 'momtaz_register_theme_modules' );

/**
 * Register the default theme modules.
 *
 * @return void
 * @since 1.1
 */
function momtaz_register_theme_modules() {

	// Register the 'Momtaz Nmwdhj' module.
	Momtaz_Modules::register( array(
		'slug' => 'momtaz-nmwdhj',
		'name' => 'Momtaz Nmwdhj',
		'path' => 'momtaz-nmwdhj/nmwdhj.php',
		'settings' => array(
			'is_loaded_callback' => function() {
				return function_exists( 'Nmwdhj\create_element' );
			},
			'auto' => false,
			'once' => true,
		),
	) );

	// Register the 'Get The Image' module.
	Momtaz_Modules::register( array(
		'slug' => 'get-the-image',
		'name' => 'Get The Image',
		'path' => 'get-the-image/get-the-image.php',
		'settings' => array(
			'is_loaded_callback' => function() {
				return function_exists( 'get_the_image' );
			},
			'auto' => true,
			'once' => true,
		),
	) );

	// Register the 'Loop Pagination' module.
	Momtaz_Modules::register( array(
		'slug' => 'loop-pagination',
		'name' => 'Loop Pagination',
		'path' => 'loop-pagination/loop-pagination.php',
		'settings' => array(
			'is_loaded_callback' => function() {
				return function_exists( 'loop_pagination' );
			},
			'auto' => false,
			'once' => true,
		),
	) );

} // end momtaz_register_theme_modules()

add_action( 'after_momtaz_setup', 'momtaz_register_theme_stacks' );

/**
 * Register the default theme template stack.
 *
 * @return void
 * @since 1.1
 */
function momtaz_register_theme_stacks() {

	if ( is_child_theme() ) {

		// Register the child theme directory.
		momtaz_register_template_stack( array(
			'path' => CHILD_THEME_DIR,
			'uri' => CHILD_THEME_URI,
			'slug' => 'child-theme',
			'priority' => 5,
		) );

	} // end if

	// Register the parent theme directory.
	momtaz_register_template_stack( array(
		'slug' => 'parent-theme',
		'path' => THEME_DIR,
		'uri' => THEME_URI,
		'priority' => 10,
	) );

} // end momtaz_register_theme_stacks()

// Load the Momtaz Framework class file.
require( trailingslashit( get_template_directory() ) . 'includes/momtaz.php' );