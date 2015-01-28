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
