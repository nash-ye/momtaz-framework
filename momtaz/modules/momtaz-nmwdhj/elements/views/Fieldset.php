<?php
namespace Nmwdhj\Views;

use Nmwdhj\Elements\Element;

/**
 * The Fieldset element view class
 *
 * @since 1.3
 */
class Fieldset extends View {

	/**
	 * Render the Element View
	 *
	 * @return string
	 * @since 1.3
	 */
	public function render_element( Element $e ){

		$content = '<fieldset' . $e->get_atts( 'string' ) . '>';

		foreach( $e->get_elements() as $element ) {
			$content .= $element->get_output();
		}

		$content .= '</fieldset>';

		return $content;

	}

}