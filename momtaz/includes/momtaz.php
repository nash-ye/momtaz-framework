<?php
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
	const VERSION = '1.3-alpha-4';


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
	private function constants() {

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
	private function check_reqs() {

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
	 * Load the Momtaz core files.
	 *
	 * @since 1.1
	 */
	private function load_core() {

		require self::path( 'includes/core/i18n.php'            );
		require self::path( 'includes/core/context.php'         );
		require self::path( 'includes/core/modules.php'         );
		require self::path( 'includes/core/settings.php'        );

	}

	/**
	 * Load the Momtaz framework functions.
	 *
	 * @return void
	 * @since 1.2
	 */
	private function load_framework() {

		// Helpers
		require self::path( 'includes/helpers/url.php'          );
		require self::path( 'includes/helpers/text.php'         );
		require self::path( 'includes/helpers/markup.php'       );
		require self::path( 'includes/helpers/general.php'      );
		require self::path( 'includes/helpers/template.php'     );
		require self::path( 'includes/helpers/layouts.php'      );
		require self::path( 'includes/helpers/sidebars.php'     );
		require self::path( 'includes/helpers/nav-menus.php'    );
		require self::path( 'includes/helpers/styles.php'       );
		require self::path( 'includes/helpers/scripts.php'      );
		require self::path( 'includes/helpers/media.php'        );
		require self::path( 'includes/helpers/entries.php'      );
		require self::path( 'includes/helpers/comments.php'     );

		if ( is_admin() ) {

			require self::path( 'includes/admin/admin.php' );

			if ( current_theme_supports( 'momtaz-core-theme-settings' ) ) {
				require self::path( 'includes/admin/settings.php' );
			}

		}

	}

	/**
	 * Load the framework's default filters.
	 *
	 * @return void
	 * @since 1.3
	 */
	private function default_filters() {

		// Remove the not needed WP tags.
		remove_action( 'wp_head', 'wp_generator'      );
		remove_action( 'wp_head', 'locale_stylesheet' );

		// Make shortcodes aware on some WP Filters.
		add_filter( 'widget_text', 'do_shortcode'      );
		add_filter( 'term_description', 'do_shortcode' );

		// Momtaz Init
		add_action( 'momtaz_init', 'momtaz_register_core_stacks'   );
		add_action( 'momtaz_init', 'momtaz_register_core_zones'    );
		add_action( 'momtaz_init', 'momtaz_register_core_layouts'  );
		add_action( 'momtaz_init', 'momtaz_adjust_current_layout'  );

		// Theme styles and scripts.
		add_action( 'wp_enqueue_scripts', 'momtaz_register_core_styles' );
		add_action( 'wp_enqueue_scripts', 'momtaz_enqueue_core_styles'  );
		add_action( 'wp_enqueue_scripts', 'momtaz_register_core_scripts' );
		add_action( 'wp_enqueue_scripts', 'momtaz_enqueue_core_scripts'  );

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

	/**
	 * Main Momtaz Instance
	 *
	 * @since 1.0
	 */
	public static function instance() {

		static $instance = NULL;

		if ( is_null( $instance ) ) {

			$instance = new Momtaz;

			do_action( 'before_momtaz_setup' );

			// Define the constants.
			$instance->constants();

			// Load the core functions.
			$instance->load_core();

			// Load the theme translations.
			$instance->load_l10n();

			// Check the Momtaz requirements.
			$instance->check_reqs();

			// Load the framework functions.
			$instance->load_framework();

			// Load the default filters.
			$instance->default_filters();

			do_action( 'after_momtaz_setup' );

			// Load the auto-load modules.
			$instance->load_modules();

			do_action( 'momtaz_init' );

		}

		return $instance;

	}

	/**
	 * Get the absolute system path to the Momtaz directory, or a file therein.
	 *
	 * The function gets the absolute system path to the Momtaz directory with a trailing slash.
	 * Also, gets the path to any file inside, by passing it to the function relative to the momtaz directory.
	 *
	 * @param string $path The path relative to the Momtaz directory.
	 * @uses get_template_directory() Get system path to theme template directory.
	 * @return string
	 * @since 1.2
	 */
	public static function path( $path = '' ) {

		$path = ltrim( $path, '/' );
		$dir = get_template_directory();
		return trailingslashit( $dir ) . $path;

	}

}

/**
 * The main function responsible for returning the one true Momtaz Instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $momtaz = momtaz() ?>
 *
 * @return The one true Momtaz Instance
 * @since 1.0
 */
function momtaz() {
	return Momtaz::instance();
}

// Fire it up!
momtaz();
