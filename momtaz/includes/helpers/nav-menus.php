<?php

add_action( 'init', 'momtaz_register_core_menus' );

/**
 * Registers the framework's default menus.
 *
 * Registers default menus to be used as locations within the theme for user created menus to be shown.
 * By default, the framework registers the 'primary' menu only. The 'momtaz_core_menus' filter hook can
 * be used to hook into the array of default menus.
 *
 * @uses register_nav_menu() Registers a navigation menu for the theme.
 * @uses momtaz_get_supported_core_menus() Get the supported Momtaz core menus.
 * @access private
 * @return void
 * @since 1.2
 */
function momtaz_register_core_menus() {

	$menus = momtaz_get_supported_core_menus();

	if ( empty( $menus ) ) {
		return;
	}

	$core_menus = array(
		'primary'    => _x( 'Primary', 'nav menu location', 'momtaz' ),
		'secondary'  => _x( 'Secondary', 'nav menu location', 'momtaz' ),
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
 * Get the supported Momtaz core menus.
 *
 * Get the array of the menus supported by the theme to register each one of them as a ready to use location
 * within the theme via momtaz_register_core_menus().
 *
 * @see momtaz_register_core_menus() Registers the the framework's default menus.
 * @uses get_theme_support() Get support of a certain theme feature.
 * @return array
 * @since 1.2
 */
function momtaz_get_supported_core_menus() {

	$menus = get_theme_support( 'momtaz-core-menus' );

	if ( ! empty( $menus ) ) {
		$menus = reset( $menus );
	}

	return (array) $menus;

}

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