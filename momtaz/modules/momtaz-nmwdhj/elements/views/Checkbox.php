<?php
namespace Nmwdhj\Views;

use Nmwdhj\Elements\Element;

/**
 * The Checkbox element view class.
 *
 * @since 1.0
 */
class Checkbox extends Input {

	/**
	 * Render the Element View.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function render_element( Element $e ){

		if ( $e->is_checked() ) {
			$e->set_attr( 'checked', 'checked' );
		}

		if ( ! $e->has_attr( 'value' ) ) {
			$e->set_attr( 'value', $e->get_checked_value() );
		}

		return parent::render_element( $e );

	}

}