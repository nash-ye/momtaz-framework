<?php

// Register the core sidebars.
add_action( 'widgets_init', 'momtaz_register_core_sidebars' );

/**
 * Registers supported widget areas for the theme.
 *
 * Register the supported widget areas to be used within the theme. The default list includes all of the asides
 * and the utility widget areas throughout the theme. By default, the framework registers the 'primary' widget
 * area only. The 'momtaz_core_sidebars' filter hook can be used to hook into the default widget areas list.
 *
 * @uses momtaz_get_supported_core_sidebars() Get the supported core sidebars.
 * @uses momtaz_register_sidebar() Register a single sidebar.
 * @access private
 * @return void
 * @since 1.2
 */
function momtaz_register_core_sidebars() {

	// Get the theme-supported sidebars.
	$sidebars = momtaz_get_supported_core_sidebars();

	// If the theme doesn't add support for any sidebars, return.
	if ( empty( $sidebars ) ) {
		return;
	}

	// Get the available core sidebars.
	$core_sidebars = array(
		'primary' => array(
			'name'         => _x( 'Primary', 'sidebar', 'momtaz' ),
			'description'  => __( 'The main (primary) widget area.', 'momtaz' ),
		),
		'secondary' => array(
			'name'         => _x( 'Secondary', 'sidebar', 'momtaz' ),
			'description'  => __( 'The second most important widget area.', 'momtaz' ),
		),
		'subsidiary' => array(
			'name'         => _x( 'Subsidiary', 'sidebar', 'momtaz' ),
			'description'  => __( 'Displayed within the site\'s footer area.', 'momtaz' ),
		),
		'header' => array(
			'name'         => _x( 'Header', 'sidebar', 'momtaz' ),
			'description'  => __( 'Displayed within the site\'s header area.', 'momtaz' ),
		),
		'before-entry' => array(
			'name'         => _x( 'Before Entry', 'sidebar', 'momtaz' ),
			'description'  => __( 'Loaded on singular post (page, attachment, etc.) views before the post title.', 'momtaz' ),
		),
		'after-entry' => array(
			'name'         => _x( 'After Entry', 'sidebar', 'momtaz' ),
			'description'  => __( 'Loaded on singular post (page, attachment, etc.) views before the comments area.', 'momtaz' ),
		),
	);

	$core_sidebars = apply_filters( 'momtaz_core_sidebars', $core_sidebars );

	// Loop through the supported sidebars.
	foreach ( $sidebars as $sidebar ) {

		// Make sure the given sidebar is one of the core sidebars.
		if ( isset( $core_sidebars[ $sidebar ] ) ) {

			// If no 'id' was given, use the $sidebar.
			if ( ! isset( $core_sidebars[ $sidebar ]['id'] ) ) {
				$core_sidebars[ $sidebar ]['id'] = $sidebar;
			}

			// Register the sidebar.
			momtaz_register_sidebar( $core_sidebars[ $sidebar ] );

		}

	}

}

/**
 * Get the supported core sidebars.
 *
 * Get the list of theme-supported sidebars to register each one of them via momtaz_register_core_sidebars()
 * as a ready to use widget area.
 *
 * @uses get_theme_support() Get support of a certain theme feature.
 * @return array
 * @since 1.2
 */
function momtaz_get_supported_core_sidebars() {

	$sidebars = get_theme_support( 'momtaz-core-sidebars' );

	if ( ! empty( $sidebars ) ) {
		$sidebars = reset( $sidebars );
	}

	return (array) $sidebars;

}

/**
 * Register a single sidebar.
 *
 * Register a single sidebar with theme default arguments and return its ID. The 'momtaz_get_sidebar_defaults'
 * filter hook can be used to hook into the default arguments array. The arguments of each sidebar can be set
 * separately by hooking into 'momtaz_core_sidebars'.
 *
 * @param array $args An array of sidebar arguments.
 * @uses register_sidebar() Register a sidebar and return its ID in wordpress.
 * @uses wp_parse_args() Merge an array of arguments with the default array in wordpress.
 * @return string
 * @since 1.1
 */
function momtaz_register_sidebar( $args ) {

	// Set up some default sidebar arguments.
	$defaults = apply_filters( 'momtaz_get_sidebar_defaults', array(
		'before_widget' => '<aside id="%1$s" class="widget-container %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget'  => '</div></aside>',
		'before_title'  => '<header class="widget-header"><h1 class="widget-title">',
		'after_title'   => '</h1></header>',
	) );

	// Parse the sidebar arguments and defaults.
	$args = wp_parse_args( $args, $defaults );

	// Register the sidebar.
	return register_sidebar( $args );

}