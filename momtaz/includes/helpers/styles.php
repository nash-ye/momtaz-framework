<?php

/**
 * Registers stylesheets for the framework.
 *
 * @access private
 * @return void
 * @since 1.3
 */
function momtaz_register_core_styles() {

	$core_styles = array(

		'normalize' => array(
			'src'       => momtaz_parent_theme_uri( 'content/styles/normalize.css' ),
			'version'   => Momtaz::VERSION,
		),

	);

	$core_styles = apply_filters( 'momtaz_core_styles', $core_styles );

	foreach( $core_styles as $key => $args ) {

		$args = array_merge( array(
			'handle'    => $key,
			'src'       => false,
			'deps'      => array(),
			'version'   => false,
			'media'     => 'all',
		), $args );

		wp_register_style(
			$args['handle'],
			$args['src'],
			$args['deps'],
			$args['version'],
			$args['media']
		);

	}

}

/**
 * Load the registered theme supported styles.
 *
 * Get the theme supported core styles needed for the framework and tell WordPress to
 * load them using the wp_enqueue_style() function. The function checks if the style is
 * registered before loading it.
 *
 * @uses momtaz_get_supported_core_styles() Get the supported core styles.
 * @access private
 * @return void
 * @since 1.3
 */
function momtaz_enqueue_core_styles() {

	$styles = momtaz_get_supported_core_styles();

	if ( ! empty( $styles ) ) {

		foreach( $styles as $style ) {

			if ( wp_style_is( $style, 'registered' ) ) {
				wp_enqueue_style( $style );
			}

		}

	}

}

/**
 * Get the supported core styles.
 *
 * Get an array of theme supported core styles to be registered in WordPress via momtaz_register_core_styles()
 * and then, loaded via momtaz_enqueue_core_styles().
 *
 * @return array
 * @since 1.3
 */
function momtaz_get_supported_core_styles() {

	$styles = get_theme_support( 'momtaz-core-styles' );

	if ( ! empty( $styles ) ) {
		$styles = reset( $styles );
	}

	return (array) $styles;

}

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
 function momtaz_get_style_loader_tag( $stylesheet_url, array $atts = array() ){

	if ( empty( $stylesheet_url ) ) {
		return;
	}

	$atts = array_merge( array(
		'href'  => $stylesheet_url,
		'media' => 'all',
	), $atts );

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
