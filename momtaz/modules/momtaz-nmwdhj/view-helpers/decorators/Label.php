<?php
namespace Nmwdhj\Decorators;
use Nmwdhj\Attributes\Attributes;

/**
 * The Label decorator class.
 *
 * @since 1.0
 */
class Label extends Decorator {

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

		// Get the label text.
		$label = $this->get_label();

		// Get the element output.
		$output = $this->get_element()->get_output();

		// Check the label text.
		if ( empty( $label ) )
			return $output;

		// Get the label attributes.
		$atts = $this->get_label_atts();

		if ( $this->has_attr( 'id' ) )
			$atts->set_attr( 'for', $this->get_attr( 'id' ), false );

		switch( strtolower( $this->get_label_position() ) ) {

			case 'after':
				$output .= '<label'. strval( $atts ) .'>' . $label . '</label>';
				break;

			case 'surround_after':
				$output = '<label'. strval( $atts ) .'>' . $label . $output . '</label>';
				break;

			case 'surround_before':
				$output = '<label'. strval( $atts ) .'>' . $output . $label . '</label>';
				break;

			default:
			case 'before':
				$output = '<label'. strval( $atts ) .'>' . $label . '</label>' . $output;
				break;

		} // end switch

		// Return the output.
		return $output;

	} // end get_output()

	// Label Position.

	/**
	 * Set the label position.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Decorator\Label
	 */
	public function set_label_position( $position ) {
		$this->set_option( 'label_position', $position );
		return $this;
	} // end set_label_position()

	/**
	 * Get the label position.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_label_position() {
		return $this->get_option( 'label_position' );
	} // end get_label_position()

	// Label Attributes.

	/**
	 * Set the label attributes.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Decorator\Label
	 */
	public function set_label_atts( $atts ) {
		$this->set_option( 'label_atts', $atts );
		return $this;
	} // end set_label_atts()

	/**
	 * Get the label attributes.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Attributes\Attributes
	 */
	public function get_label_atts() {

		$atts = $this->get_option( 'label_atts' );

		if ( ! $atts instanceof Attributes ) {

			$atts = new Attributes( $atts );
			$this->set_label_atts( $atts );

		} // end if

		return $atts;

	} // end get_label_atts()

	/**
	 * Set a label attribute.
	 *
	 * @since 1.2
	 * @return Nmwdhj\Decorator\Label
	 */
	public function set_label_attr( $key, $value, $override = true ) {
		$this->get_label_atts()->set_attr( $key, $value, $override );;
		return $this;
	} // end set_label_attr()

	/**
	 * Get a label attribute.
	 *
	 * @since 1.2
	 * @return string
	 */
	public function get_label_attr( $key, $def = '' ) {
		return $this->get_label_atts()->get_attr( $key, $def );;
	} // end get_label_attr()

	// Label Text.

	/**
	 * Set the label text.
	 *
	 * @since 1.0
	 * @return Nmwdhj\Decorator\Label
	 */
	public function set_label( $text ) {
		$this->set_option( 'label', $text );
		return $this;
	} // end set_label()

	/**
	 * Get the label text.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_label() {
		return $this->get_option( 'label' );
	} // end get_label()

} // end Class Label