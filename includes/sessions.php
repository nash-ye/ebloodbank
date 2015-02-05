<?php

namespace eBloodBank;

/**
 * @since 0.4
 */
final class Sessions {

	/**
	 * @var eBloodBank\User
	 * @since 0.4
	 */
	private static $current_user;

	/**
	 * @since 0.4
	 */
	private function __construct() {}

	/**
	 * @return bool
	 * @since 0.4
	 */
	public static function is_signed_in() {
		return ( ! empty( $_SESSION['signed'] ) );
	}

	/**
	 * @return eBloodBank\User
	 * @since 0.4
	 */
	public static function get_current_user() {

		if ( is_null( self::$current_user ) ) {

			if ( self::is_signed_in() ) {

				$user_id = (int) $_SESSION['user_id'];
				$user = Users::fetch_by_ID( $user_id );

				if ( ! empty( $user ) ) {
					self::$current_user = $user;
				}

			}

		}

		return self::$current_user;

	}

	/**
	 * @return int
	 * @since 0.4
	 */
	public static function get_current_user_ID() {
		return self::get_current_user()->get_ID();
	}

	/**
	 * @return bool
	 * @since 0.4
	 */
	public static function signin( $user_logon, $user_pass ) {

		if ( self::is_signed_in() ) {
			return FALSE;
		}

		if ( ! $user_logon || ! $user_pass ) {
			return FALSE;
		}

		$user = Users::fetch_by_logon( $user_logon );

		if ( empty( $user ) ) {
			return FALSE;
		}

		if ( ! password_verify( $user_pass, $user->get( 'user_pass' ) ) ) {
			return FALSE;
		}

		if ( session_status() !== PHP_SESSION_ACTIVE ) {
			session_start();
		}

		$_SESSION['user_id'] = $user->get_ID();
		$_SESSION['signin_time'] = time();
		$_SESSION['signed'] = TRUE;

		session_regenerate_id( TRUE );

		return TRUE;

	}

	/**
	 * @return void
	 * @since 0.4
	 */
	public static function signout() {

		if ( self::is_signed_in() ) {
			$_SESSION = array();
			return session_destroy();
		}

		return TRUE;

	}

}