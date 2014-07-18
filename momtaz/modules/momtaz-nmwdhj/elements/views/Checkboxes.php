<?php
namespace Nmwdhj\Views;

use Nmwdhj\Elements\Element;

/**
 * The Checkboxes element view class.
 *
 * @since 1.0
 */
class Checkboxes extends View {

	/**
	 * Render the Element View.
	 *
	 * @return string
	 * @since 1.3
	 */
	public function render_element( Element $e ){

		$content = '';

		foreach( $e->get_value_options() as $key => $option ) {

			if ( is_scalar( $option ) ) {

				$option = array(
					'value' => $key,
					'label' => $option,
				);

			}

			// Render the option.
			$content .= $this->render_option( $option, $e ) . "\n";

		}

		return $content;

	}

	/**
	 * Render an individual option.
	 *
	 * Should be of the form:
	 * <code>
	 * array(
	 *	 'atts'			=> $atts,
	 *	 'value'		=> $value,
	 *	 'label'		=> $label,
	 *	 'disabled'		=> $boolean,
	 *	 'checked'		=> $boolean,
	 * )
	 * </code>
	 *
	 * @return string
	 * @since 1.0
	 */
	public function render_option( array $option, Element $e ) {

		// The default option arguments.
		$option = array_merge( array(
			'disabled' => false,
			'checked' => false,
			'value' => NULL,
			'atts' => NULL,
			'label' => '',
		), $option );


		/** CheckBox Input ****************************************************/

		// Set the 'checked' attribute.
		if ( empty( $option['selected'] ) && ! empty( $option['value'] ) ) {

			if ( in_array( $option['value'], (array) $e->get_value(), true ) ) {
				$option['selected'] = true;
			}

		}


		// Get the Attributes object.
		$option['atts'] = \Nmwdhj\create_atts_obj( $option['atts'] )
				->set_atts( array(
					'selected'	=> (bool) $option['selected'],
					'disabled'	=> (bool) $option['disabled'],
					'value'		=> $option['value'],
				) )
				->set_atts( $e->get_atts(), false );

		// Fix the 'name' attribute.
		if ( $option['atts']->has_attr( 'name' ) ) {

			$name = $option['atts']->get_attr( 'name' );

			if ( substr( $name, -2 ) !== '[]' ) {
				$option['atts']->set_attr( 'name', $name . '[]' );
			}

		}


		// The checkbox input output.
		$content = '<input' . strval( $option['atts'] ) . ' />';


		/** CheckBox Label ****************************************************/

		if ( ! empty( $option['label'] ) ) {

			$label_atts = \Nmwdhj\create_atts_obj( $e->get_option( 'label_atts' ) );

			if ( ! $label_atts->has_attr( 'for' ) && $option['atts']->has_attr( 'id' ) ) {
				$label_atts->set_attr( 'for', $option['atts']->get_attr( 'id' ) );
			}

			$content = $this->render_label( array(
				'position'	=> $e->get_option( 'label_position' ),
				'label'		=> $option['label'],
				'atts'		=> $label_atts,
			), $content );

		}

		return $content;

	}

}