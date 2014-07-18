<?php
/**
 * Functions file for loading theme scripts.
 *
 * @package Momtaz
 * @subpackage Functions
 */

/**
 * Registers JavaScript files for the framework.
 *
 * This function merely registers theme supported scripts with WordPress using the wp_register_script() function.
 * It does not load any script files on the site. If a theme wants to register its own custom scripts, it should
 * do so on the 'wp_enqueue_scripts' hook.
 *
 * @uses momtaz_get_supported_core_scripts() Get the supported core scripts.
 * @uses wp_register_script() Register a script in WordPress.
 * @access private
 * @return void
 * @since 1.2
 */
function momtaz_register_core_scripts() {

	if ( momtaz_is_style_debug() ) {

		wp_register_script(
			'less',
			momtaz_theme_uri( 'content/scripts/less.js' ),
			array(),
			Momtaz::VERSION
		);

	}

	$scripts = momtaz_get_supported_core_scripts();

	if ( empty( $scripts ) ) {
		return;
	}

	$core_scripts = array(
		'superfish' => array(
			'src'			=> momtaz_theme_uri( 'content/scripts/superfish.js' ),
			'deps'			=> array( 'jquery' ),
			'ver'			=> Momtaz::VERSION,
		),
		'superfish.args' => array(
			'src'			=> momtaz_theme_uri( 'content/scripts/superfish.args.js' ),
			'deps'			=> array( 'jquery', 'superfish' ),
			'ver'			=> Momtaz::VERSION,
		),
	);

	$core_scripts = apply_filters( 'momtaz_core_scripts', $core_scripts );

	// Loop through the core scripts.
	foreach( $core_scripts as $key => $args ) {

		// Make sure the given script is one of the supported-scripts.
		if ( in_array( $key, $scripts ) ) {

			$args = array_merge( array(
				'handle'	=> $key,
				'src'		=> false,
				'deps'		=> array(),
				'ver'		=> false,
				'in_footer' => true,
			), $args );

			// Register the script.
			wp_register_script(
				$args['handle'],
				$args['src'],
				$args['deps'],
				$args['ver'],
				$args['in_footer']
			);

		}

	}

}

/**
 * Load the registered theme supported scripts.
 *
 * Get the theme supported core scripts and scripts needed for the framework and tell WordPress to
 * load them using the wp_enqueue_script() function. The function checks if the script is
 * registered before loading it.
 *
 * @uses momtaz_get_supported_core_scripts() Get the supported core scripts.
 * @uses wp_enqueue_script() Load a script in WordPress.
 * @access private
 * @return void
 * @since 1.2
 */
function momtaz_enqueue_core_scripts() {

	if ( momtaz_is_style_debug() ) {
		wp_enqueue_script( 'less' );
	}

	// Get the theme-supported scripts.
	$scripts = momtaz_get_supported_core_scripts();

	if ( $scripts ) {

		foreach( $scripts as $script ) {

			if ( wp_script_is( $script, 'registered' ) ) {
				wp_enqueue_script( $script );
			}

		}

	}

	if ( is_singular() && get_option( 'thread_comments' ) && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}

}

/**
 * Get the supported core scripts.
 *
 * Get an array of theme supported core scripts to be registered in WordPress via momtaz_register_core_scripts()
 * and then, loaded via momtaz_enqueue_core_scripts().
 *
 * @uses get_theme_support() Get support of a certain theme feature.
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
