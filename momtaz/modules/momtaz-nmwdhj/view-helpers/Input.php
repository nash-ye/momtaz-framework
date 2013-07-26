<?php
/**
 * The Input elements view class.
 *
 * @since 1.0
 */
class Momtaz_Nmwdhj_View_Input extends Momtaz_Nmwdhj_View {

    /**
     * Check the element.
     *
     * @since 1.0
     * @return boolean
     */
    public function check( Momtaz_Nmwdhj_Element $element ) {

        // The 'type' attribute is required.
        if ( ! $element->has_attr( 'type' ) )
            return false;

        return true;

    } // end check()

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

            if ( ! empty( $value ) ) {

                switch( strtolower( $element->get_attr( 'type' ) ) ) {

                    case 'url':
                        $value = ' value="' . esc_url( $value ) . '"';
                        break;

                    default:
                        $value = ' value="' . esc_attr( $value ) . '"';
                        break;

                } // end switch

            } // end if

        } // end if

        return '<input'. $element->get_atts_string() . $value .' />';

    } // end render()

} // end Class Momtaz_Nmwdhj_View_Input