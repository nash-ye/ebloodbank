<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
abstract class Model {
}

/**
 * @since 0.1
 */
abstract class Model_Meta {

	/*** Variables ************************************************************/

	/**
	 * @var int
	 * @since 0.1
	 */
	protected $id = 0;

	/**
	 * @var array
	 * @since 0.1
	 */
	protected $meta = array();


	/*** Methods **************************************************************/

	/**
	 * @return void
	 * @since 0.1
	 */
	public function __construct( $id ) {
		$this->id = (int) $id;
	}

	/**
	 * @return mixed
	 * @since 0.1
	 */
	public function get( $m_key, $single = true ) {

		if ( $single ) {
			$m_value = NULL;
		} else {
			$m_value = array();
		}

		try {

			global $db;

			if ( ! $this->id || ! $m_key ) {
				return $m_value;
			}

			if ( ! isset( $this->meta[ $m_key ] ) ) {

				if ( $single ) {

					$row = $db->fetchAssoc( 'SELECT m_id, m_value FROM ' . static::TABLE . ' WHERE ' . static::FK_ATTR . ' = ? AND m_key = ?', array( $this->id, $m_key ) );

					if ( $row ) {
						$this->meta[ $m_key ][ $row['m_id'] ] = $row['m_value'];
					}

				} else {

					$rows = $db->fetchAll( 'SELECT m_id, m_value FROM ' . static::TABLE . ' WHERE ' . static::FK_ATTR . ' = ? AND m_key = ?', array( $this->id, $m_key ) );

					if ( $rows ) {

						foreach( $rows as $row ) {
							$this->meta[ $m_key ][ $row['m_id'] ] = $row['m_value'];
						}

					}

				}

			}

			if ( isset( $this->meta[ $m_key ] ) ) {

				if ( $single ) {
					$m_value = reset( $this->meta[ $m_key ] );
				} else {
					$m_value = (array) $this->meta[ $m_key ];
				}

			}

		} catch ( \Exception $ex ) {
			// TODO: Logging
		}

		return $m_value;

	}

	/**
	 * @return void
	 * @since 0.1
	 */
	public function submit( $m_key, $m_value ) {

		if ( empty( $m_value ) ) {

			$this->delete( $m_key );

		} else {

			$old_value = $this->get( $m_key );

			if ( is_null( $old_value ) ) {

				$this->insert( $m_key, $m_value );

			} else {

				$this->update( $m_key, $m_value );

			}

		}

	}

	/**
	 * @return int
	 * @since 0.1
	 */
	public function insert( $m_key, $m_value ) {

		try {

			global $db;

			$m_key = (string) $m_key;

			if ( ! $this->id || ! $m_key ) {
				return FALSE;
			}

			$m_value = $this->prepare_value( $m_key, $m_value );

			return $db->insert( static::TABLE, array(
				static::FK_ATTR => $this->id,
				'm_value' => $m_value,
				'm_key' => $m_key,
			) );

		} catch( Exception $ex ) {
			return FALSE;
		}

	}

	/**
	 * @return bool
	 * @since 0.1
	 */
	public function update( $m_key, $m_value, $prev_value = NULL ) {

		try {

			global $db;

			$m_key = (string) $m_key;

			if ( ! $this->id  || ! $m_key ) {
				return FALSE;
			}

			$where = array(
				'm_key' => $m_key,
				static::FK_ATTR => $this->id,
			);

			if ( ! is_null( $prev_value ) ) {
				$where['m_value'] = $prev_value;
			}

			return $db->update( static::TABLE, array( 'm_value' => $m_value ), $where );

		} catch( Exception $ex ) {
			return FALSE;
		}

	}

	/**
	 * @return int
	 * @since 0.1
	 */
	public function delete( $m_key, $m_value = NULL ) {

		try {

			global $db;

			$m_key = (string) $m_key;

			if ( ! $this->id || ! $m_key ) {
				return FALSE;
			}

			$where = array(
				'm_key' => $m_key,
				static::FK_ATTR => $this->id,
			);

			if ( ! is_null( $m_value ) ) {
				$where['m_value'] = $m_value;
			}

			return $db->delete( static::TABLE, $where );

		} catch( \Exception $ex ) {
			return FALSE;
		}

	}

}