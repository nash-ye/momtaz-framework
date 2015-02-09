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
		return $this->render_tag( 'button', $e->get_atts_obj(), $e->get_content() );
	}

}