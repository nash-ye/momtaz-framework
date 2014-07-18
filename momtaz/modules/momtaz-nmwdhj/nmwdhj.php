<?php
/**
 * Plugin Name: Nmwdhj
 * Plugin URI: http://wordpress.org/plugins/momtaz-nmwdhj/
 * Description: An API for creating forms elements via code.
 * Author: Nashwan Doaqan
 * Author URI: http://nashwan-d.com
 * Version: 1.3
 *
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Copyright (c) 2013 - 2014 Nashwan Doaqan.  All rights reserved.
 */

namespace Nmwdhj;

// Nmwdhj Version.
const VERSION = '1.3';

/**** Loaders *****************************************************************/

/**
 * A helper function to load the Nmwdhj classes.
 *
 * @return void
 * @since 1.2
 */
function class_loader( $class_name ) {

	$nps = explode( '\\', $class_name, 3 );

	if ( 'Nmwdhj' !== $nps[0] || count( $nps ) === 1 ) {
		return;
	}

	switch( $nps[1] ) {

		case 'Manager':
		case 'Exception':
		case 'EventManager':
		case 'PriorityArray':
			$class_path = get_path( 'core/Essentials.php' );
			break;

		case 'Attributes':
			$class_path = get_path( 'core/Attributes.php' );
			break;

		case 'Elements':
			if ( ! empty( $nps[2] ) ) {
				$class_path = get_path( "elements/{$nps[2]}.php" );
			}
			break;

		case 'Views':
			if ( ! empty( $nps[2] ) ) {
				$class_path = get_path( "elements/views/{$nps[2]}.php" );
			}
			break;

	}

	if ( ! empty( $class_path ) && file_exists( $class_path ) ) {
		require $class_path;
	}

}

// Register the autoload function.
spl_autoload_register( 'Nmwdhj\class_loader' );


/**** Functions ***************************************************************/

/**
 * Create an element object.
 *
 * @return Nmwdhj\Elements\Element
 * @throws Nmwdhj\Exception
 * @since 1.2
 */
function create_element( $args ) {

	if ( is_string( $args ) ) {
		$args = array( 'type' => $args );
	}

	if ( empty( $args['type'] ) ) {
		throw new Exception( 'Invalid element type' );
	}

	$element = Manager::get_element( $args['type'] );

	if ( empty( $element ) ) {
		throw new Exception( 'Invalid element type' );
	}

	return new $element->name( $args );

}

/**
 * Create many elements objects at once.
 *
 * @return Nmwdhj\Elements\Element[]
 * @throws Nmwdhj\Exception
 * @since 1.2
 */
function create_elements( array $elements ) {

	$objects = array();

	foreach( $elements as $key => $value ) {
		$objects[ $key ] = create_element( $value );
	}

	return $objects;

}

/**
 * Create an attributes object.
 *
 * @return Nmwdhj\Attributes\Attributes
 * @since 1.0
 */
function create_atts_obj( $atts ) {

	if ( $atts instanceof Attributes\Attributes ) {
		return $atts;
	}

	$atts = new Attributes\Attributes( $atts );
	return $atts;

}

/**
 * Create an attribute object.
 *
 * @return Nmwdhj\Attributes\Attribute
 * @since 1.1
 */
function create_attr_obj( $key, $value ) {

	if ( $value instanceof Attributes\Attribute ) {

		if ( strcasecmp( $value->get_key(), $key ) === 0 ) {
			return $value;
		}

		return create_attr_obj( $key, $value->get_value() );

	} else {

		switch( strtolower( $key ) ) {

			case 'class':
				return new Attributes\ClassAttribute( $key, $value );

			default:
				return new Attributes\SimpleAttribute( $key, $value );

		}

	}

}

// Paths

/**
 * Get the absolute system path to the plugin directory, or a file therein.
 *
 * @param string $path
 * @return string
 * @since 1.2
 */
function get_path( $path = '' ) {

	$base = dirname( __FILE__ );

	if ( ! empty( $path ) ) {
		$path = path_join( $base, $path );
	} else {
		$path = untrailingslashit( $base );
	}

	return $path;

}


/**** Initialize **************************************************************/

// Register the default settings.
Manager::register_defaults();

do_action( 'nmwdhj_init' );