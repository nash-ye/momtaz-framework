<?php
/**
 * The Textarea element view class.
 *
 * @since 1.0
 */
class Momtaz_Nmwdhj_View_Textarea extends Momtaz_Nmwdhj_View {

    /**
     * Render the element view, and return the output.
     *
     * @since 1.0
     * @return string
     */
    public function render( Momtaz_Nmwdhj_Element $element ) {

        return '<textarea'. $element->get_atts_string() .'>' . esc_textarea( $element->get_value() ) . '</textarea>';

    } // end render()

} // end Class Momtaz_Nmwdhj_View_Textarea