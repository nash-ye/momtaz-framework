<?php
namespace Nmwdhj\Elements;

/**
 * The Checkboxes element class.
 *
 * @since 1.0
 */
class Checkboxes extends Input {

	/*** Properties ***********************************************************/

	/**
	 * Default element key.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $key = 'checkboxes';


	/*** Magic Methods ********************************************************/

	/**
	 * The Checkboxes element constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( $key = '', array $properties = null ) {

		// Set the type attribute.
		if ( ! $this->has_attr( 'type' ) )
			$this->set_attr( 'type', 'checkbox' );

		parent::__construct( $key, $properties );

		if ( is_array( $properties ) && isset( $properties['value_options'] ) )
			$this->set_value_options( $properties['value_options'] );

	} // end __construct()


	/*** Methods **************************************************************/

	// Value Options

	/**
	 * Get the values and labels for the value options.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_value_options() {
		return $this->value_options;
	} // end get_value_options()

	/**
	 * Ser the values and labels for the value options.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Checkboxes
	 */
	public function set_value_options( $options, $append = false ) {

		if ( is_array( $options ) ) {

			if ( $append )
				$options = array_merge( (array) $this->value_options, $options );

			$this->value_options = $options;

		} // end if

		return $this;

	} // end set_value_options()

	/**
	 * Remove all/specified value options.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Checkboxes
	 */
	public function remove_value_options( $options = '' ) {

		if ( is_array( $options ) && ! empty( $options ) ) {

			foreach( $options as $option )
				$this->remove_value_option( $option );

		} else {

			$this->value_options = array();

		} // end if

		return $this;

	} // end remove_value_options()

	/**
	 * Remove a specified value option.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Checkboxes
	 */
	public function remove_value_option( $option ) {
		unset( $this->value_options[$option] );
		return $this;
	} // end remove_value_option()

} // end Class Checkboxes