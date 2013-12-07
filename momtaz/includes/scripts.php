<?php
/**
 * Functions file for loading theme scripts.
 *
 * @package Momtaz
 * @subpackage Functions
 */

/**
 * Get the framework's available scripts list.
 *
 * @return array
 * @since 1.2
 */
function momtaz_get_scripts() {

	$scripts = array();

	// LessCSS preprocessor.
	$scripts['less'] = array(
		'src' => momtaz_theme_uri( 'content/scripts/less.js' ),
		'version' => Momtaz::VERSION,
	);

	// theme drop-downs preprocessor.
	$scripts['drop-downs'] = array(
		'src' => momtaz_theme_uri( 'content/scripts/dropdowns.js' ),
		'version' => Momtaz::VERSION,
		'deps' => array( 'jquery' ),
		'in_footer' => true,
	);

	return apply_filters( 'momtaz_get_scripts', $scripts );

}

/**
 * Registers JavaScript files for the framework.  This function merely registers scripts with WordPress using
 * the wp_register_script() function.  It does not load any script files on the site.  If a theme wants to register
 * its own custom scripts, it should do so on the 'wp_enqueue_scripts' hook.
 *
 * @return void
 * @since 1.0
 */
function momtaz_register_scripts() {

	// Register LessCSS script.
	wp_register_script( 'less', momtaz_theme_uri( 'content/scripts/less.js' ), false, Momtaz::VERSION );

	// Register the drop-downs script.
	wp_register_script( 'drop-downs', momtaz_theme_uri( 'content/scripts/dropdowns.js' ), array( 'jquery' ), Momtaz::VERSION, true );

}

/**
 * Tells WordPress to load the scripts needed for the framework using the wp_enqueue_script() function.
 *
 * @return void
 * @since 1.0
 */
function momtaz_enqueue_scripts() {

	if ( momtaz_is_style_dev_mode() ) {
		wp_enqueue_script( 'less' );
	}

	if ( current_theme_supports( 'momtaz-core-drop-downs' ) ) {
		wp_enqueue_script( 'drop-downs' );
	}

	if ( is_singular() && get_option( 'thread_comments' ) && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}

}