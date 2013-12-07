<?php
/**
 * A helper class to generate a dynamic stylesheet content.
 *
 * @since 1.2
 */
class Momtaz_Dynamic_Stylesheet {

	/*** Properties ***********************************************************/

	/**
	 * The options list.
	 *
	 * @var array
	 * @since 1.2
	 */
	protected $options = array();

	/**
	 * The stylesheets list.
	 *
	 * @var array
	 * @since 1.2
	 */
	protected $stylesheets = array();


	/*** Methods **************************************************************/

	/**
	 * @return void
	 * @since 1.2
	 */
	public function headers() {

		header_remove();

		// Send the content headers.
		$this->send_content_headers();

		if ( ! empty( $this->stylesheets ) ) {

			// Send the cache headers.
			$this->send_cache_headers();

		} else {

			// Send the no-cache headers.
			$this->send_nocache_headers();

		} // end if

	}

	/**
	 * @return void
	 * @since 1.2
	 */
	public function contents() {

		$paths = (array) $this->get_stylesheets();
		$paths = $this->list_pluck( $paths, 'path' );

		foreach( $paths as $path ) {
			echo file_get_contents( $path );
		} // end foreach

	}

	/**
	 * @return void
	 * @since 1.2
	 */
	public function set_stylesheets( array $list ) {

		foreach( $list as &$value ) {

			if ( is_string( $value ) ) {
				$value = array( 'path' => $value );
			} // end if

			$value = array_merge( array(
				'path' => '',
				'ver' => '',
			), (array) $value );

			if ( ! file_exists( $value['path'] ) ) {
				return false;
			} // end if

		} // end foreach

		$this->stylesheets = $list;

		return true;

	}

	/**
	 * @return array
	 * @since 1.2
	 */
	public function get_stylesheets() {
		return $this->stylesheets;
	}

	/**
	 * @return void
	 * @since 1.2
	 */
	public function set_options( $args = '' ) {

		$this->options = array_merge( array(
			'content_type' => 'text/css',
			'charset' => 'utf-8',
		), (array) $args );

	}

	/**
	 * @return mixed
	 * @since 1.2
	 */
	public function get_option( $key, $default = '' ) {

		if ( isset( $this->options[ $key ] ) ){
			return $this->options[ $key ];
		} // end if

		return $default;

	}

	/**
	 * @return array
	 * @since 1.2
	 */
	public function get_options() {
		return $this->options;
	}


	/*** HTTP Headers *********************************************************/

	/**
	 * @return void
	 * @since 1.2
	 */
	protected function send_status_header( $code, $text ) {

		if ( empty( $code ) || empty( $text ) ) {
			return false;
		} // end if

		$protocol = $_SERVER['SERVER_PROTOCOL'];

		if ( 'HTTP/1.1' !== $protocol && 'HTTP/1.0' !== $protocol ) {
			$protocol = 'HTTP/1.0';
		} // end if

		return @header( "$protocol $code $text", true, (int) $code );

	}

	/**
	 * @return void
	 * @since 1.2
	 */
	protected function send_nocache_headers() {

		$headers = array(
			'Expires' => 'Wed, 11 Jan 1984 05:00:00 GMT',
			'Cache-Control' => 'no-cache, must-revalidate, max-age=0',
			'Last-Modified' => false,
			'Pragma' => 'no-cache',
		);

		foreach( $headers as $name => $value ) {

			if ( $value !== false ) {
				@header( "{$name}: {$value}" );

			} else {
				@header_remove( $name );

			} // end if

		} // end foreach

	}

	/**
	 * @return void
	 * @since 1.2
	 */
	protected function send_cache_headers() {

		header( 'Cache-Control: public,max-age=0' );

		$last_mtime = $this->get_stylesheets_last_mtime();

		if ( ! empty( $last_mtime ) ) {
			header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s \G\M\T', $last_mtime ) );
		}

		header( 'ETag: ' . md5( serialize( $this->get_stylesheets() ) ) );

	}

	/**
	 * @return void
	 * @since 1.2
	 */
	protected function send_content_headers() {

		$charset = $this->get_option( 'charset' );
		$content_type = $this->get_option( 'content_type' );

		if ( ! empty( $content_type ) && ! empty( $charset ) ) {
			header( "Content-Type: {$content_type}; charset={$charset}" );
		} // end if

	}


	/*** Helper Methods *******************************************************/

	/**
	 * @return array
	 * @since 1.2
	 */
	protected function list_pluck( array $list, $field ) {

		foreach ( $list as $key => $value ) {

			if ( is_object( $value ) ) {
				$list[ $key ] = $value->$field;
			} else {
				$list[ $key ] = $value[ $field ];
			} // end if

		} // end foreach

		return $list;

	}

	/**
	 * @return int|bool
	 * @since 1.2
	 */
	protected function get_stylesheets_last_mtime() {

		$last_mtime = 0;

		foreach( $this->list_pluck( $this->get_stylesheets(), 'path' ) as $path ) {

			$filemtime = filemtime( $path );

			if ( $filemtime > $last_mtime ) {
				$last_mtime = $filemtime;
			} // end if

		} // end foreach

		return $last_mtime;

	}

}