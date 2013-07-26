<?php
/**
 * The Label decorator class.
 *
 * @since 1.0
 */
class Momtaz_Nmwdhj_Decorator_Tag extends Momtaz_Nmwdhj_Decorator {

    // Output

    /**
     * Display the element output.
     *
     * @since 1.0
     */
    public function output() {
        echo $this->get_output();
    } // end output()

    /**
     * Get the element output.
     *
     * @since 1.0
     * @return string
     */
    public function get_output() {

        // Get the wrapper tag.
        $tag = $this->get_wrapper_tag();

        // Get the element output.
        $output = $this->get_element()
                    ->get_output();

        // Check the tag name.
        if ( ! is_string( $tag ) || empty( $tag ) )
            return $output;

        // Get the wrapper attributes.
        $atts = strval( $this->get_wrapper_atts() );

        // Return the element output with the tag wrapper.
        return '<' . $tag . $atts . '>' . $output . '</' . $tag . '>';

    } // end get_output()

    // Wrapper Attributes.

    /**
     * Set the wrapper attributes.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Decorator_Label
     */
    public function set_wrapper_atts( $atts ) {
        $this->set_option( 'wrapper_atts', $atts );
        return $this;
    } // end set_wrapper_atts()

    /**
     * Get the wrapper attributes.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Attributes
     */
    public function get_wrapper_atts() {
        return momtaz_nmwdhj_atts( $this->get_option( 'wrapper_atts' ) );
    } // end get_wrapper_atts()

    // Wrapper Tag.

    /**
     * Set the wrapper tag.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Decorator_Tag
     */
    public function set_wrapper_tag( $tag ) {
        $this->set_option( 'wrapper_tag', $tag );
        return $this;
    } // end set_wrapper_tag()

    /**
     * Get the wrapper tag.
     *
     * @since 1.0
     * @return string
     */
    public function get_wrapper_tag() {
        return $this->get_option( 'wrapper_tag', 'div' );
    } // end get_wrapper_tag()

} // end Class Momtaz_Nmwdhj_Decorator_Tag