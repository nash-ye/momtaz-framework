<?php

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

	$generators = implode( ',', array_filter( array( 'WordPress', wp_get_theme( get_template() )->get( 'Name' ) ) ) );

	if ( empty( $generators ) ) {
		return;
	}

	$meta = '<meta' . momtaz_get_html_atts( array( 'name' => 'generator', 'content' => $generators ) ) . '>' . "\n";

	// Allow plugins/child-themes to filter the generator meta.
	$meta = apply_filters( 'momtaz_meta_generator', $meta, $generators );

	return $meta;

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

	// If it is empty, return NULL.
	if ( empty( $designer ) ) {
		return;
	}

	$meta = '<meta' . momtaz_get_html_atts( array( 'name' => 'designer', 'content' => $designer ) ) . '>' . "\n";

	// Allow plugins/child-themes to filter the designer meta.
	$meta = apply_filters( 'momtaz_meta_designer', $meta, $designer );

	return $meta;

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
	$link = apply_filters( 'momtaz_get_site_link', $link, $args );
	return $link;

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
	$link = apply_filters( 'momtaz_get_wp_link', $link, $args );
	return $link;

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
	$link = apply_filters( 'momtaz_get_theme_link', $link, $theme, $args );
	return $link;

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
	$link = apply_filters( 'momtaz_get_theme_author_link', $link, $theme, $args );
	return $link;

}
