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

/**
 * @return array
 * @since 0.1
 */
function get_blood_groups() {

	return array(
		'-O',
		'+O',
		'-A',
		'+A',
		'-B',
		'+B',
		'-AB',
		'+AB',
	);

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
