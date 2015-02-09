<?php

/**
 * Display the current page title.
 *
 * @return void
 * @since 1.0
 */
function momtaz_title( $args = '' ) {
	do_action( 'momtaz_title', $args );
}

add_action( 'momtaz_title', 'momtaz_wp_title' );

/**
 * Outputs the page title using the wp_title() function.
 *
 * @return void
 * @since 1.0
 */
function momtaz_wp_title( $args = '' ) {

	$args = wp_parse_args( $args, array(
		'seplocation' => 'right',
		'separator'   => '|',
		'echo'        => true,
	) );

	$title = wp_title( $args['separator'], false, $args['seplocation'] );

	if ( ! $args['echo'] ) {
		return $title;
	}

	echo $title;

}

add_filter( 'wp_title', 'momtaz_filter_wp_title', 10, 2 );

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

	if ( ! empty( $site_description ) && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'momtaz' ), max( $paged, $page ) );
	}

	return $title;

}

/**
 * Outputs the generator meta tag.
 *
 * @return void
 * @since 1.1
*/
function momtaz_meta_generator() {
	echo momtaz_get_meta_generator();
}

/**
 * Returns the generator meta tag.
 *
 * @return string
 * @since 1.3
*/
function momtaz_get_meta_generator() {

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

	return '<meta' . momtaz_get_html_atts( array( 'name' => 'generator', 'content' => implode( ',', $generator ) ) ) . '>' ."\n";

}

/**
 * Outputs the designer meta tag.
 *
 * @return void
 * @since 1.1
*/
function momtaz_meta_designer() {
	echo momtaz_get_meta_designer();
}

/**
 * Returns the designer meta tag.
 *
 * @return string
 * @since 1.3
*/
function momtaz_get_meta_designer() {

	// Get the current theme author name.
	$designer = wp_get_theme( get_stylesheet() )->get( 'Author' );

	// Allow plugins/child-themes to filter the designer meta.
	$designer = apply_filters( 'momtaz_meta_designer', $designer );
	$designer = array_filter( array_unique( (array) $designer ) );

	// If it is empty, return NULL.
	if ( empty( $designer ) ) {
		return;
	}

	return '<meta' . momtaz_get_html_atts( array( 'name' => 'designer', 'content' => implode( ',', $designer ) ) ) . '>' ."\n";

}

/**
 * Outputs a link back to the site.
 *
 * @return void
 * @since 1.3
 */
function momtaz_site_link( array $args = array() ) {
	echo momtaz_get_site_link( $args );
}

/**
 * Returns a link back to the site.
 *
 * @return string
 * @since 1.3
 */
function momtaz_get_site_link( array $args = array() ) {

	$args = array_merge( array(
		'text'  => get_bloginfo( 'name', 'display' ),
		'atts'  => array(
			'href'  => home_url( '/' ),
			'class' => 'site-link',
			'rel'   => 'home',
		),
	), $args );

	$link = '<a' . momtaz_get_html_atts( $args['atts'] ) . '>' . $args['text'] . '</a>';
	return apply_filters( 'momtaz_get_site_link', $link, $args );

}

/**
 * Outputs a link back to WordPress.org.
 *
 * @return void
 * @since 1.3
 */
function momtaz_wp_link( array $args = array() ) {
	echo momtaz_get_wp_link( $args );
}

/**
 * Returns a link back to WordPress.org.
 *
 * @return string
 * @since 1.3
 */
function momtaz_get_wp_link( array $args = array() ) {

	$args = array_merge( array(
		'text'  => __( 'WordPress', 'momtaz' ),
		'atts'  => array(
			'href'  => 'http://wordpress.org',
			'class' => 'wp-link',
		),
	), $args );

	$link = '<a' . momtaz_get_html_atts( $args['atts'] ) . '>' . $args['text'] . '</a>';
	return apply_filters( 'momtaz_get_wp_link', $link, $args );

}

/**
 * Outputs a link back to a theme URI.
 *
 * @return void
 * @since 1.3
 */
function momtaz_theme_link( $theme, array $args = array() ) {
	echo momtaz_get_theme_link( $theme, $args );
}

/**
 * Returns a link back to a theme URI.
 *
 * @return string
 * @since 1.3
 */
function momtaz_get_theme_link( $theme, array $args = array() ) {

	if ( is_string( $theme ) ) {
		$theme = wp_get_theme( $theme );
	}

	if ( empty( $theme ) || ! $theme->exists() ) {
		return;
	}

	$args = array_merge( array(
		'text'  => $theme->display( 'Name', FALSE ),
		'atts'  => array(
			'href'  => $theme->get( 'ThemeURI' ),
			'class' => 'theme-link',
		),
	), $args );

	$link = '<a' . momtaz_get_html_atts( $args['atts'] ) . '>' . $args['text'] . '</a>';
	return apply_filters( 'momtaz_get_theme_link', $link, $theme, $args );

}

/**
 * Outputs a link back to a theme author URI.
 *
 * @return void
 * @since 1.3
 */
function momtaz_theme_author_link( $theme, array $args = array() ) {
	echo momtaz_get_theme_author_link( $theme, $args );
}

/**
 * Returns a link back to a theme author URI.
 *
 * @return string
 * @since 1.3
 */
function momtaz_get_theme_author_link( $theme, array $args = array() ) {

	if ( is_string( $theme ) ) {
		$theme = wp_get_theme( $theme );
	}

	if ( empty( $theme ) || ! $theme->exists() ) {
		return;
	}

	$args = array_merge( array(
		'text'  => $theme->display( 'Author', FALSE ),
		'atts'  => array(
			'href'  => $theme->get( 'AuthorURI' ),
			'class' => 'theme-author-link',
		),
	), $args );

	$link = '<a' . momtaz_get_html_atts( $args['atts'] ) . '>' . $args['text'] . '</a>';
	return apply_filters( 'momtaz_get_theme_author_link', $link, $theme, $args );

}