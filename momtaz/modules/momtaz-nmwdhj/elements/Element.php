<?php
namespace Nmwdhj\Elements;

use Nmwdhj\EventManager;
use Nmwdhj\Attributes\Attributes;

/**
 * The abstract simple element class.
 *
 * @since 1.0
 */
abstract class Element {

	/*** Properties ***********************************************************/

	/**
	 * Element value.
	 *
	 * @var mixed
	 * @since 1.0
	 */
	protected $value;

	/**
	 * Element attributes.
	 *
	 * @var Nmwdhj\Attributes\Attributes
	 * @since 1.3
	 */
	protected $atts;

	/**
	 * Element Event Manager.
	 *
	 * @var Nmwdhj\EventManager
	 * @since 1.3
	 */
	protected $dispatcher;

	/**
	 * Element options.
	 *
	 * @var array
	 * @since 1.0
	 */
	protected $options = array();


	/*** Magic Methods ********************************************************/

	/**
	 * The default element constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( $config = NULL ) {
		$this->configure( $config );
	}


	/*** Methods **************************************************************/

	// Configurations

	/**
	 * Configure the element
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.3
	 */
	public function configure( $config ) {

		if ( is_array( $config ) ) {

			foreach ( $config as $key => $value ) {

				switch( $key = strtolower( $key ) ) {

					case 'id':
						$this->set_ID( $value );
						break;

					case 'nid':
						$this->set_NID( $value );
						break;

					case 'name':
						$this->set_name( $value );
						break;

					case 'atts':
						$this->set_atts( $value );
						break;

					case 'value':
						$this->set_value( $value );
						break;

					case 'options':
						$this->set_options( $value );
						break;

					case 'hint':
					case 'label':
					case 'wrapper':
						$this->set_option( $key, $value );
						break;

				}

			}

		}

	}

	// Value

	/**
	 * Get the element value
	 *
	 * @return mixed
	 * @since 1.0
	 */
	public function get_value() {

		if ( is_null( $this->value ) ) {

			$callback = $this->get_option( 'value_cb', array() );

			if ( is_array( $callback ) && ! empty( $callback ) ) {
				$this->set_value( call_user_func_array( $callback['name'], $callback['args'] ) );
			}

		}

		return $this->value;

	}

	/**
	 * Set the element value.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_value( $value ) {
		$this->value = $value;
		$this->get_dispatcher()->trigger( 'set_value', $value );
		return $this;
	}

	/**
	 * Set a value callback.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_value_callback( $callback ) {
		$this->set_value_callback_array( $callback, array_slice( func_get_args(), 1 ) );
		return $this;
	}

	/**
	 * Set a value callback with an array of parameters.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.1
	 */
	public function set_value_callback_array( $callback, array $args ) {
		$this->set_option( 'value_cb', array( 'name' => $callback, 'args' => $args ) );
		$this->get_dispatcher()->trigger( 'set_value_callback', $callback, $args );
		return $this;
	}

	// Special Attributes

	/**
	 * Set the element 'id' and 'name' attributes.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function set_NID( $value ) {
		$this->set_name( $value );
		$this->set_ID( $value );
		return $this;
	}

	/**
	 * Get the element ID attribute.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function get_ID( $def = '' ) {
		return $this->get_attr( 'id', $def );
	}

	/**
	 * Set the element ID attribute.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_ID( $value ) {
		$this->set_attr( 'id', $value );
		return $this;
	}

	/**
	 * Get the element name attribute.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function get_name( $def = '' ) {
		return $this->get_attr( 'name', $def );
	}

	/**
	 * Set the element name attribute.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_name( $value ) {
		$this->set_attr( 'name', $value );
		return $this;
	}

	// Attributes

	/**
	 * Get all the attributes array.
	 *
	 * @since 1.0
	 */
	public function get_atts( $type = 'array' ) {

		switch( $type ) {

			case 'obj':
				return $this->get_atts_obj();

			default:
			case 'array':
				return $this->get_atts_obj()->get_atts();

			case 'string':

				$args = NULL;

				if ( func_num_args() > 1 ) {
					$args = (array) func_get_arg( 1 );
				}

				return $this->get_atts_obj()->to_string( $args );

		}

	}

	/**
	 * Get an attribute value.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function get_attr( $key, $def = '' ) {
		return $this->get_atts_obj()->get_attr( $key, $def );
	}

	/**
	 * Check for an attribute existence.
	 *
	 * @return bool
	 * @since 1.0
	 */
	public function has_attr( $key ) {
		return $this->get_atts_obj()->has_attr( $key );
	}

	/**
	 * Set many attributes at once.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_atts( $atts, $override = true ) {
		$this->get_atts_obj()->set_atts( $atts, $override );
		return $this;
	}

	/**
	 * Set an attribute value.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_attr( $key, $value ) {
		$this->get_atts_obj()->set_attr( $key, $value );
		return $this;
	}

	/**
	 * Remove many attributes at once.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function remove_atts( $keys ) {
		$this->get_atts_obj()->remove_atts( $keys );
		return $this;
	}

	/**
	 * Remove an attribute.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function remove_attr( $key ) {
		$this->get_atts_obj()->remove_attr( $key );
		return $this;
	}

	/**
	 * Get the attributes object.
	 *
	 * @return Nmwdhj\Attributes\Attributes
	 * @since 1.0
	 */
	public function get_atts_obj() {

		if ( is_null( $this->atts ) ) {
			$this->atts = new Attributes();
		}

		return $this->atts;

	}

	// Options

	/**
	 * Get the defined options.
	 *
	 * @return array
	 * @since 1.0
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Get a specified option.
	 *
	 * @return mixed
	 * @since 1.0
	 */
	public function get_option( $option, $default = NULL ) {

		$options = $this->get_options();

		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		} else {
			return $default;
		}

	}

	/**
	 * Set the element options.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_options( array $options, $append = false ) {

		if ( $append ) {
			$this->options = array_merge( $this->options, $options );
		} else {
			$this->options = $options;
		}

		$this->get_dispatcher()->trigger( 'set_options', $options, $append );
		return $this;

	}

	/**
	 * Set a specified option.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function set_option( $option, $value ) {

		$this->options[ $option ] = $value;
		$this->get_dispatcher()->trigger( 'set_option', $option, $value );
		return $this;

	}

	/**
	 * Remove all/specified options.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function remove_options( $options = '' ) {

		if ( is_array( $options ) ) {

			foreach( $options as $option ) {
				$this->remove_option( $option );
			}

		} else {

			$this->set_options( array() );

		}

		$this->get_dispatcher()->trigger( 'remove_options', $options );
		return $this;

	}

	/**
	 * Remove a specified option.
	 *
	 * @return Nmwdhj\Elements\Element
	 * @since 1.0
	 */
	public function remove_option( $option ) {

		unset( $this->options[ $option ] );
		$this->get_dispatcher()->trigger( 'remove_option', $option );
		return $this;

	}

	// Event Manager

	/**
	 * Set the Dispatcher (EventManager).
	 *
	 * @return Nmwdhj\Elements\Element
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

	// Output

	/**
	 * Get the element output.
	 *
	 * @return string
	 * @since 1.0
	 */
	abstract public function get_output();

	/**
	 * Display the element output.
	 *
	 * @since 1.0
	 */
	public function output() {
		echo $this->get_output();
	}

}