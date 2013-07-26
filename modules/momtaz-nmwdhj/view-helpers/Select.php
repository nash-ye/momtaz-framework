<?php
/**
 * The Select elements view class.
 *
 * @since 1.0
 */
class Momtaz_Nmwdhj_View_Select extends Momtaz_Nmwdhj_View {

    /**
     * Prepare the element.
     *
     * @since 1.0
     * @return void
     */
    public function prepare( Momtaz_Nmwdhj_Element $element ){

        // Fix the name attribute.
        if ( $element->has_attr( 'multiple' ) ) {

            $name = $element->get_attr( 'name' );

            if ( ! empty( $name ) && substr( $name, -2 ) != '[]' )
                $element->set_attr( 'name', $name . '[]' );

        } // end if

    } // end prepare()

    /**
     * Render the element view, and return the output.
     *
     * @since 1.0
     * @return string
     */
    public function render( Momtaz_Nmwdhj_Element $element ) {

        // Open tag.
        $output = '<select' . $element->get_atts_string() . '>';

        // Options list.
        $output .= $this->render_options( $element->get_value_options(), $element->get_value() );

        // Close tag
        $output .= '</select>';

        return $output;

    } // end render()

    /**
     * Render an array of options.
     *
     * Individual options should be of the form:
     *
     * <code>
     * array(
     *     'value'    => $value,
     *     'label'    => $label,
     *     'disabled' => $boolean,
     *      selected  => $boolean,
     * )
     * </code>
     *
     * or:
     *
     * <code>
     * array(
     *     $value    => $label,
     * )
     * </code>
     *
     * @since 1.0
     * @return string
     */
    public function render_options( array $options, $value ) {

        $chunks = array();

        foreach( $options as $key => $option ) {

            if ( is_scalar( $option ) ) {

                $option = array(
                    'value' => $key,
                    'label' => $option,
                );

            } // end if

            if ( isset( $option['options'] ) && is_array( $option['options'] ) ) {
                $chunks[] = $this->render_optgroup( $option );
                continue;
            } // end if

            if ( isset( $option['value'] ) && in_array( $option['value'], (array) $value ) ) {
                $option['selected'] = true;
            } // end if

            $chunks[] = $this->render_option( $option );

        } // end foreach

        return implode( "\n", $chunks );

    } // end render_options()

    /**
     * Render an optgroup.
     *
     * Should be of the form:
     * <code>
     * array(
     *     'label'    => 'label',
     *     'atts'     => $array,
     *     'options'  => $array,
     * )
     * </code>
     *
     * @since 1.0
     * @return string
     */
    public function render_optgroup( array $optgroup ) {

        $defaults = array(
            'label' => '',
            'atts' => array(),
            'options' => array(),
        );

        $optgroup = wp_parse_args( $optgroup, $defaults );

        if ( ! empty( $optgroup['label'] ) )
            $optgroup['atts']['label'] = $optgroup['label'];

        $attributes = strval( momtaz_nmwdhj_atts( $optgroup['atts'] ) );

        return '<optgroup'. $attributes .'>'. $this->render_options( $optgroup['options'] ) .'</optgroup>';

    } // end render_optgroup()

    /**
     * Render an individual option.
     *
     * Should be of the form:
     * <code>
     * array(
     *     'value'    => 'value',
     *     'label'    => 'label',
     *     'disabled' => $boolean,
     *      selected  => $boolean,
     * )
     * </code>
     *
     * @since 1.0
     * @return string
     */
    public function render_option( array $option ) {

        $defaults = array(
            'value' => '',
            'label' => '',
            'atts' => array(),
            'disabled' => false,
            'selected' => false,
        );

        $option = wp_parse_args( $option, $defaults );

        foreach( array( 'value', 'disabled', 'selected' ) as $k ) {

            $option['atts'][ $k ] = $option[ $k ];
            unset( $option[ $k ] );

        } // end foreach

        $attributes = strval( momtaz_nmwdhj_atts( $option['atts'] ) );

        return '<option'. $attributes .'>'. esc_html( $option['label'] ) .'</option>';

    } // end render_option()

} // end Class Momtaz_Nmwdhj_View_Select