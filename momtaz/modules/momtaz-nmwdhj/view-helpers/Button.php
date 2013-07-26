<?php
/**
 * The Button element view class.
 *
 * @since 1.0
 */
class Momtaz_Nmwdhj_View_Button extends Momtaz_Nmwdhj_View {

    /**
     * Render the element view, and return the output.
     *
     * @since 1.0
     * @return string
     */
    public function render( Momtaz_Nmwdhj_Element $element ) {

        $value = '';

        if ( ! $element->has_attr( 'value' ) ) {

            $value = strval( $element->get_value() );

            if ( ! empty( $value ) )
                $value = ' value="' . esc_attr( $value ) . '"';

        } // end if

        return '<button'. $element->get_atts_string() . $value .'>' . $element->get_content() . '</button>';

    } // end render()

} // end Class Momtaz_Nmwdhj_View_Button