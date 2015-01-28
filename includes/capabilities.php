<?php

namespace eBloodBank;

/*** Roles ********************************************************************/

/**
 * @since 0.4.1
 */
class Role {

    /*** Properties ***********************************************************/

	/**
	 * @var string
	 * @since 0.4.1
	 */
	public $slug;

	/**
	 * @var string
	 * @since 0.4.1
	 */
	public $title;

	/**
	 * @var array
	 * @since 0.4.1
	 */
	public $caps = array();


	/*** Methods **************************************************************/

	/**
	 * @return void
	 * @since 0.4.1
	 */
	public function __construct( $slug, $title, array $caps ) {
		$this->slug    = $slug;
		$this->title   = $title;
		$this->caps    = $caps;
	}

	/**
	 * @return bool
	 * @since 0.4.1
	 */
	public function has_caps( array $caps, $opt = 'AND' ) {

		if ( empty( $opt ) ) {
			$opt = 'AND';
		}

		$opt = strtoupper( $opt );

		foreach( $caps as $cap ) {

			switch( $opt ) {

				case 'AND':

					if ( ! $this->has_cap( $cap ) ) {
						return FALSE;
					}

					break;

				case 'OR':

					if ( $this->has_cap( $cap ) ) {
						return TRUE;
					}

					break;

				CASE 'NOT':

					if ( $this->has_cap( $cap ) ) {
						return FALSE;
					}

					break;

			}

		}

		switch( $opt ) {

			case 'AND':
			case 'NOT':
				return TRUE;

			case 'OR':
				return FALSE;

		}

	}

	/**
	 * @return bool
	 * @since 0.4.1
	 */
	public function has_cap( $cap ) {
		return ( ! empty( $this->caps[ $cap ] ) );
	}

}

/**
 * @since 0.4.1
 */
final class Roles {

    /*** Properties ***********************************************************/

	/**
	 * @var Role[]
	 * @since 0.4.1
	 */
	private static $roles = array();


    /*** Methods **************************************************************/

	/**
	 * @return bool
	 * @since 0.4.1
	 */
	public static function add_role( Role $role ) {

		if ( isset( self::$roles[ $role->slug ] ) ) {
			return false;
		}

		self::$roles[ $role->slug ] = $role;
		return true;

	}

	/**
	 * @return bool
	 * @since 0.4.1
	 */
	public static function remove_role( $slug ) {

		if ( ! isset( self::$roles[ $slug ] ) ) {
			return false;
		}

		unset( self::$roles[ $slug ] );
		return true;

	}

	/**
	 * @return bool
	 * @since 0.4.1
	 */
	public static function role_exists( $slug ) {
		return isset( self::$roles[ $slug ] );
	}

	/**
	 * @return Role
	 * @since 0.4.1
	 */
	public static function get_role( $slug ) {

		if ( self::role_exists( $slug ) ) {
			return self::$roles[ $slug ];
		}

	}

	/**
	 * @return Role[]
	 * @since 0.4.1
	 */
	public static function get_roles() {
		return self::$roles;
	}

}

/*** Helpers ******************************************************************/

/**
 * @return bool
 * @since 0.4.1
 */
function current_user_can( $caps, $opt = 'AND' ) {

	if ( empty( $caps ) || ! Sessions::is_signed_in() ) {
		return FALSE;
	}

	$current_user = Sessions::get_current_user();
	return $current_user->has_caps( (array) $caps, $opt );

}

/**
 * @return bool
 * @since 0.4.1
 */
function user_can( $user_id, $caps, $opt = 'AND' ) {

	if ( empty( $caps ) ) {
		return FALSE;
	}

	$user = Users::fetch_by_ID( $user_id );

	if ( empty( $user ) ) {
		return FALSE;
	}

	return $user->has_caps( (array) $caps, $opt );

}

/*** Settings *****************************************************************/

Roles::add_role( new Role( 'administrator', 'مدير', array(

	// User
	'add_user'          => TRUE,
	'del_user'          => TRUE,
	'edit_user'         => TRUE,
	'view_user'         => TRUE,
	'manage_user'       => TRUE,

) ) );