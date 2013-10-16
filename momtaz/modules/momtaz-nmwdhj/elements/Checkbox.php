<?php
namespace Nmwdhj\Elements;

/**
 * The Checkbox element class.
 *
 * @since 1.0
 */
class Checkbox extends Input {

	/*** Properties ***********************************************************/

	/**
	 * Default element key.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $key = 'checkbox';


	/*** Magic Methods ********************************************************/

	/**
	 * The Checkbox element constructor.
	 *
	 * @since 1.0
	 */
	public function __construct( $key = '', array $properties = null ) {

		// Set the type attribute.
		if ( ! $this->has_attr( 'type' ) )
			$this->set_attr( 'type', 'checkbox' );

		parent::__construct( $key, $properties );

	} // end __construct()


	/*** Methods **************************************************************/

	// Value

	/**
	 * Checks if this checkbox is checked.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public function is_checked() {
		return (bool) $this->get_value();
	} // end is_checked()

	/**
	 * Checks or unchecks the checkbox.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Checkbox
	 */
	public function set_checked( $value ) {
		return $this->set_value( (bool) $value );
	} // end set_checked()

	/**
	 * Set the element value.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Checkbox
	 */
	public function set_value( $value ) {

		if ( ! is_bool( $value ) )
			$value = ( $value === $this->get_checked_value() );

		return parent::set_value( $value );

	} // end set_value()

	// Checked Value

	/**
	 * Get the value to use when checkbox is checked
	 *
	 * @since 1.0
	 * @return scalar
	 */
	public function get_checked_value( $def = '1' ) {

		if ( ! is_scalar( $def ) )
			$def = '1';

		return $this->get_option( 'checked_value', $def );

	} // end get_checked_value()

	/**
	 * Set the value to use when checkbox is checked
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Checkbox
	 */
	public function set_checked_value( $value ) {

		if ( ! is_scalar( $value ) )
			return $this;

		return $this->set_option( 'checked_value', $value );

	} // end set_checked_value()

	// Unchecked Value

	/**
	 * Get the value to use when checkbox is unchecked.
	 *
	 * @since 1.0
	 * @return scalar
	 */
	public function get_unchecked_value( $def = '0' ) {

		if ( ! is_scalar( $def ) )
			$def = '0';

		return $this->get_option( 'unchecked_value', $def );

	} // end get_unchecked_value()

	/**
	 * Set the value to use when checkbox is unchecked.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Elements\Checkbox
	 */
	public function set_unchecked_value( $value ) {

		if ( ! is_scalar( $value ) )
			return $this;

		return $this->set_option( 'unchecked_value', $value );

	} // end set_unchecked_value()

} // end Class Checkbox