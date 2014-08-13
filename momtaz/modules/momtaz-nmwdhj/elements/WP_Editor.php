<?php
namespace Nmwdhj\Elements;

/**
 * The WP_Editor element class.
 *
 * @since 1.0
 */
class WP_Editor extends Element {

	/**
	 * Get the element output.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function get_output() {
		$view = new \Nmwdhj\Views\WP_Editor();
		return $view( $this );
	}

}