<?php
/**
 * Get size information for all currently-registered image sizes.
 *
 * @return array $sizes Data for all currently-registered image sizes.
 * @uses   get_intermediate_image_sizes()
 * @global $_wp_additional_image_sizes
 */

if ( ! function_exists( 'get_image_sizes' ) ) {
	function get_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
				$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
				$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
				$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}

		return $sizes;
	}
}

/**
 * Get size information for a specific image size.
 *
 * @param string $size The image size for which to retrieve data.
 *
 * @return bool|array $size Size data about an image size or false if the size doesn't exist.
 * @uses   get_image_sizes()
 *
 */
if ( ! function_exists( 'get_image_size' ) ) {
	function get_image_size( $size ) {
		$sizes = get_image_sizes();

		if ( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		}

		return false;
	}
}