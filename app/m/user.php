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


	/*** Methods **************************************************************/

	/**
	 * @return void
	 * @since 0.1
	 */
	public function __construct( $data ) {

		if ( is_object( $data ) ) {
			$data = get_object_vars( $data );
		}

		if ( ! $data || ! is_array( $data ) ) {
			return;
		}

		foreach( $data as $key => $value ) {

			if ( property_exists( $this, $key ) ) {
				$this->$key = $value;
			}

		}

	}

	// Getters

	/**
	 * @return mixed
	 * @since 0.1
	 */
	public function display( $key, $format = 'html' ) {

		switch( $format ) {

			case 'attr':
				echo esc_attr( $this->get( $key ) );
				break;

			case 'html':
				echo esc_html( $this->get( $key ) );
				break;

			default:
				echo $this->get( $key );
				break;

		}

	}

	/**
	 * @return mixed
	 * @since 0.1
	 */
	public function get( $key ) {

		if ( isset( $this->$key ) ) {
			return $this->$key;
		}

	}

	/**
	 * @return bool
	 * @since 0.1
	 */
	public function is_exists() {
		return ! empty( $this->city_id );
	}

	/**
	 * @return array
	 * @since 0.1
	 */
	public function to_array() {
		return get_object_vars( $this );
	}

}