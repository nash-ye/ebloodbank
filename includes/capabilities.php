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

		if ( self::role_exists( $role->slug ) ) {
			return FALSE;
		}

		self::$roles[ $role->slug ] = $role;
		return TRUE;

	}

	/**
	 * @return bool
	 * @since 0.4.1
	 */
	public static function remove_role( $slug ) {

		if ( ! self::role_exists( $slug ) ) {
			return FALSE;
		}

		unset( self::$roles[ $slug ] );
		return TRUE;

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

	if ( empty( $current_user ) ) {
		return FALSE;
	}

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

/**
 * @return bool
 * @since 0.4.8
 */
function anonymous_can( $caps, $opt = 'AND' ) {

	if ( empty( $caps ) ) {
		return FALSE;
	}

	$role = new Role( 'anonymous', 'مجهول', array(

		// Users
		'view_user'         => TRUE,

		// Donors
		'add_donor'         => TRUE,
		'view_donor'        => TRUE,

		// Cites
		'view_city'         => TRUE,

		// Districts
		'view_distr'        => TRUE,

		// Tests
		'view_bank'         => TRUE,

		// Stocks
		'view_stock'        => TRUE,

	) );

	return $role->has_caps( (array) $caps, $opt );

}

/*** Settings *****************************************************************/

Roles::add_role( new Role( 'administrator', 'مدير', array(

	// Users
	'add_user'              => TRUE,
	'edit_user'             => TRUE,
	'view_user'             => TRUE,
	'delete_user'           => TRUE,
	'manage_users'          => TRUE,

	// Donors
	'add_donor'             => TRUE,
	'edit_donor'            => TRUE,
	'view_donor'            => TRUE,
	'delete_donor'          => TRUE,
	'approve_donor'         => TRUE,
	'manage_donors'         => TRUE,

	// Cites
	'add_city'              => TRUE,
	'edit_city'             => TRUE,
	'view_city'             => TRUE,
	'delete_city'           => TRUE,
	'manage_cites'          => TRUE,

	// Districts
	'add_distr'             => TRUE,
	'edit_distr'            => TRUE,
	'view_distr'            => TRUE,
	'delete_distr'          => TRUE,
	'manage_distrs'         => TRUE,

	// Banks
	'add_bank'              => TRUE,
	'edit_bank'             => TRUE,
	'view_bank'             => TRUE,
	'delete_bank'           => TRUE,
	'approve_bank'          => TRUE,
	'manage_banks'          => TRUE,

	// Stocks
	'add_stock'             => TRUE,
	'edit_stock'            => TRUE,
	'view_stock'            => TRUE,
	'delete_stock'          => TRUE,
	'manage_stocks'         => TRUE,

) ) );