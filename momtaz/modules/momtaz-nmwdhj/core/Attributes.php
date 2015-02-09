<?php
namespace Nmwdhj\Attributes;

use Nmwdhj\EventManager;

/**
 * The attributes class.
 *
 * @since 1.0
 */
class Attributes {

	/*** Properties ***********************************************************/

	/**
	 * Attributes Event Manager.
	 *
	 * @var Nmwdhj\EventManager
	 * @since 1.3
	 */
	protected $dispatcher;

	/**
	 * Attributes list.
	 *
	 * @var Nmwdhj\Attributes\Attribute[]
	 * @since 1.0
	 */
	protected $atts = array();


	/*** Magic Methods ********************************************************/

	/**
	 * The Attributes class constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( $atts = NULL ) {

		// Set the attributes.
		$this->set_atts( $atts );

	}


	/*** Methods **************************************************************/

	// Getters

	/**
	 * Get all the attributes array.
	 *
	 * @return Nmwdhj\Attributes\Attribute[]
	 * @since 1.0
	 */
	public function get_atts() {
		return $this->atts;
	}

	/**
	 * Get an attribute object.
	 *
	 * @return Nmwdhj\Attributes\Attribute|NULL
	 * @since 1.1
	 */
	public function get_attr_obj( $key ) {

		$key = strtolower( $key );

		if ( isset( $this->atts[ $key ] ) ) {
			return $this->atts[ $key ];
		}

	}

	/**
	 * Get an attribute value.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function get_attr( $key, $def = '' ) {

		$obj = $this->get_attr_obj( $key );

		if ( ! $obj && is_scalar( $def ) ) {
			return $def;
		}

		return $obj->get_value();

	}

	// Checks

	/**
	 * Check for an attribute existence.
	 *
	 * @return bool
	 * @since 1.0
	 */
	public function has_attr( $key ) {

		if ( is_array( $key ) ) {

			foreach( $key as $value ) {

				if ( ! $this->has_attr( $value ) ) {
					return false;
				}

			}

		} else {

			if ( $key instanceof Attribute ) {
				$key = $key->get_key();
			}

			if ( ! $this->get_attr_obj( $key ) ) {
				return false;
			}

		}

		return true;

	}

	// Setters

	/**
	 * Set many attributes at once.
	 *
	 * @return Nmwdhj\Attributes\Attributes
	 * @since 1.0
	 */
	public function set_atts( $atts, $override = true ) {

		if ( $atts instanceof Attributes ) {
			$atts = $atts->get_atts();
		}

		if ( is_array( $atts ) ) {

			foreach( $atts as $key => $value ) {
				$this->set_attr( $key, $value, $override );
			}

			$this->get_dispatcher()->trigger( 'set_atts', $atts, $override );

		}

		return $this;

	}

	/**
	 * Set an attribute value.
	 *
	 * @return Nmwdhj\Attributes\Attributes
	 * @since 1.0
	 */
	public function set_attr( $key, $value, $override = true ) {

		$key = strtolower( $key );

		if ( $override || ! $this->has_attr( $key ) ) {

			$this->atts[ $key ] = \Nmwdhj\create_attr_obj( $key, $value );

			$this->get_dispatcher()->trigger( 'set_attr', $key, $value, $override );

		}

		return $this;

	}

	// Remove

	/**
	 * Remove many attributes at once.
	 *
	 * @return Nmwdhj\Attributes\Attributes
	 * @since 1.0
	 */
	public function remove_atts( $keys ) {

		if ( $keys instanceof Attributes ) {
			$keys = array_keys( $keys->get_atts() );
		}

		if ( is_array( $keys ) ) {

			foreach( $keys as $key ) {
				$this->remove_attr( $key );
			}

			$this->get_dispatcher()->trigger( 'remove_atts', $keys );

		}

		return $this;

	}

	/**
	 * Remove an attribute.
	 *
	 * @return Nmwdhj\Attributes\Attributes
	 * @since 1.0
	 */
	public function remove_attr( $key ) {

		$key = strtolower( $key );
		unset( $this->atts[ $key ] );

		$this->get_dispatcher()->trigger( 'remove_attr', $key );

		return $this;

	}

	// Event Manager

	/**
	 * Set the Dispatcher (EventManager).
	 *
	 * @return Nmwdhj\Attributes\Attributes
	 * @since 1.3
	 */
	public function set_dispatcher( EventManager $dispatcher ) {
		$this->dispatcher = $dispatcher;
		return $this;
	}

	/**
	 * Get the Dispatcher (EventManager).
	 *
	 * @return Nmwdhj\EventManager
	 * @since 1.3
	 */
	public function get_dispatcher() {

		if ( is_null( $this->dispatcher ) ) {
			$this->dispatcher = new EventManager();
		}

		return $this->dispatcher;

	}

	// Converters

	/**
	 * Convert the attributes array to string.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function to_string( array $args = NULL ) {

		$output = '';
		$atts = $this->get_atts();

		if ( count( $atts ) === 0 ) {
			return $output;
		}

		$args = array_merge( array(
			'before' => ' ',
			'after'  => '',
		), (array) $args );

		$atts = array_map( 'strval', $atts );
		$output = trim( implode( ' ', $atts ) );

		if ( empty( $output ) ) {
			return $output;
		}

		return $args['before'] . $output . $args['after'];

	}

	/**
	 * Convert the attributes array to string.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function __toString() {
		return $this->to_string();
	}

}


/**
 * The attribute interface.
 *
 * @since 1.1
 */
interface Attribute {

	/**
	 * Get the attribute key.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function get_key();

	/**
	 * Get the attribute value.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function get_value();

	/**
	 * Get the attribute output.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function __toString();

}

/**
 * The simple attribute class.
 *
 * @since 1.1
 */
class SimpleAttribute implements Attribute {

	/*** Properties ***********************************************************/

	/**
	 * The attribute key.
	 *
	 * @var string
	 * @since 1.1
	 */
	protected $key;

	/**
	 * The attribute value.
	 *
	 * @var mixed
	 * @since 1.1
	 */
	protected $value;


	/*** Magic Methods ********************************************************/

	/**
	 * The Attribute class constructor.
	 *
	 * @since 1.1
	 */
	public function __construct( $key, $value ) {

		// Set the attribute key.
		$this->set_key( $key );

		// Set the attribute value.
		$this->set_value( $value );

	}


	/*** Methods **************************************************************/

	// Getters

	/**
	 * Get the attribute key.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * Get the attribute value.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function get_value() {
		return $this->value;
	}

	// Setters

	/**
	 * Set the attribute key.
	 *
	 * @return Nmwdhj\Attributes\SimpleAttribute
	 * @since 1.1
	 */
	protected function set_key( $key ) {
		$this->key = $key;
		return $this;
	}

	/**
	 * Set the attribute value.
	 *
	 * @return Nmwdhj\Attributes\SimpleAttribute
	 * @since 1.1
	 */
	protected function set_value( $value ) {
		$this->value = $value;
		return $this;
	}

	/**
	 * Get the attribute output.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function __toString(){

		$output = '';

		// Get the attribute key.
		$key = $this->get_key();

		// Get the attribute value.
		$value = $this->get_value();

		if ( ! empty( $key ) && $value !== false ) {

			if ( $value === true ) {
				$value = $key;
			}

			$output = $key . '="' . esc_attr( $value ) . '"';

		}

		return $output;

	}

}

/**
 * The CSS classes attribute.
 *
 * @since 1.1
 */
class ClassAttribute extends SimpleAttribute {

	// Getters

	/**
	 * Get the classes list.
	 *
	 * @return string|array
	 * @since 1.1
	 */
	public function get_value( $type = 'string' ) {

		switch( strtolower( $type ) ) {

			case 'array':

				// Convert the classes list to an array.
				$this->value = $this->explode_classes( $this->value );

				break;

			default:
			case 'string':

				// Convert the classes list to a string.
				$this->value = $this->implode_classes( $this->value );

				break;

		}

		return $this->value;

	}

	// Checks

	/**
	 * [Need Description]
	 *
	 * @return bool
	 * @since 1.1
	 */
	public function has_classes( $classes ) {

		$classes = $this->explode_classes( $classes );

		if ( $classes ) {

			if ( in_array( $classes, $this->get_value( 'array' ) ) ) {
				return true;
			}

		}

		return false;

	}

	// Setters

	/**
	 * Adds many classes at once.
	 *
	 * @return Nmwdhj\Attributes\ClassAttribute
	 * @since 1.1
	 */
	public function add_classes( $classes ) {

		$classes = $this->explode_classes( $classes );

		if ( $classes ) {

			$classes = array_merge( $this->get_value( 'array' ), $classes );
			$this->set_value( array_unique( $classes ) );

		}

		return $this;

	}

	/**
	 * Removes many classes at once.
	 *
	 * @return Nmwdhj\Attributes\ClassAttribute
	 * @since 1.1
	 */
	public function remove_classes( $classes ) {

		$classes = $this->explode_classes( $classes );

		if ( $classes ) {

			$classes = array_diff( $this->get_value( 'array' ), $classes );
			$this->set_value( $classes );

		}

		return $this;

	}

	// Helpers

	/**
	 * Convert the classes list to an array.
	 *
	 * @return array
	 * @since 1.1
	 */
	protected function explode_classes( $value ) {

		if ( $value instanceof ClassAttribute ){
			$value = $value->get_value( 'array' );

		} elseif ( is_string( $value ) ) {
			$value = explode( ' ', $value );

		} elseif ( ! is_array( $value ) ) {
			$value = array();
		}

		$value = array_map( 'strtolower', $value );

		return $value;

	}

	/**
	 * Convert the classes list to a string.
	 *
	 * @return string
	 * @since 1.1
	 */
	protected function implode_classes( $value ) {

		if ( $value instanceof ClassAttribute ){
			$value = $value->get_value( 'string' );

		} elseif ( is_array( $value ) ) {
			$value = implode( ' ', $value );

		} else {
			$value = (string) $value;
		}

		$value = strtolower( $value );

		return $value;

	}

}