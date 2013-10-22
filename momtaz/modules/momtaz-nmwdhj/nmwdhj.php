<?php
/**
 * Plugin Name: Nmwdhj
 * Plugin URI: http://nashwan-d.com
 * Description: An API for creating forms elements via code.
 * Author: Nashwan Doaqan
 * Author URI: http://nashwan-d.com
 * Version: 1.2.1
 *
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Copyright (c) 2013 - 2014 Nashwan Doaqan.  All rights reserved.
 */

namespace Nmwdhj;

//*** Loaders *****************************************************************/

/**
 * A helper function to load the Nmwdhj classes.
 *
 * @return void
 * @since 1.2
 */
function class_loader( $class_name ) {

	switch( $class_name ) {

		/*** Nmwdhj Core ******************************************************/

		case 'Nmwdhj\Exception':
			require_once get_path( 'core/exceptions.php' );
			break;

		case 'Nmwdhj\Attributes\Attribute':
		case 'Nmwdhj\Attributes\Attributes':
		case 'Nmwdhj\Attributes\ClassAttribute':
		case 'Nmwdhj\Attributes\SimpleAttribute':
			require_once get_path( 'core/attributes.php' );
			break;


		/*** Nmwdhj Elements **************************************************/

		case 'Nmwdhj\Elements\Base':
			require_once get_path( 'elements/Base.php' );
			break;

		case 'Nmwdhj\Elements\Manager':
			require_once get_path( 'elements/Manager.php' );
			break;

		case 'Nmwdhj\Elements\Element':
			require_once get_path( 'elements/Element.php' );
			break;


		/*** Nmwdhj Views *****************************************************/

		case 'Nmwdhj\Views\View':
			require_once get_path( 'view-helpers/View.php' );
			break;

		case 'Nmwdhj\Views\Manager':
			require_once get_path( 'view-helpers/Manager.php' );
			break;


		/*** Nmwdhj Decorators ************************************************/

		case 'Nmwdhj\Decorators\Manager':
			require_once get_path( 'view-helpers/decorators/Manager.php' );
			break;

		case 'Nmwdhj\Decorators\Decorator':
			require_once get_path( 'view-helpers/decorators/Decorator.php' );
			break;

	} // end switch

} // end class_loader()

// Register the autoload function.
spl_autoload_register( 'Nmwdhj\class_loader' );


//*** Functions ***************************************************************/

/**
 * Create an element object.
 *
 * @return Nmwdhj\Elements\Element
 * @throws Nmwdhj\Exception
 * @since 1.2
 */
function create_element( $key, array $properties = null ) {

	if ( ! ( $element = Elements\Manager::get_by_key( $key ) ) )
		throw new Exception( 'invalid_element' );

	if ( ! Elements\Manager::check_class( $element->class_name ) )
		throw new Exception( 'invalid_element_class' );

	return new $element->class_name( $key, $properties );

} // end create_element()

/**
 * Create many elements objects at once.
 *
 * @return Nmwdhj\Elements\Element[]
 * @throws Nmwdhj\Exception
 * @since 1.2
 */
function create_elements( array $elements ) {

	$objects = array();

	foreach( $elements as $key => $element ) {

		if ( empty( $element['key'] ) )
			continue;

		$objects[ $key ] = create_element( $element['key'], $element );

	} // end foreach

	return $objects;

} // end create_elements()

/**
 * Create an attributes object.
 *
 * @return Nmwdhj\Attributes\Attributes
 * @since 1.0
 */
function create_atts_obj( $atts ) {

	if ( $atts instanceof Attributes\Attributes )
		return $atts;

	return new Attributes\Attributes( $atts );

} // end create_atts_obj()

/**
 * Create an attribute object.
 *
 * @return Nmwdhj\Attributes\Attribute
 * @since 1.1
 */
function create_attr_obj( $key, $value ) {

	if ( $value instanceof Attributes\Attribute ) {

		if ( strcasecmp( $value->get_key(), $key ) !== 0 )
			$obj = create_attr_obj( $key, $value->get_value() );

	} else {

		switch( strtolower( $key ) ) {

			case 'class':
				$obj = new Attributes\ClassAttribute( $key, $value );
				break;

			default:
				$obj = new Attributes\SimpleAttribute( $key, $value );
				break;

		} // end Switch

	} // end if

	return $obj;

} // end create_attr_obj()

/**
 * Decorate an element.
 *
 * @return Nmwdhj\Decorators\Decorator
 * @throws Nmwdhj\Exception
 * @since 1.2
 */
function decorate_element( $key, Elements\Element &$element ) {

	if ( ! ( $decorator = Decorators\Manager::get_by_key( $key ) ) )
		throw new Exception( 'invalid_decorator' );

	if ( ! Decorators\Manager::check_class( $decorator->class_name ) )
		throw new Exception( 'invalid_decorator_class' );

	$element = new $decorator->class_name( $element );
	return $element;

} // end decorate_element()

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

	if ( ! empty( $path ) )
		$path = path_join( $base, $path );
	else
		$path = untrailingslashit( $base );

	return $path;

} // end get_path()


//*** Initialize **************************************************************/

// Register the default decorators.
Decorators\Manager::register_defaults();

// Register the default elements.
Elements\Manager::register_defaults();

// Register the default views.
Views\Manager::register_defaults();

do_action( 'nmwdhj_init' );