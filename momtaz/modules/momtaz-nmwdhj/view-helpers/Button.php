<?php
namespace Nmwdhj\Views;
use Nmwdhj\Elements\Element;

/**
 * The Button element view class.
 *
 * @since 1.0
 */
class Button extends View {

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

			if ( ! empty( $value ) )
				$value = ' value="' . esc_attr( $value ) . '"';

		} // end if

		return '<button'. $element->get_atts_string() . $value .'>' . $element->get_content() . '</button>';

	} // end render()

} // end Class Button