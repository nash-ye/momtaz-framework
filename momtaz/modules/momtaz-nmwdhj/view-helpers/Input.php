<?php
namespace Nmwdhj\Views;
use Nmwdhj\Elements\Element;

/**
 * The Input elements view class.
 *
 * @since 1.0
 */
class Input extends View {

	/**
	 * Check the element.
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public function check( Element $element ) {

		// The 'type' attribute is required.
		if ( ! $element->has_attr( 'type' ) )
			return false;

		return true;

	} // end check()

	/**
	 * Render the element view, and return the output.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function render( Element $element ) {

		$value = '';

		if ( ! $element->has_attr( 'value' ) ) {

			$value = strval( $element->get_value() );

			if ( ! empty( $value ) ) {

				switch( strtolower( $element->get_attr( 'type' ) ) ) {

					case 'url':
						$value = ' value="' . esc_url( $value ) . '"';
						break;

					default:
						$value = ' value="' . esc_attr( $value ) . '"';
						break;

				} // end switch

			} // end if

		} // end if

		return '<input'. $element->get_atts_string() . $value .' />';

	} // end render()

} // end Class Input