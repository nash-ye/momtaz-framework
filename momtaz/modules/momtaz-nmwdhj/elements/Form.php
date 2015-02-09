<?php
namespace Nmwdhj\Elements;

/**
 * The Form element class
 *
 * @since 1.3
 */
class Form extends Fieldset {

	// Output

	/**
	 * Get the element output
	 *
	 * @return string
	 * @since 1.3
	 */
	public function get_output() {
		$view = new \Nmwdhj\Views\Form();
		return $view( $this );
	}

}