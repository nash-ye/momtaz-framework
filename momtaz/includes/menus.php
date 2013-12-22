<?php
/*
 * The menus functions deal with registering nav menus within WordPress for the core framework.  Theme
 * developers may use the default menu(s) provided by the framework within their own themes, decide not
 * to use them, or register additional menus.
 *
 * @package Momtaz
 * @subpackage Functions
 */

// Register nav menus.
add_action( 'init', 'momtaz_register_core_menus' );

/**
 * Registers the the framework's default menus.  By default, the framework registers the 'primary' menu,
 * which is technically a location within the theme for a user-created menu to be shown.
 *
 * @uses register_nav_menu() Registers a nav menu with WordPress.
 * @since 1.2
 */
function momtaz_register_core_menus() {

	$menus = momtaz_get_supported_core_menus();

	if ( empty( $menus ) ) {
		return;
	}

	$core_menus = array(
		'primary' => _x( 'Primary', 'nav menu location', 'momtaz' ),
		'secondary' => _x( 'Secondary', 'nav menu location', 'momtaz' ),
		'subsidiary' => _x( 'Subsidiary', 'nav menu location', 'momtaz' ),
	);

	$core_menus = apply_filters( 'momtaz_core_menus', $core_menus );

	// Loop through the supported menus.
	foreach( $menus as $menu ) {

		// Make sure the given menu is one of the core menu.
		if ( isset( $core_menus[ $menu ] ) ) {

			// Register the menu.
			register_nav_menu( $menu, $core_menus[ $menu ] );

		}

	}

}

/**
 * Get the theme-supported core menus.
 *
 * @return array
 * @since 1.2
 */
function momtaz_get_supported_core_menus() {

	$menus = get_theme_support( 'momtaz-core-menus' );

	if ( ! $menus || ! is_array( $menus ) ) {
		return array();
	}

	$menus = reset( $menus );
	return (array) $menus;

}

// Add the home menu item.
add_filter( 'wp_page_menu_args', 'momtaz_page_menu_args' );

/**
 * Add the home-link button in the pages menu.
 *
 * @return array
 * @since 1.0
 */
function momtaz_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}