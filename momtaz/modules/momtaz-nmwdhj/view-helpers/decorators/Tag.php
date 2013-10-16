<?php
namespace Nmwdhj\Decorators;
use Nmwdhj\Attributes\Attributes;

/**
 * The Label decorator class.
 *
 * @since 1.0
 */
class Tag extends Decorator {

	// Output

	/**
	 * Display the element output.
	 *
	 * @since 1.0
	 */
	public function output() {
		echo $this->get_output();
	} // end output()

	/**
	 * Get the element output.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_output() {

		// Get the wrapper tag.
		$tag = $this->get_wrapper_tag();

		// Get the element output.
		$output = $this->get_element()->get_output();

		// Check the tag name.
		if ( ! is_string( $tag ) || empty( $tag ) )
			return $output;

		// Get the wrapper attributes.
		$atts = strval( $this->get_wrapper_atts() );

		// Return the element output with the tag wrapper.
		return '<' . $tag . $atts . '>' . $output . '</' . $tag . '>';

	} // end get_output()

	// Wrapper Attributes.

	/**
	 * Set the wrapper attributes.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Decorator\Tag
	 */
	public function set_wrapper_atts( $atts ) {
		$this->set_option( 'wrapper_atts', $atts );
		return $this;
	} // end set_wrapper_atts()

	/**
	 * Get the wrapper attributes.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Attributes\Attributes
	 */
	public function get_wrapper_atts() {

		$atts = $this->get_option( 'wrapper_atts' );

		if ( ! $atts instanceof Attributes ) {

			$atts = new Attributes( $atts );
			$this->set_wrapper_atts( $atts );

		} // end if

		return $atts;

	} // end get_wrapper_atts()

	/**
	 * Set a wrapper attribute.
	 *
	 * @since 1.2
	 * @return Nmwdhj\Decorator\Tag
	 */
	public function set_wrapper_attr( $key, $value, $override = true ) {
		$this->get_wrapper_atts()->set_attr( $key, $value, $override );;
		return $this;
	} // end set_wrapper_attr()

	/**
	 * Get a wrapper attribute.
	 *
	 * @since 1.2
	 * @return string
	 */
	public function get_wrapper_attr( $key, $def = '' ) {
		return $this->get_wrapper_atts()->get_attr( $key, $def );;
	} // end get_wrapper_attr()

	// Wrapper Tag.

	/**
	 * Set the wrapper tag.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Decorator\Tag
	 */
	public function set_wrapper_tag( $tag ) {
		$this->set_option( 'wrapper_tag', $tag );
		return $this;
	} // end set_wrapper_tag()

	/**
	 * Get the wrapper tag.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_wrapper_tag() {
		return $this->get_option( 'wrapper_tag', 'div' );
	} // end get_wrapper_tag()

} // end Class Tag