<?php
/**
 * The Momtaz Nmwdhj Decorators class.
 *
 * @since 1.0
 */
class Momtaz_Nmwdhj_Decorators {

    /*** Properties ***********************************************************/

    /**
     * Decorators list.
     *
     * @since 1.0
     * @var array
     */
    protected static $decorators = array();


    /*** Methods ***************************************************************/

    // Getters

    /**
     * Get a decorator by key.
     *
     * @since 1.0
     * @return array
     */
    public static function get_by_key( $key ) {

        $key = sanitize_key( $key );

        if ( ! empty( $key ) ) {

            $decorators = self::get();

            if ( isset( $decorators[ $key ] ) )
                return $decorators[ $key ];

        } // end if

        return array();

    } // end get_by_key()

    /**
     * Retrieve a list of registered decorators.
     *
     * @since 1.0
     * @return array
     */
    public static function get( array $args = null, $operator = 'AND' ) {
        return wp_list_filter( self::$decorators, $args, $operator );
    } // end get()

    // Register/Deregister

    /**
     * Register a decorator.
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

        self::$decorators[ $args['key'] ] = $args;

        return true;

    } // end register()

    /**
     * Register the Momtaz Nmwdhj default decorators.
     *
     * @since 1.0
     * @return void
     */
    public static function register_defaults() {

        self::register( 'tag', array(
            'class_name' => 'Momtaz_Nmwdhj_Decorator_Tag',
            'class_path' => Momtaz_Nmwdhj::get_path( 'view-helpers/decorators/Tag.php' ),
        ) );

        self::register( 'label', array(
            'class_name' => 'Momtaz_Nmwdhj_Decorator_Label',
            'class_path' => Momtaz_Nmwdhj::get_path( 'view-helpers/decorators/Label.php' ),
        ) );

        self::register( 'description', array(
            'class_name' => 'Momtaz_Nmwdhj_Decorator_Description',
            'class_path' => Momtaz_Nmwdhj::get_path( 'view-helpers/decorators/Description.php' ),
        ) );

    } // end register_defaults()

    /**
     * Remove a registered decorator.
     *
     * @since 1.0
     * @return boolean
     */
    public static function deregister( $key ) {

        $key = sanitize_key( $key );

        if ( empty( $key ) )
            return false;

        unset( self::$decorators[ $key ] );

        return true;

    } // end deregister()

    // Checks

    /**
     * Check decorator class.
     *
     * @since 1.0
     * @return boolean
     */
    public static function check_class( $class_name, $autoload = true ) {

        if ( empty( $class_name ) )
            return false;

        if ( ! class_exists( $class_name, $autoload ) )
            return false;

        if ( ! is_subclass_of( $class_name, 'Momtaz_Nmwdhj_Decorator' ) )
            return false;

        return true;

    } // end check_class()

    // Loaders

    /**
     * Load decorator class file.
     *
     * @since 1.0
     * @return boolean
     */
    public static function load_class( $class_name ) {

        if ( empty( $class_name ) )
            return false;

        if ( ( $decorator = self::get( array( 'class_name' => $class_name ), 'OR' ) ) ) {

            $decorator = reset( $decorator );

            if ( ! empty( $decorator['class_path'] ) && file_exists( $decorator['class_path'] ) ) {

                require $decorator['class_path'];

                return true;

            } // end if

        } // end if

        return false;

    } // end load_class()

} // end Class Momtaz_Nmwdhj_Decorators

/**
 * The decorator abstract class.
 *
 * @since 1.0
 */
abstract class Momtaz_Nmwdhj_Decorator implements Momtaz_Nmwdhj_Element {

    /*** Properties ***********************************************************/

    /**
     * The element.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Element
     */
    protected $element;


    /*** Magic Methods ********************************************************/

    /**
     * A magic method to redirect the methods calls to the element object.
     *
     * @throws Exception
     * @since 1.0
     */
    public function __call( $method, $args ) {

        if ( is_callable( array( $this->get_element(), $method ) ) )
            return call_user_func_array( array( $this->get_element(), $method ), $args );

        throw new Exception( 'Undefined method - ' . get_class( $this->get_element() ) . '->' . $method );

    } // end __call()

    /**
     * The default Decorator constructor.
     *
     * @since 1.0
     */
    public function __construct( Momtaz_Nmwdhj_Element $element ) {
        $this->set_element( $element );
    } // end __construct()


    /*** Methods **************************************************************/

    /**
     * Set the element.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Decorator
     */
    protected function set_element( Momtaz_Nmwdhj_Element $element ){
        $this->element = $element;
        return $this;
    } // end set_element()

    /**
     * Get the original, undecorated element.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Element
     */
    public function get_original_element(){

        $element = $this->get_element();

        while( $element instanceof Momtaz_Nmwdhj_Decorator )
            $element = $element->get_element();

        return $element;

    } // end get_original_element()

    /**
     * Get the element.
     *
     * @since 1.0
     * @return Momtaz_Nmwdhj_Element
     */
    public function get_element(){
        return $this->element;
    } // end get_element()

} // end Class Momtaz_Nmwdhj_Decorator