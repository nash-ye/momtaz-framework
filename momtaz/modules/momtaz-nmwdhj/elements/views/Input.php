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
	 * Render the Element View.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function render_element( Element $e ){
		return '<input' . $e->get_atts( 'string' ) . ' />';
	}

}