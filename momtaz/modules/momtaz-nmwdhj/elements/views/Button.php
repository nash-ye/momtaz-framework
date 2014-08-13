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
	 * Render the Element View.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function render_element( Element $e ){
		return '<button' . $e->get_atts( 'string' ) . '>' . $e->get_content() . '</button>';
	}

}