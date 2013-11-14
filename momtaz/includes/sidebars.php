<?php
/**
 * Sets up the default framework sidebars if the theme supports them.  By default, the framework
 * registers six sidebars.  Themes may choose to use or not use these sidebars, create new sidebars, or
 * unregister individual sidebars.  A theme must register support for 'momtaz-core-sidebars' to use them.
 *
 * @package Momtaz
 * @subpackage Functions
 */

// Register the theme sidebars.
add_action( 'widgets_init', 'momtaz_register_sidebars' );

/**
 * Registers each widget area for the theme. This includes all of the asides
 * and the utility widget areas throughout the theme.
 *
 * @since 1.0
 */
function momtaz_register_sidebars() {

	// Get the theme-supported sidebars.
	$sidebars = get_theme_support( 'momtaz-core-sidebars' );

	// If the theme doesn't add support for any sidebars, return.
	if ( ! is_array( $sidebars[0] ) ) {
		return;
	}

	// Get the available core framework sidebars.
	$core_sidebars = array (

		'primary' => array (
			'name' => _x( 'Primary', 'sidebar', 'momtaz' ),
			'description' => __( 'The main (primary) widget area.', 'momtaz' )
		),
		'secondary' => array (
			'name' => _x( 'Secondary', 'sidebar', 'momtaz' ),
			'description' => __( 'The second most important widget area.', 'momtaz' ),
		),
		'subsidiary' => array (
			'name' => _x( 'Subsidiary', 'sidebar', 'momtaz' ),
			'description' => __( 'Displayed within the site\'s footer area.', 'momtaz' ),
		),
		'header' => array (
			'name' => _x( 'Header', 'sidebar', 'momtaz' ),
			'description' => __( 'Displayed within the site\'s header area.', 'momtaz' ),
		),
		'before-entry' => array (
			'name' => _x( 'Before Entry', 'sidebar', 'momtaz' ),
			'description' => __( 'Loaded on singular post (page, attachment, etc.) views before the post title.', 'momtaz' ),
		),
		'after-entry' => array (
			'name' => _x( 'After Entry', 'sidebar', 'momtaz' ),
			'description' => __( 'Loaded on singular post (page, attachment, etc.) views before the comments area.', 'momtaz' ),
		),

	);

	$core_sidebars = apply_filters( 'momtaz_core_sidebars', $core_sidebars );

	// Loop through the supported sidebars.
	foreach ( $sidebars[0] as $sidebar ) {

		// Make sure the given sidebar is one of the core sidebars.
		if ( isset( $core_sidebars[ $sidebar ] ) ) {

			// If no 'id' was given, use the $sidebar.
			if ( ! isset( $core_sidebars[ $sidebar ]['id'] ) ) {
				$core_sidebars[ $sidebar ]['id'] = $sidebar;
			}

			// Register the sidebar.
			momtaz_register_sidebar( $core_sidebars[ $sidebar ] );

		} // end if

	} // end foreach

} // end momtaz_register_sidebars()

/**
 * Register a single sidebar by the theme defaults and returns the ID.
 *
 * @see register_sidebar()
 * @since 1.1
 */
function momtaz_register_sidebar( $args ) {

	// Set up some default sidebar arguments.
	$defaults = array(
		'before_widget' => '<aside id="%1$s" class="widget-container %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></aside>',
		'before_title' => '<header class="widget-header"><h1 class="widget-title">',
		'after_title' => '</h1></header>',
	);

	$defaults = apply_filters( 'momtaz_get_sidebar_defaults', $defaults );

	// Parse the sidebar arguments and defaults.
	$args = wp_parse_args( $args, $defaults );

	// Register the sidebar.
	return register_sidebar( $args );

} // end momtaz_register_sidebar()