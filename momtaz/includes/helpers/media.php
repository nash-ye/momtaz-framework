<?php

/**
 * A utility class to crop/resize the images on-the-fly.
 *
 * @since 1.1
 */
class Momtaz_Image_Clipper {

	/*** Properties ***********************************************************/

	// The image:

	/**
	 * The image path.
	 *
	 * @var string
	 * @since 1.1
	 */
	protected $image_path;

	// The clip:

	/**
	 * The clip width.
	 *
	 * @var int
	 * @since 1.1
	 */
	protected $clip_width;

	/**
	 * The clip height.
	 *
	 * @var int
	 * @since 1.1
	 */
	protected $clip_height;

	/**
	 * The clip file name.
	 *
	 * @var string
	 * @since 1.1
	 */
	protected $clip_filename;

	/**
	 * The clip directory path.
	 *
	 * @var string
	 * @since 1.1
	 */
	protected $clip_directory;

	/**
	 * The clip options.
	 *
	 * @var array
	 * @since 1.1
	 */
	protected $clip_options = array();


	/*** Methods **************************************************************/

	// Getters

	/**
	 * Get the image path.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function get_image_path() {
		return $this->image_path;
	}

	/**
	 * Get the clip width.
	 *
	 * @return int
	 * @since 1.1
	 */
	public function get_clip_width() {
		return (int) $this->clip_width;
	}

	/**
	 * Get the clip height.
	 *
	 * @return int
	 * @since 1.1
	 */
	public function get_clip_height() {
		return (int) $this->clip_height;
	}

	/**
	 * Get the clip options.
	 *
	 * @return array
	 * @since 1.1
	 */
	public function get_clip_options() {
		return (array) $this->clip_options;
	}

	/**
	 * Get a clip option value based on name of option.
	 *
	 * @return mixed
	 * @since 1.1
	 */
	public function get_clip_option( $key, $default = '' ) {

		$options = $this->get_clip_options();

		if ( isset( $options[ $key ] ) ) {
			return $options[ $key ];
		}

		return $default;

	}

	/**
	 * Get the clip directory path.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function get_clip_directory() {

		if ( empty( $this->clip_directory ) ) {

			$img_path = $this->get_image_path();

			if ( ! empty( $img_path ) ) {
				return dirname( $img_path );
			}

		}

		return $this->clip_directory;

	}

	/**
	 * Get the clip file name.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function get_clip_filename() {

		if ( empty( $this->clip_filename ) ) {

			$img_path = $this->get_image_path();

			if ( ! empty( $img_path ) ) {

				// Get the clip width.
				$width = $this->get_clip_width();

				// Get the clip height.
				$height = $this->get_clip_height();

				// Return the hashed file name.
				return md5( $img_path . $width . $height );

			}

		}

		return $this->clip_filename;

	}

	/**
	 * Get the clip absolute file path.
	 *
	 * @return string
	 * @since 1.1
	 */
	public function get_clip_filepath() {

		// Get the image path.
		$img_path = $this->get_image_path();

		if ( empty( $img_path ) ) {
			return;
		}

		// Get the clip directory.
		$dir = $this->get_clip_directory();

		// Set the clip file name.
		$name = $this->get_clip_filename();

		// Get the image file extension.
		$extension = pathinfo( $img_path, PATHINFO_EXTENSION );

		// Return the destination file path.
		return trailingslashit( $dir ) . "{$name}.{$extension}";

	}

	// Setters

	/**
	 * Set the image path.
	 *
	 * @return Momtaz_Image_Clipper
	 * @since 1.1
	 */
	public function set_image_path( $path ) {

		if ( momtaz_is_self_hosted_url( $path ) ) {

			// Parse the URL and return the possible path.
			$path = ltrim( parse_url( $path, PHP_URL_PATH ), '/' );
			$path = path_join( $_SERVER['DOCUMENT_ROOT'], $path );

		}

		if ( file_exists( $path ) ) {
			$this->image_path = $path;
		}

		return $this;

	}

	/**
	 * Set the clip width.
	 *
	 * @return Momtaz_Image_Clipper
	 * @since 1.1
	 */
	public function set_clip_width( $width ) {
		$this->clip_width = abs( (int) $width );
		return $this;
	}

	/**
	 * Set the clip height.
	 *
	 * @return Momtaz_Image_Clipper
	 * @since 1.1
	 */
	public function set_clip_height( $height ) {
		$this->clip_height = abs( (int) $height );
		return $this;
	}

	/**
	 * Set the clip directory path.
	 *
	 * @return Momtaz_Image_Clipper
	 * @since 1.1
	 */
	public function set_clip_directory( $path ) {

		if ( is_dir( $path ) ) {
			$this->clip_directory = $path;
		}

		return $this;

	}

	/**
	 * Set the clip file name.
	 *
	 * @return Momtaz_Image_Clipper
	 * @since 1.1
	 */
	public function set_clip_filename( $filename ) {
		$this->clip_filename = $filename;
		return $this;
	}

	/**
	 * Set the clip options list.
	 *
	 * @return Momtaz_Image_Clipper
	 * @since 1.1
	 */
	public function set_clip_options( array $options ) {
		$this->clip_options = $options;
		return $this;
	}

	/**
	 * Set a clip option value.
	 *
	 * @return Momtaz_Image_Clipper
	 * @since 1.1
	 */
	public function set_clip_option( $key, $value ) {
		$this->clip_options[ $key ] = $value;
		return $this;
	}

	// Processing

	/**
	 * Locate the point to crop the source image from it.
	 *
	 * @access private
	 * @return array
	 * @since 1.1
	 */
	protected function locate_the_point( $img_w, $img_h ) {

		$src = array (
			'w' => $img_w,
			'h' => $img_h,
			'x' => 0,
			'y' => 0,
		);

		// Get the clip properties.
		$width = $this->get_clip_width();
		$height = $this->get_clip_height();

		if ( $this->get_clip_option( 'c', true ) ) {

			$cmp_x = $img_w / $width;
			$cmp_y = $img_h / $height;

			// Calculate x or y coordinate, and width or height of source
			if ( $cmp_x > $cmp_y ) {

				$src['w'] = round( $img_w / $cmp_x * $cmp_y );
				$src['x'] = round( ( $img_w - ( $img_w / $cmp_x * $cmp_y ) ) / 2 );

			} elseif ( $cmp_y > $cmp_x ) {

				$src['h'] = round( $img_h / $cmp_y * $cmp_x );
				$src['y'] = round( ( $img_h - ( $img_h / $cmp_y * $cmp_x ) ) / 2 );

			}

			$align = $this->get_clip_option( 'a', 'c' );

			if ( ! empty( $align ) ) {

				if ( strpos( $align, 't' ) !== false ) {
					$src['y'] = 0;
				}

				if ( strpos( $align, 'b' ) !== false ) {
					$src['y'] = $img_h - $src['h'];
				}

				if ( strpos( $align, 'l' ) !== false ) {
					$src['x'] = 0;
				}

				if ( strpos( $align, 'r' ) !== false ) {
					$src['x'] = $img_w - $src['w'];
				}

			}

		}

		return $src;

	}

	/**
	 * Resize images dynamically using WP built-in functions.
	 *
	 * @return array|bool
	 * @since 1.1
	 */
	public function save_the_clip() {

		// Get the clip size.
		$width = $this->get_clip_width();
		$height = $this->get_clip_height();

		if ( ! $width || ! $height ) {
			return false;
		}

		$img_path = $this->get_image_path();

		if ( ! $img_path ) {
			return false;
		}

		// Get the clip file path.
		$clip_path = $this->get_clip_filepath();

		if ( ! $clip_path ) {
			return false;
		}

		if ( ! file_exists( $clip_path ) ) {

			// Get the WP_Image_Editor instance.
			$img_editor = wp_get_image_editor( $this->get_image_path() );

			if ( is_wp_error( $img_editor ) ) {
				return $img_editor;
			}

			// Get the original image size
			$image_size = $img_editor->get_size();

			// Set the clip compression quality.
			$img_editor->set_quality( $this->get_clip_option( 'q', 100 ) );

			// Locate the point to crop the image from it.
			$src = $this->locate_the_point( $image_size['width'], $image_size['height'] );

			// Time to crop the image!
			$img_editor->crop( $src['x'], $src['y'], $src['w'], $src['h'], $width, $height );

			// Now let's save the image.
			$saved = $img_editor->save( $clip_path );

			if ( is_wp_error( $saved ) || ! is_array( $saved ) ) {
				return false;
			}

			$dest_img = array(
				'path' => $saved['path'],
				'width' => $saved['width'],
				'height' => $saved['height'],
			);

		} else {

			$dest_img = array(
				'width' => $width,
				'height' => $height,
				'path' => $clip_path,
			);

		}

		return $dest_img;

	}

}

/**
 * Resize images dynamically using wp built-in functions.
 *
 * @return array|WP_Error
 * @since 1.0
 */
function momtaz_resize_image( $image_path, $width, $height, $crop = true ) {

	// Create the clipper object.
	$clipper = new Momtaz_Image_Clipper();

	// Set the clipper properties and save the file.
	$the_clip = $clipper->set_clip_directory( MOMTAZ_CACHE_DIR )
						->set_clip_option( 'c', (bool) $crop )
						->set_image_path( $image_path )
						->set_clip_height( $height )
						->set_clip_width( $width )
						->save_the_clip();

	if ( is_array( $the_clip ) && ! empty( $the_clip['path'] ) ) {

		// Add the clip image URL if it saved successfuly.
		$the_clip['url'] = trailingslashit( MOMTAZ_CACHE_URI );
		$the_clip['url'] .= wp_basename( $the_clip['path'] );

	}

	return $the_clip;

}