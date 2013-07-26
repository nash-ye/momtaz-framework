<?php
/**
 * The Momtaz Nmwdhj Views class.
 *
 * @since 1.0
 */
class Momtaz_Nmwdhj_Views {

    /*** Properties ***********************************************************/

    /**
     * Views list.
     *
     * @since 1.0
     * @var array
     */
    protected static $views = array();


    /*** Methods ***************************************************************/

    // Getters

    /**
     * Get a view by key.
     *
     * @since 1.0
     * @return array
     */
    public static function get_by_key( $key ) {

        $key = sanitize_key( $key );

        if ( ! empty( $key ) ) {

            $views = self::get();

            if ( isset( $views[ $key ] ) )
                return $views[ $key ];

        } // end if

        return array();

    } // end get_by_key()

    /**
     * Retrieve a list of registered views.
     *
     * @since 1.0
     * @return array
     */
    public static function get( array $args = null, $operator = 'AND' ) {
        return wp_list_filter( self::$views, $args, $operator );
    } // end get()

    // Register/Deregister

    /**
     * Register a view.
     *
     * @since 1.0
     * @return boolean
     */
    public static function register( $key, array $args ) {

        $args['key'] = sanitize_key( $key );

        if ( empty( $args['key'] ) )
            return false;

        $args = wp_parse_args( $args, array(
            'class_name' => '',
            'class_path' => '',
        ) );

        if ( empty( $args['class_name'] ) )
            return false;

        if ( ! file_exists( $args['class_path'] ) )
            return false;

        self::$views[ $args['key'] ] = $args;

        return true;

    } // end register()

    /**
     * Register the default views.
     *
     * @since 1.0
     * @return void
     */
    public static function register_defaults() {

        self::register( 'button', array(
            'class_name' => 'Momtaz_Nmwdhj_View_Button',
            'class_path' => Momtaz_Nmwdhj::get_path( 'view-helpers/Button.php' ),
        ) );

        self::register( 'input', array(
            'class_name' => 'Momtaz_Nmwdhj_View_Input',
            'class_path' => Momtaz_Nmwdhj::get_path( 'view-helpers/Input.php' ),
        ) );

        self::register( 'select', array(
            'class_name' => 'Momtaz_Nmwdhj_View_Select',
            'class_path' => Momtaz_Nmwdhj::get_path( 'view-helpers/Select.php' ),
        ) );

        self::register( 'textarea', array(
            'class_name' => 'Momtaz_Nmwdhj_View_Textarea',
            'class_path' => Momtaz_Nmwdhj::get_path( 'view-helpers/Textarea.php' ),
        ) );

        self::register( 'wp_editor', array(
            'class_name' => 'Momtaz_Nmwdhj_View_WP_Editor',
            'class_path' => Momtaz_Nmwdhj::get_path( 'view-helpers/WP_Editor.php' ),
        ) );

        self::register( 'checkbox', array(
            'class_name' => 'Momtaz_Nmwdhj_View_Checkbox',
            'class_path' => Momtaz_Nmwdhj::get_path( 'view-helpers/Checkbox.php' ),
        ) );

        self::register( 'checkboxes', array(
            'class_name' => 'Momtaz_Nmwdhj_View_Checkboxes',
            'class_path' => Momtaz_Nmwdhj::get_path( 'view-helpers/Checkboxes.php' ),
        ) );

    } // end register_defaults()

    /**
     * Remove a registered view.
     *
     * @since 1.0
     * @return boolean
     */
    public static function deregister( $key ) {

        $key = sanitize_key( $key );

        if ( empty( $key ) )
            return false;

        unset( self::$views[ $key ] );

        return true;

    } // end deregister()

    // Checks

    /**
     * Check view class.
     *
     * @since 1.0
     * @return boolean
     */
    public static function check_class( $class_name, $autoload = true ) {

        if ( empty( $class_name ) )
            return false;

        if ( ! class_exists( $class_name, $autoload ) )
            return false;

        if ( ! is_subclass_of( $class_name, 'Momtaz_Nmwdhj_View' ) )
            return false;

        return true;

    } // end check_class()

    // Loaders

    /**
     * Load view class file.
     *
     * @since 1.0
     * @return boolean
     */
    public static function load_class( $class_name ) {

        if ( empty( $class_name ) )
            return false;

        if ( ( $view = self::get( array( 'class_name' => $class_name ), 'OR' ) ) ) {

            $view = reset( $view );

            if ( ! empty( $view['class_path'] ) && file_exists( $view['class_path'] ) ) {

                require $view['class_path'];

                return true;

            } // end if

        } // end if

        return false;

    } // end load_class()

} // end Class Momtaz_Nmwdhj_Views

/**
 * The view abstract class.
 *
 * @since 1.0
 */
abstract class Momtaz_Nmwdhj_View {

    /*** Abstract Methods *****************************************************/

    /**
     * Check the element.
     *
     * @since 1.0
     * @return boolean
     */
    public function check( Momtaz_Nmwdhj_Element $element ){
        return true;
    } // end check()

    /**
     * Prepare the element.
     *
     * @since 1.0
     * @return void
     */
    public function prepare( Momtaz_Nmwdhj_Element $element ){
    } // end prepare()

    /**
     * Render the element view, and return the output.
     *
     * @since 1.0
     * @return string
     */
    abstract public function render( Momtaz_Nmwdhj_Element $element );


    /*** Magic Methods ********************************************************/

    /**
     * Invoke helper as functor.
     *
     * @since 1.0
     * @return string
     */
    public function __invoke( Momtaz_Nmwdhj_Element $element ){

        if ( $this->check( $element ) ) {

            $this->prepare( $element );

            return $this->render( $element );

        } // end if

    } // end __invoke()

} // end Class Momtaz_Nmwdhj_View