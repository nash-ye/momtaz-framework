<?php
namespace Nmwdhj\Views;

use Nmwdhj\Elements\Element;

/**
 * The Textarea element view class.
 *
 * @since 1.0
 */
class Textarea extends View {

	/**
	 * Render the Element View.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function render_element( Element $e ){
		return $this->render_tag( 'textarea', $e->get_atts_obj(), esc_textarea( $e->get_value() ) );
	}

}