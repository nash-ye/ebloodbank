<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
class User extends Model {

	use Model_Meta;

	/**
	 * @var string
	 * @since 0.4.4
	 */
	const TABLE = 'user';

	/**
	 * @var string
	 * @since 0.4.4
	 */
	const PK_ATTR = 'user_id';

	/**
	 * @var string
	 * @since 0.4.4
	 */
	const META_TABLE = 'user_meta';

	/**
	 * @var string
	 * @since 0.4.4
	 */
	const META_FK_ATTR = 'user_id';

	/**
	 * @var int
	 * @since 0.1
	 */
	public $user_id = 0;

	/**
	 * @var string
	 * @since 0.1
	 */
	public $user_logon;

	/**
	 * @var string
	 * @since 0.1
	 */
	public $user_pass;

	/**
	 * @var string
	 * @since 0.1
	 */
	public $user_rtime;

	/**
	 * @var string
	 * @since 0.1
	 */
	public $user_role = 'default';

	/**
	 * @var string
	 * @since 0.1
	 */
	public $user_status = 'activated';

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