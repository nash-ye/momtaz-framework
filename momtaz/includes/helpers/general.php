<?php

/**
 * Display the current page title.
 *
 * @since 1.0
 */
function momtaz_title( $args = '' ) {
	do_action( 'momtaz_title', $args );
}

/**
 * The default title callback using the wp_title() function.
 *
 * @return void
 * @since 1.0
 */
function momtaz_wp_title( $args = '' ) {

	$args = wp_parse_args( $args, array(
		'seplocation' => 'right',
		'separator' => '|',
		'echo' => true,
	) );

	$title = wp_title( $args['separator'], false, $args['seplocation'] );

	if ( ! $args['echo'] ) {
		return $title;
	}

	echo $title;

}

/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @return string The filtered title.
 * @since 1.0
 */
function momtaz_filter_wp_title( $title, $sep ) {

	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );

	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'momtaz' ), max( $paged, $page ) );
	}

	return $title;

}

/**
 * Display the generator meta tag.
 *
 * @return void
 * @since 1.1
*/
function momtaz_meta_generator() {

	$generator = array( 'WordPress' );

	// Add the parent theme name, which will be Momtaz!
	$generator[] = wp_get_theme( get_template() )->get( 'Name' );

	// Allow plugins/child-themes to filter the generator meta.
	$generator = apply_filters( 'momtaz_meta_generator', $generator );
	$generator = array_filter( array_unique( (array) $generator ) );

	// If it is empty, return NULL.
	if ( empty( $generator ) ) {
		return;
	}

	echo '<meta' . momtaz_get_html_atts( array( 'name' => 'generator', 'content' => implode( ',', $generator ) ) ) . '>' ."\n";

}

/**
 * Display the designer meta tag.
 *
 * @return void
 * @since 1.1
*/
function momtaz_meta_designer() {

	// Get the current theme author name.
	$designer = wp_get_theme( get_stylesheet() )->get( 'Author' );

	// Allow plugins/child-themes to filter the designer meta.
	$designer = apply_filters( 'momtaz_meta_designer', $designer );
	$designer = array_filter( array_unique( (array) $designer ) );

	// If it is empty, return NULL.
	if ( empty( $designer ) ) {
		return;
	}

	echo '<meta' . momtaz_get_html_atts( array( 'name' => 'generator', 'content' => implode( ',', $designer ) ) ) . '>' ."\n";

}

/**
 * Display the classes for the body element.
 *
 * @param array $classes One or more classes to add to the class list.
 * @since 1.0
*/
function momtaz_filter_body_class( $classes ) {

	/* Momtaz Current Layout. */
	if ( ( $layout = Momtaz_Layouts::get_current_layout() ) ) {
		$classes[] = 'layout-' . trim( $layout->id );
	}


	/* Date classes. */
	$time = time() + ( get_option( 'gmt_offset' ) * 3600 );
	$classes[] = strtolower( gmdate( '\yY \mm \dd \hH l', $time ) );


	/* Check if the current theme is a parent or child theme. */
	$classes[] = ( is_child_theme() ? 'child-theme' : 'parent-theme' );


	// Get the global browsers vars.
	global $is_lynx, $is_gecko, $is_IE , $is_opera, $is_NS4, $is_safari, $is_chrome;

	/* Browser Detection Loop. */
	foreach ( array( 'gecko' => $is_gecko, 'opera' => $is_opera, 'lynx' => $is_lynx, 'ns4' => $is_NS4, 'safari' => $is_safari, 'chrome' => $is_chrome, 'ie' => $is_IE ) as $key => $value ) {

		if ( $value ) {
			$classes[] = 'browser-' . $key;
			break;
		}

	}


	// Register devices vars
	global $is_iphone;
	$is_ipod = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'iPod' );
	$is_ipad = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'iPad' );
	$is_android = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'Android' );
	$is_palmpre = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'webOS' );
	$is_blackberry = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'BlackBerry' );

	/* Devices Detection Loop. */
	foreach ( array( 'ipod' => $is_ipod, 'ipad' => $is_ipad, 'android' => $is_android, 'webos' => $is_palmpre, 'blackberry' => $is_blackberry , 'iphone' => $is_iphone ) as $key => $value ) {

		if ( $value ) {
			$classes[] = 'device-' . $key;
			break;
		}

	}


	// Register systems vars
	$is_Mac = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'Mac' );
	$is_Windows = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'Win' );
	$is_Linux = (bool) strpos( $_SERVER['HTTP_USER_AGENT'], 'Linux' );

	/* Systems Detection Loop. */
	foreach ( array( 'windows' => $is_Windows, 'linux' => $is_Linux, 'mac' => $is_Mac ) as $key => $value ) {

		if ( $value ) {
			$classes[] = 'system-' . $key;
			break;
		}

	}

	return (array) apply_filters( 'momtaz_body_class', $classes );

}
