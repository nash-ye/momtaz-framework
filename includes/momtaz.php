<?php

do_action( 'before_momtaz_load' );

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
    const VERSION = '1.1';


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
    } // end __clone()

    /**
     * A dummy magic method to prevent Momtaz from being unserialized
     *
     * @since 1.0
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'momtaz' ), '1.0' );
    } // end __wakeup()


    /** Private Methods *******************************************************/

    /**
     * Define the default constants.
     *
     * @since 1.0
     */
    private function define_constants() {

        if ( ! defined( 'THEME_PREFIX' ) )
             define( 'THEME_PREFIX', 'momtaz' );

        if ( ! defined( 'THEME_TEXTDOMAIN' ) )
             define( 'THEME_TEXTDOMAIN', 'momtaz' );

        define( 'THEME_DIR', get_template_directory() );
        define( 'THEME_URI', get_template_directory_uri() );

        define( 'CHILD_THEME_DIR', get_stylesheet_directory() );
        define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );

        define( 'MOMTAZ_ADMIN_DIR', trailingslashit( THEME_DIR ) . 'admin' );
        define( 'MOMTAZ_ADMIN_URI', trailingslashit( THEME_URI ) . 'admin' );

        define( 'MOMTAZ_CONTENT_DIR', trailingslashit( THEME_DIR ) . 'content' );
        define( 'MOMTAZ_CONTENT_URI', trailingslashit( THEME_URI ) . 'content' );

        define( 'MOMTAZ_INCLUDES_DIR', trailingslashit( THEME_DIR ) . 'includes' );
        define( 'MOMTAZ_INCLUDES_URI', trailingslashit( THEME_URI ) . 'includes' );

        if ( ! defined( 'MOMTAZ_CACHE_DIR' ) )
            define( 'MOMTAZ_CACHE_DIR', trailingslashit( THEME_DIR ) . 'cache' );

        if ( ! defined( 'MOMTAZ_CACHE_URI' ) )
            define( 'MOMTAZ_CACHE_URI', trailingslashit( THEME_URI ) . 'cache' );

    } // end define_constents()

    /**
     * Load the theme's translated strings.
     *
     * @since 1.0
     */
    private function load_l10n() {

        // Load theme textDomain.
        load_theme_textdomain( THEME_TEXTDOMAIN );

        // Load child theme textDomain.
        if ( is_child_theme() && defined( 'CHILD_THEME_TEXTDOMAIN' ) )
             load_child_theme_textdomain( CHILD_THEME_TEXTDOMAIN );

    } // end load_l10n()

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
                    '<b>The default theme will be activated automaticly.</b>', THEME_TEXTDOMAIN ) );

        } // end if

    } // end check_requirements()

    /**
     * Load the Momtaz kernel files.
     *
     * @since 1.1
     */
    private function load_kernel() {

        require MOMTAZ_INCLUDES_DIR . '/kernel/core.php';
        require MOMTAZ_INCLUDES_DIR . '/kernel/modules.php';

    } // end load_kernel()

    /**
     * Load the Momtaz common functions.
     *
     * @since 1.1
     */
    private function load_common() {

        require MOMTAZ_INCLUDES_DIR . '/common/context.php';
        require MOMTAZ_INCLUDES_DIR . '/common/settings.php';
        require MOMTAZ_INCLUDES_DIR . '/common/formatting.php';
        require MOMTAZ_INCLUDES_DIR . '/common/comments.php';
        require MOMTAZ_INCLUDES_DIR . '/common/general.php';
        require MOMTAZ_INCLUDES_DIR . '/common/templates.php';
        require MOMTAZ_INCLUDES_DIR . '/common/scripts.php';
        require MOMTAZ_INCLUDES_DIR . '/common/styles.php';
        require MOMTAZ_INCLUDES_DIR . '/common/media.php';

        // Sets up the default filters and actions.
        require MOMTAZ_INCLUDES_DIR . '/common/filters.php';

    } // end load_common()

    /**
     * Load the supported features.
     *
     * @since 1.0
     */
    private function load_features() {

        require_if_theme_supports( 'momtaz-core-sidebars', MOMTAZ_INCLUDES_DIR . '/features/sidebars.php' );
        require_if_theme_supports( 'momtaz-core-menus', MOMTAZ_INCLUDES_DIR . '/features/menus.php' );

    } // end load_features()

    /**
     * Load the admin functions.
     *
     * @since 1.0
     */
    private function load_admin() {

        if ( is_admin() ) {

            require MOMTAZ_ADMIN_DIR . '/admin.php';

            require_if_theme_supports( 'momtaz-core-theme-settings', MOMTAZ_ADMIN_DIR . '/settings.php' );

        } // end if

    } // end load_admin()

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

    } // end load_modules()


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

            // Load the common functions.
            self::$instance->load_common();

            // Load the supported features.
            self::$instance->load_features();

            // Load the admin functions.
            self::$instance->load_admin();

            do_action( 'after_momtaz_setup' );

            // Load the auto-load modules.
            self::$instance->load_modules();

        } // end if

        return self::$instance;

    } // end instance()

} // end Class Momtaz

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
} // end momtaz()

// Fire it up!
momtaz();