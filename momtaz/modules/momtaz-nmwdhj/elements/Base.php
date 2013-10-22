<?php
namespace Nmwdhj\Elements;
use Nmwdhj\Attributes\Attributes;
use Nmwdhj\Exception;
use Nmwdhj\Views;

/**
 * The abstract simple element class.
 *
 * @since 1.0
 */
abstract class Base implements Element {

	/*** Properties ***********************************************************/

	/**
	 * Element key.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $key;

	/**
	 * Element value.
	 *
	 * @since 1.0
	 * @var mixed
	 */
	protected $value;

	/**
	 * Element attributes object.
	 *
	 * @since 1.0
	 * @var Nmwdhj\Attributes\Attributes
	 */
	protected $attributes;

	/**
	 * Element value callback.
	 *
	 * @since 1.0
	 * @var array
	 */
	protected $value_callback;

	/**
	 * Element options.
	 *
	 * @since 1.0
	 * @var array
	 */
	protected $options = array();


	/*** Magic Methods ********************************************************/

	/**
	 * The default element constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( $key = '', array $properties = null ) {

		$this->set_key( $key );

		if ( ! is_null( $properties ) ) {

			foreach ( $properties as $property => $value ) {

				switch( strtolower( $property ) ) {

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

				} // end Switch

			} // end foreach

		} // end if

	} // end __construct()


	/*** Methods **************************************************************/

	// Key

	/**
	 * Get the element key.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_key() {
		return $this->key;
	} // end get_key()

	/**
	 * Set the element key.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	protected function set_key( $key ) {

		if ( ! empty( $key ) )
			$this->key = $key;

		return $this;

	} // end set_key()

	// Value

	/**
	 * Get the element value.
	 *
	 * @since 1.0
	 * @return mixed
	 */
	public function get_value() {

		if ( is_null( $this->value ) ) {

			$callback = $this->get_value_callback();

			if ( is_array( $callback ) && ! empty( $callback ) )
				$this->set_value( call_user_func_array( $callback['name'], $callback['args'] ) );

		} // end if

		return $this->value;

	} // end get_value()

	/**
	 * Set the element value.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	public function set_value( $value ) {
		$this->value = $value;
		return $this;
	} // end set_value()

	/**
	 * Get the element value callback.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_value_callback() {
		return $this->value_callback;
	} // end get_value_callback()

	/**
	 * Set a value callback.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	public function set_value_callback( $callback ) {

		$params = array_slice( func_get_args(), 1 );
		$this->set_value_callback_array( $callback, $params );

		return $this;

	} // end set_value_callback()

	/**
	 * Set a value callback with an array of parameters.
	 *
	 * @since 1.1
	 * @return Nmwdhj\Elements\Base
	 */
	public function set_value_callback_array( $callback, array $param ) {

		if ( is_callable( $callback ) ) {

			$this->value_callback = array(
				'name' => $callback,
				'args' => $param,
			);

		} // end if

		return $this;

	} // end set_value_callback_array()

	// The Special Attributes

	/**
	 * Set the element 'id' and 'name' attributes.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function set_NID( $value ) {
		$this->set_name( $value );
		$this->set_ID( $value );
		return $this;
	} // end set_NID()

	/**
	 * Get the element ID attribute.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_ID( $def = '' ) {
		return $this->get_attr( 'id', $def );
	} // end get_ID()

	/**
	 * Set the element ID attribute.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	public function set_ID( $value ) {
		$this->set_attr( 'id', $value );
		return $this;
	} // end set_ID()

	/**
	 * Get the element name attribute.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_name( $def = '' ) {
		return $this->get_attr( 'name', $def );
	} // end get_name()

	/**
	 * Set the element name attribute.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	public function set_name( $value ) {
		$this->set_attr( 'name', $value );
		return $this;
	} // end set_name()

	// Output

	/**
	 * Get the element output.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_output() {

		$view = Views\Manager::get_by_key( $this->get_key() );

		if ( empty( $view ) ) {

			if ( ( $element = Manager::get_by_key( $this->get_key() ) ) )
				$view = Views\Manager::get_by_key( $element->key );

			if ( empty( $view ) )
				throw new Exception( 'invalid_view' );

		} // end if

		if ( ! Views\Manager::check_class( $view->class_name ) )
			throw new Exception( 'invalid_view_class' );

		return call_user_func( new $view->class_name, $this );

	} // end get_output()

	/**
	 * Display the element output.
	 *
	 * @since 1.0
	 */
	public function output() {
		echo $this->get_output();
	} // end output()

	// Attributes

	/**
	 *  Get all the attributes array.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_atts() {
		return $this->get_atts_obj()->get_atts();
	} // end get_atts()

	/**
	 * Get an attribute value.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_attr( $key, $def = '' ) {
		return $this->get_atts_obj()->get_attr( $key, $def );
	} // end get_attr()

	/**
	 * Get an attribute object.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_attr_obj( $key ) {
		return $this->get_atts_obj()->get_attr_obj( $key );
	} // end get_attr_obj()

	/**
	 * Check for an attribute existence.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public function has_attr( $key ) {
		return $this->get_atts_obj()->has_attr( $key );
	} // end has_attr()

	/**
	 * Set many attributes at once.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	public function set_atts( array $atts ) {

		foreach( $atts as $key => $value )
			$this->set_attr( $key, $value );

		return $this;

	} // end set_atts()

	/**
	 * Set an attribute value.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	public function set_attr( $key, $value ) {
		$this->get_atts_obj()->set_attr( $key, $value );
		return $this;
	} // end set_attr()

	/**
	 * Remove many attributes at once.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	public function remove_atts( array $keys ) {

		foreach( $keys as $key )
			$this->remove_attr( $key );

		return $this;

	} // end remove_atts()

	/**
	 * Remove an attribute.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	public function remove_attr( $key ) {
		$this->get_atts_obj()->remove_attr( $key );
		return $this;
	} // end remove_attr()

	/**
	 * Convert the attributes list to string.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_atts_string( array $args = null ) {
		return $this->get_atts_obj()->to_string( $args );
	} // end get_atts_string()

	/**
	 * Get the attributes object.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Attributes\Attributes
	 */
	protected function get_atts_obj() {

		if ( is_null( $this->attributes ) )
			$this->attributes = new Attributes();

		return $this->attributes;

	} // end get_atts_obj()

	// Options

	/**
	 * Get the defined options.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	} // end get_options()

	/**
	 * Get a specified option.
	 *
	 * @since 1.0
	 * @return mixed
	 */
	public function get_option( $option, $def = '' ) {

		if ( ! empty( $option ) ) {

			$options = $this->get_options();

			if ( isset( $options[$option] ) )
				return $options[$option];

		} // end if

		return $def;

	} // end get_option()

	/**
	 * Set the element options.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	public function set_options( $options ) {

		if ( is_array( $options ) )
			$this->options = $options;

		return $this;

	} // end set_options()

	/**
	 * Set a specified option.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	public function set_option( $option, $value ) {

		if ( ! empty( $option ) )
			$this->options[$option] = $value;

		return $this;

	} // end set_option()

	/**
	 * Remove all/specified options.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	public function remove_options( $options = '' ) {

		if ( is_array( $options ) && ! empty( $options ) ) {

			foreach( $options as $option )
				$this->remove_option( $option );

		} else {

			$this->set_options( array() );

		} // end if

		return $this;

	} // end remove_options()

	/**
	 * Remove a specified option.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Base
	 */
	public function remove_option( $option ) {

		if ( ! empty( $option ) )
			unset( $this->options[$option] );

		return $this;

	} // end remove_option()

} // end Class Base