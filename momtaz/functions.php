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
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	// Add support for the Momtaz features.
	add_theme_support( 'momtaz-core-scripts', array() );
	add_theme_support( 'momtaz-core-menus', array( 'primary' ) );
	add_theme_support( 'momtaz-core-sidebars', array( 'primary' ) );
	add_theme_support( 'momtaz-core-theme-settings', array( 'about' ) );

}

add_action( 'after_momtaz_setup', 'momtaz_register_theme_modules' );

/**
 * Register the default theme modules.
 *
 * @return void
 * @since 1.1
 */
function momtaz_register_theme_modules() {

	if ( ! defined( 'Nmwdhj\VERSION' ) ) {

		// Register the 'Momtaz Nmwdhj' module.
		Momtaz_Modules::register( array(
			'slug' => 'momtaz-nmwdhj',
			'name' => 'Momtaz Nmwdhj',
			'path' => 'momtaz-nmwdhj/nmwdhj.php',
			'settings' => array(
				'auto' => true,
				'once' => true,
			),
		) );

	}

	if ( ! function_exists( 'get_the_image' ) ) {

		// Register the 'Get The Image' module.
		Momtaz_Modules::register( array(
			'slug' => 'get-the-image',
			'name' => 'Get The Image',
			'path' => 'get-the-image/get-the-image.php',
			'settings' => array(
				'auto' => true,
				'once' => true,
			),
		) );

	}

	if ( ! function_exists( 'loop_pagination' ) ) {

		// Register the 'Loop Pagination' module.
		Momtaz_Modules::register( array(
			'slug' => 'loop-pagination',
			'name' => 'Loop Pagination',
			'path' => 'loop-pagination/loop-pagination.php',
			'settings' => array(
				'auto' => true,
				'once' => true,
			),
		) );

	}

}

add_action( 'after_setup_theme', 'momtaz_theme_setup' );

/**
 * Setup the framework's theme.
 *
 * @return void
 * @since 1.3
 */
function momtaz_theme_setup() {

	Momtaz_Zones::add_callback( 'head', 'momtaz_main_stylesheet' );
	Momtaz_Zones::add_callback( 'head', 'momtaz_locale_stylesheet' );
	Momtaz_Zones::add_callback( 'head', 'momtaz_meta_generator' );
	Momtaz_Zones::add_callback( 'head', 'momtaz_meta_designer' );

}

// Load the Momtaz Framework class file.
require( trailingslashit( get_template_directory() ) . 'includes/momtaz.php' );
