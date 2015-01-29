<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
class User extends Model {

	/**
	 * @var int
	 * @since 0.1
	 */
	protected $user_id = 0;

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $user_logon;

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $user_pass;

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $user_rtime;

	/**
	 * @var string
	 * @since 0.1
	 */
	protected $user_role;

	/**
	 * @var int
	 * @since 0.1
	 */
	protected $user_status = 0;

	/**
	 * @return Role
	 * @since 0.4.3
	 */
	public function get_role() {
		return Roles::get_role( $this->get( 'user_role' ) );
	}

	/**
	 * @return bool
	 * @since 0.4.3
	 */
	public function has_caps( array $caps, $opt = 'AND' ) {

		$role = $this->get_role();

		if ( empty( $role ) ) {
			return FALSE;
		}

		return $role->has_caps( $caps, $opt );

	}

	/**
	 * @return bool
	 * @since 0.4.3
	 */
	public function has_cap( $cap ) {

		$role = $this->get_role();

		if ( empty( $role ) ) {
			return FALSE;
		}

		return $role->has_cap( $cap );

	}

}

/**
 * @since 0.4.2
 */
class User_Meta extends Model_Meta {

	/**
	 * @var string
	 * @since 0.4.2
	 */
	const TABLE = 'user_meta';

	/**
	 * @var string
	 * @since 0.4.2
	 */
	const FK_ATTR = 'user_id';

}