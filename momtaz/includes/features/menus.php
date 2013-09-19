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
add_action( 'init', 'momtaz_register_menus' );

/**
 * Registers the the framework's default menus.  By default, the framework registers the 'primary' menu,
 * which is technically a location within the theme for a user-created menu to be shown.
 *
 * @uses register_nav_menu() Registers a nav menu with WordPress.
 * @since 1.0
 */
function momtaz_register_menus() {

   $args = get_theme_support( 'momtaz-core-menus' );

	if ( is_array( $args[0] ) ) {

		// Register the 'primary' menu.
		if ( in_array( 'primary', $args[0], true ) )
			register_nav_menu( 'primary', _x( 'Primary', 'nav menu location', 'momtaz' ) );

		// Register the 'secondary' menu.
		if ( in_array( 'secondary', $args[0], true ) )
			register_nav_menu( 'secondary', _x( 'Secondary', 'nav menu location', 'momtaz' ) );

		// Register the 'subsidiary' menu.
		if ( in_array( 'subsidiary', $args[0], true ) )
			register_nav_menu( 'subsidiary', _x( 'Subsidiary', 'nav menu location', 'momtaz' ) );

	} // end if

} // end momtaz_register_menus()

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
} // end momtaz_page_menu_args()