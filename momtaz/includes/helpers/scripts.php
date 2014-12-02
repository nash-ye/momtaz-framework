<?php

/**
 * Registers JavaScript files for the framework.
 *
 * @access private
 * @return void
 * @since 1.2
 */
function momtaz_register_core_scripts() {

	$core_scripts = array(

		'less' => array(
			'src'       => momtaz_theme_uri( 'content/scripts/less.js' ),
			'version'   => Momtaz::VERSION,
		),

	);

	$core_scripts = apply_filters( 'momtaz_core_scripts', $core_scripts );

	foreach( $core_scripts as $key => $args ) {

		$args = array_merge( array(
			'handle'    => $key,
			'src'       => false,
			'deps'      => array(),
			'version'   => false,
			'in_footer' => true,
		), $args );

		wp_register_script(
			$args['handle'],
			$args['src'],
			$args['deps'],
			$args['version'],
			$args['in_footer']
		);

	}

}

/**
 * Load the registered theme supported scripts.
 *
 * Get the theme supported core scripts and scripts needed for the framework and tell WordPress to
 * load them using the wp_enqueue_script() function. The function checks if the script is
 * registered before loading it.
 *
 * @access private
 * @return void
 * @since 1.2
 */
function momtaz_enqueue_core_scripts() {

	// Get the theme-supported scripts.
	$scripts = momtaz_get_supported_core_scripts();

	if ( ! empty( $scripts ) ) {

		foreach( $scripts as $script ) {

			if ( wp_script_is( $script, 'registered' ) ) {
				wp_enqueue_script( $script );
			}

		}

	}

	if ( is_singular() && get_option( 'thread_comments' ) && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( momtaz_is_style_debug() ) {
		wp_enqueue_script( 'less' );
	}

}

/**
 * Get the supported core scripts.
 *
 * Get an array of theme supported core scripts to be registered in WordPress via momtaz_register_core_scripts()
 * and then, loaded via momtaz_enqueue_core_scripts().
 *
 * @return array
 * @since 1.2
 */
function momtaz_get_supported_core_scripts() {

	$scripts = get_theme_support( 'momtaz-core-scripts' );

	if ( ! empty( $scripts ) ) {
		$scripts = reset( $scripts );
	}

	return (array) $scripts;

}
