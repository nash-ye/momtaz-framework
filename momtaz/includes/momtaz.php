<?php

if ( ! class_exists( 'Momtaz' ) ) :

/**
 * Momtaz main class.
 *
 * @since 1.0
 */
final class Momtaz {

	/**
	 * Momtaz version.
	 *
	 * @var float
	 * @since 1.0
	 */
	const VERSION = '1.2-alpha-2';


	/** Magic Methods *********************************************************/

	/**
	 * A dummy constructor to prevent Momtaz from being loaded more than once.
	 *
	 * @since 1.0
	 */
	private function __construct() {}

	/**
	 * A dummy magic method to prevent Momtaz from being cloned
	 *
	 * @since 1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'momtaz' ), '1.0' );
	}

	/**
	 * A dummy magic method to prevent Momtaz from being unserialized
	 *
	 * @since 1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'momtaz' ), '1.0' );
	}


	/** Private Methods *******************************************************/

	/**
	 * Define the default constants.
	 *
	 * @since 1.0
	 */
	private function define_constants() {

		if ( ! defined( 'THEME_PREFIX' ) ) {
			 define( 'THEME_PREFIX', 'momtaz' );
		}

		define( 'THEME_DIR', get_template_directory() );
		define( 'THEME_URI', get_template_directory_uri() );

		define( 'CHILD_THEME_DIR', get_stylesheet_directory() );
		define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );

		if ( ! defined( 'MOMTAZ_CACHE_DIR' ) ) {
			define( 'MOMTAZ_CACHE_DIR', trailingslashit( THEME_DIR ) . 'cache' );
		}

		if ( ! defined( 'MOMTAZ_CACHE_URI' ) ) {
			define( 'MOMTAZ_CACHE_URI', trailingslashit( THEME_URI ) . 'cache' );
		}

	}

	/**
	 * Load the theme's translated strings.
	 *
	 * @since 1.0
	 */
	private function load_l10n() {

		// Load parent theme translation files.
		momtaz_load_parent_theme_textdomain();

		if ( is_child_theme() ) {

			// Load child theme translation files.
			momtaz_load_child_theme_textdomain();

		}

	}

	/**
	 * Check Momtaz requirements.
	 *
	 * @since 1.0
	 */
	private function check_requirements() {

		global $wp_version;

		if ( version_compare( PHP_VERSION, '5.3', '<' ) ||
			 version_compare( $wp_version, '3.5', '<' ) ) {

			// Switch to the default theme.
			switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );

			// Tell the user about the problem.
			wp_die( __( 'The current environment doesn\'t meet the Momtaz requirements.<br>'.
					'<b>The default theme will be activated automaticly.</b>', 'momtaz' ) );

		}

	}

	/**
	 * Load the Momtaz kernel files.
	 *
	 * @since 1.1
	 */
	private function load_kernel() {

		require self::path( 'includes/core.php' );
		require self::path( 'includes/modules.php' );

	}

	/**
	 * Load the Momtaz framework functions.
	 *
	 * @return void
	 * @since 1.2
	 */
	private function load_framework() {

		require self::path( 'includes/context.php'		);
		require self::path( 'includes/settings.php'		);
		require self::path( 'includes/formatting.php'	);
		require self::path( 'includes/comments.php'		);
		require self::path( 'includes/general.php'		);
		require self::path( 'includes/templates.php'	);
		require self::path( 'includes/layouts.php'		);
		require self::path( 'includes/sidebars.php'		);
		require self::path( 'includes/scripts.php'		);
		require self::path( 'includes/styles.php'		);
		require self::path( 'includes/menus.php'		);
		require self::path( 'includes/media.php'		);

		if ( is_admin() ) {

			require self::path( 'admin/admin.php'		);
			require self::path( 'admin/settings.php'	);

		}

		// Sets up the default filters and actions.
		require self::path( 'includes/filters.php'		);

	}

	/**
	 * Load the auto-load modules.
	 *
	 * @since: 1.1
	 */
	private function load_modules() {

		// Get the auto-load modules.
		$autoload_modules = Momtaz_Modules::get( array(
			'settings' => array(
				'auto' => true,
			)
		) );

		Momtaz_Modules::load_modules( $autoload_modules );

	}


	/** Singleton *************************************************************/

	private static $instance;

	/**
	 * Main Momtaz Instance
	 *
	 * @since 1.0
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) ) {

			self::$instance = new Momtaz;

			do_action( 'before_momtaz_setup' );

			// Define the constants.
			self::$instance->define_constants();

			// Load the kernel functions.
			self::$instance->load_kernel();

			// Load the theme translations.
			self::$instance->load_l10n();

			// Check the Momtaz requirements.
			self::$instance->check_requirements();

			// Load the framework functions.
			self::$instance->load_framework();

			do_action( 'after_momtaz_setup' );

			// Load the auto-load modules.
			self::$instance->load_modules();

			do_action( 'momtaz_init' );

		}

		return self::$instance;

	}

	/**
	 * Get the absolute system path to the Momtaz directory, or a file therein.
	 *
	 * @return string
	 * @since 1.2
	 */
	public static function path( $path = '' ) {

		$path = ltrim( $path, '/' );
		$dir = get_template_directory();
		return trailingslashit( $dir ) . $path;

	}

}

endif; // end class_exists() check

/**
 * The main function responsible for returning the one true Momtaz Instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $momtaz = momtaz(); ?>
 *
 * @since 1.0
 * @return The one true Momtaz Instance
 */
function momtaz() {
	return Momtaz::instance();
}

// Fire it up!
momtaz();
