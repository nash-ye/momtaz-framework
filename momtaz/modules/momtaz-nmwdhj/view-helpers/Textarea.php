<?php
namespace Nmwdhj\Views;
use Nmwdhj\Elements\Element;

/**
 * The Textarea element view class.
 *
 * @since 1.0
 */
class Textarea extends View{

	/**
	 * Render the element view, and return the output.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function render( Element $element ) {

		return '<textarea' . $element->get_atts_string() . '>' . esc_textarea( $element->get_value() ) . '</textarea>';

	} // end render()

} // end Class Textarea