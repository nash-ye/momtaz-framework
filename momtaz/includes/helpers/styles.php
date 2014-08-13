<?php

/**
 * Display the main stylesheet link tag.
 *
 * @return void
 * @since 1.1
 */
function momtaz_main_stylesheet() {
	echo momtaz_get_style_loader_tag( momtaz_get_main_stylesheet_uri() );
}

/**
 * Get the main stylesheet URI.
 *
 * @return string
 * @since 1.1
 */
function momtaz_get_main_stylesheet_uri() {

	$stylesheet_uri = get_stylesheet_uri();

	if ( momtaz_is_style_debug() ) {
		$stylesheet_uri = momtaz_get_dev_stylesheet_uri( $stylesheet_uri );
	}

	$stylesheet_uri = apply_filters( 'momtaz_get_main_stylesheet_uri', $stylesheet_uri );
	return $stylesheet_uri;

}

/**
 * Display the localized stylesheet link tag.
 *
 * @return void
 * @since 1.1
 */
function momtaz_locale_stylesheet() {
	echo momtaz_get_style_loader_tag( momtaz_get_locale_stylesheet_uri() );
}

/**
 * Get the localized stylesheet URI.
 *
 * @return string
 * @since 1.1
 */
function momtaz_get_locale_stylesheet_uri() {

	$stylesheet_uri = get_locale_stylesheet_uri();

	if ( momtaz_is_style_debug() ) {
		$stylesheet_uri = momtaz_get_dev_stylesheet_uri( $stylesheet_uri );
	}

	$stylesheet_uri = apply_filters( 'momtaz_get_locale_stylesheet_uri', $stylesheet_uri );
	return $stylesheet_uri;

}

/**
 * Get the possible development stylesheet URI.
 *
 * @return string
 * @since 1.0
 */
function momtaz_get_dev_stylesheet_uri( $stylesheet_uri ) {

	if ( ! empty( $stylesheet_uri ) ) {

		$pathinfo = pathinfo( parse_url( $stylesheet_uri, PHP_URL_PATH ) );
		$stylesheet_dir = $_SERVER['DOCUMENT_ROOT'] . $pathinfo['dirname'];

		if ( is_dir( $stylesheet_dir ) ) {

			foreach ( momtaz_get_dev_stylesheet_suffixs() as $dev_suffix ) {

				$dev_basename = $pathinfo['filename'] . $dev_suffix;

				if ( file_exists( trailingslashit( $stylesheet_dir ) . $dev_basename ) ) {
					$stylesheet_uri = str_replace( $pathinfo['basename'], $dev_basename, $stylesheet_uri );
				}

			}

		}

	}

	return $stylesheet_uri;

}

/**
 * Retrieve stylesheet link tag with the proper attributes.
 *
 * @return string
 * @since 1.0
 */
 function momtaz_get_style_loader_tag( $stylesheet_uri, $atts = '' ){

	if ( empty( $stylesheet_uri ) ) {
		return;
	}

	$atts = wp_parse_args( $atts, array(
		'href' => $stylesheet_uri,
		'media' => 'all',
	) );

	if ( empty( $atts['rel'] ) ) {

		switch( pathinfo( $atts['href'], PATHINFO_EXTENSION ) ) {

			case 'less':
				$atts['rel'] = 'stylesheet/less';
				break;

			default:
				$atts['rel'] = 'stylesheet';
				break;

		}

	}

	$output = '<link' . momtaz_get_html_atts( $atts ) . ' />' . "\n";
	$output = apply_filters( 'momtaz_get_style_loader_tag', $output, $atts );
	return $output;

}

/**
 * Get the development stylesheet filename suffixs.
 *
 * @return array
 * @since 1.0
 */
function momtaz_get_dev_stylesheet_suffixs() {
	return apply_filters( 'momtaz_get_dev_stylesheet_suffixs', array( '.less', '.dev.css' ) );
}

/**
 * Check the Style Debug status.
 *
 * @return bool
 * @since 1.2.1
 */
function momtaz_is_style_debug() {
	return apply_filters( 'momtaz_is_style_debug', defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
}