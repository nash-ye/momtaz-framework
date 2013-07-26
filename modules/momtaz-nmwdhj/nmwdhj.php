<?php
/**
 * Plugin Name: Momtaz Nmwdhj
 * Plugin URI: http://nashwan-d.com
 * Description: An API for creating forms elements via code.
 * Author: Nashwan Doaqan
 * Author URI: http://nashwan-d.com
 * Version: 1.1
 *
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Copyright (c) 2013 - 2014 Nashwan Doaqan.  All rights reserved.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Momtaz_Nmwdhj' ) ) :

/**
 * The Momtaz Nmwdhj plugin main class.
 *
 * @since 1.0
 */
final class Momtaz_Nmwdhj {

    /**
     * Plugin version.
     *
     * @var float
     * @since 1.0
     */
    const VERSION = '1.1';

    // Setup

    /**
     * Initialize the Momtaz Nmwdhj plugin.
     *
     * @since 1.0
     * @return void
     */
    public static function init() {

        // Register the default views.
        Momtaz_Nmwdhj_Views::register_defaults();

        // Register the default elements.
        Momtaz_Nmwdhj_Elements::register_defaults();

        // Register the default decorators.
        Momtaz_Nmwdhj_Decorators::register_defaults();

        do_action( 'momtaz_nmwdhj_init' );

    } // end init()

    // Elements

    /**
     * Create an element object.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Element
     */
    public static function create_element( $key, array $properties = null ) {

        if ( ( $element = Momtaz_Nmwdhj_Elements::get_by_key( $key ) ) ) {

            if ( Momtaz_Nmwdhj_Elements::check_class( $element['class_name'] ) ) {

                return new $element['class_name']( $key, $properties );

            } // end if

        } // end if

    } // end create_element()

    /**
     * Create many elements objects at once.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Element[]
     */
    public static function create_elements( array $elements ) {

        $objects = array();

        foreach( $elements as $key => $element ) {

            if ( empty( $element['key'] ) )
                continue;

            $objects[$key] = self::create_element( $element['key'], $element );

        } // end foreach

        return $objects;

    } // end create_elements()

    // Decorators

    /**
     * Decorate an element.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Decorator
     */
    public static function decorate_element( $decorator_key, Momtaz_Nmwdhj_Element $element ) {

        // Get the decorator.
        if ( ( $decorator = Momtaz_Nmwdhj_Decorators::get_by_key( $decorator_key ) ) ) {

            // Check the decorator class.
            if ( Momtaz_Nmwdhj_Decorators::check_class( $decorator['class_name'] ) ) {

                return new $decorator['class_name']( $element );

            } // end if

        } // end if

    } // end decorate_element()

    // Views

    /**
     * View an element.
     *
     * @since 1.0
     * @return string
     */
    public static function view_element( Momtaz_Nmwdhj_Element $element, $view_key = false ) {

        if ( empty( $view_key ) )
            $view_key = $element->get_view_key();

        // Get the view.
        if ( ( $view = Momtaz_Nmwdhj_Views::get_by_key( $view_key ) ) ) {

            // Check the view class.
            if ( Momtaz_Nmwdhj_Views::check_class( $view['class_name'] ) ) {

                return call_user_func( new $view['class_name'], $element );

            } // end if

        } // end if

    } // end view_element()

    // Paths

    /**
     * Get the absolute system path to the plugin directory, or a file therein.
     *
     * @param string $path
     * @return string
     */
    public static function get_path( $path = '' ) {

        $path = strval( $path );
        $base = dirname( __FILE__ );

        if ( empty( $path ) )
            return untrailingslashit( $base );

        return path_join( $base, $path );

    } // end get_path()

} // end Class Momtaz_Nmwdhj

/**
 * A helper function to load the Momtaz Nmwdhj classes.
 *
 * @since 1.0
 * @return boolean
 */
function momtaz_nmwdhj_class_loader( $class_name ) {

    // The core classes.
    $core_classes = array(

        // Views
        'Momtaz_Nmwdhj_View' => 'core/views.php',
        'Momtaz_Nmwdhj_Views' => 'core/views.php',

        // Elements
        'Momtaz_Nmwdhj_Element' => 'core/elements.php',
        'Momtaz_Nmwdhj_Elements' => 'core/elements.php',

        // Decorators
        'Momtaz_Nmwdhj_Decorator' => 'core/decorators.php',
        'Momtaz_Nmwdhj_Decorators' => 'core/decorators.php',

        // Attributes
        'Momtaz_Nmwdhj_Attribute' => 'core/attributes.php',
        'Momtaz_Nmwdhj_Attributes' => 'core/attributes.php',
        'Momtaz_Nmwdhj_ClassAttribute' => 'core/attributes.php',
        'Momtaz_Nmwdhj_SimpleAttribute' => 'core/attributes.php',

    );

    // Check if the class exists in $core_classes .
    if ( isset( $core_classes[ $class_name ] ) ) {

        require_once Momtaz_Nmwdhj::get_path( $core_classes[ $class_name ] );

        return true;

    } else {

        // Load a Decorator class.
        if ( Momtaz_Nmwdhj_Decorators::load_class( $class_name ) )
            return true;

        // Load an Element class.
        if ( Momtaz_Nmwdhj_Elements::load_class( $class_name ) )
            return true;

        // Load a View class.
        if ( Momtaz_Nmwdhj_Views::load_class( $class_name ) )
            return true;

    } // end if

    // :(
    return false;

} // end momtaz_nmwdhj_class_loader()

// Register the autoload function for Momtaz Nmwdhj classes.
spl_autoload_register( 'momtaz_nmwdhj_class_loader' );

/**
 * Hook Momtaz Nmwdhj early onto the 'plugins_loaded' action.
 *
 * This gives all other plugins the chance to load before Momtaz Nmwdhj, to get their
 * actions, filters, and overrides setup without Momtaz Nmwdhj being in the way.
 */
if ( defined( 'MOMTAZ_NMWDHJ_LATE_LOAD' ) ) {
    add_action( 'plugins_loaded', array( 'Momtaz_Nmwdhj', 'init' ), (int) MOMTAZ_NMWDHJ_LATE_LOAD );

// "And now here's something we hope you'll really like!"
} else {
    Momtaz_Nmwdhj::init();

} // end if

endif; // class_exists check