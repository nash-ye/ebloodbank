<?php

namespace eBloodBank;

/**
 * @return bool
 * @since 0.1
 */
function is_vaild_ID( $id ) {
	$id = (int) $id;
	return ( $id > 0 );
}

/**
 * @return void
 * @since 0.1
 */
function redirect( $location ) {
	header( "Location: $location" );
	die();
}

/*** URLs Helpers *************************************************************/

/**
 * @return bool
 * @since 0.4.3
 */
function is_https() {
	return ! empty( $_SERVER['HTTPS'] );
}

/**
 * @return void
 * @since 0.4.3
 */
function base_url() {
	echo get_base_url();
}

/**
 * @return string
 * @since 0.4.3
 */
function get_base_url() {

	if ( is_https() ) {
		$url = 'https://';
	} else {
		$url = 'http://';
	}

	$url .= $_SERVER['SERVER_NAME'];

	return $url;

}

/**
 * @return void
 * @since 0.4.3
 */
function current_url() {
	echo get_current_url();
}

/**
 * @return string
 * @since 0.4.3
 */
function get_current_url() {
	return get_base_url() . $_SERVER['REQUEST_URI'];
}

/**
 * @return void
 * @since 0.4.3
 */
function site_url( array $query = array() ) {
	echo get_site_url( $query );
}

/**
 * @return string
 * @since 0.4.3
 */
function get_site_url( array $query = array() ) {

	static $url = NULL;

	if ( is_null( $url ) ) {
		$root = str_replace( '\\', '/', realpath( $_SERVER['DOCUMENT_ROOT'] ) );
		$url = str_replace( $root, get_base_url(), str_replace( '\\', '/', DIR ) );
	}

	if ( ! empty( $query ) ) {
		return "{$url}/?" . http_build_query( $query );
	}

	return $url;

}

/*** HTML Helpers *************************************************************/

/**
 * Output HTML attributes list.
 *
 * @return void
 * @since 0.6.1
 */
function html_atts( array $atts, array $args = null ) {
	echo get_html_atts( $atts, $args );
}

/**
 * Convert an associative array to HTML attributes list.
 *
 * Convert an associative array of attributes and their values 'attribute => value' To
 * an inline list of HTML attributes.
 *
 * @return string
 * @since 0.6.1
 */
function get_html_atts( array $atts, array $args = null ) {

	$output = '';

	if ( empty( $atts ) ) {
		return $output;
	}

	$args = array_merge( array(
		'after' => '',
		'before' => ' ',
	), (array) $args );

	foreach ( $atts as $key => $value ) {

		$key = strtolower( $key );

		 if ( is_bool( $value ) ) {

			if ( $value === TRUE ) {
				$value = $key;
			} else {
				continue;
			}

		 } elseif ( is_array( $value ) ) {

			$value = array_filter( $value );
			$value = implode( ' ', $value );

		 }

		$output .= $key . '="' . esc_attr( $value ) . '" ';

	}

	if ( ! empty( $output ) ) {
		$output = $args['before'] . trim( $output ) . $args['after'];
	}

	return $output;

}

/**
 * @return string
 * @since 0.1
 */
function esc_attr( $data, $encoding = 'UTF-8' ) {
	return htmlspecialchars( $data, ENT_QUOTES, $encoding );
}

/**
 * @return string
 * @since 0.1
 */
function esc_html( $data, $encoding = 'UTF-8' ) {
	return htmlspecialchars( $data, ENT_HTML5, $encoding );
}
