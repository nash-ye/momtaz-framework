<?php
namespace Nmwdhj\Elements;

/**
 * The Textarea element class.
 *
 * @since 1.0
 */
class Textarea extends Element {

	/**
	 * Get the element output.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function get_output() {
		$view = new \Nmwdhj\Views\Textarea();
		return $view( $this );
	}

}