<?php
namespace Nmwdhj\Views;

use Nmwdhj\Elements\Element;

/**
 * The WP_Editor element view class.
 *
 * @since 1.0
 */
class WP_Editor extends View {

	/**
	 * Render the Element View.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function render_element( Element $e ){

		ob_start();

			// Merge the editor settings with the defaults.
			$settings = array_merge( array(
				'textarea_name' => $e->get_name(),
			), (array) $e->get_option( 'settings' ) );

			wp_editor( $e->get_value(), $e->get_ID(), $settings );

			$content = ob_get_contents();

		ob_end_clean();

		return $content;

	}

}