<?php

namespace eBloodBank;

/**
* @since 0.1
*/
final class Users {

	/**
	 * @access private
	 * @return void
	 * @since 0.1
	 */
	private function __construct() {}

	/**
	 * @return eBloodBank\User[]
	 * @since 0.1
	 */
	public static function fetch_all() {

		$sql = 'SELECT * FROM user';
		return self::fetch_multi( $sql );

	}

	/**
	 * @return eBloodBank\User
	 * @since 0.1
	 */
	public static function fetch_by_ID( $id ) {

		try {

			$id = (int) $id;

			if ( empty( $id ) ) {
				return FALSE;
			}

			$sql = 'SELECT * FROM user WHERE user_id = ?';
			return self::fetch_single( $sql, array( $id ) );

		} catch ( \PDOException $ex ) {
			return FALSE;
		}

	}

	/**
	 * @return eBloodBank\User[]
	 * @since 0.1
	 */
	public static function fetch_multi( $sql, array $params = array() ) {

		try {

			global $db;

			$stmt = $db->prepare( $sql, array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL) );
			$stmt->execute( $params );

			$results = $stmt->fetchAll( \PDO::FETCH_CLASS, 'eBloodBank\User' );
			$stmt->closeCursor();

			return $results;

		} catch ( \PDOException $ex ) {
			return FALSE;
		}

	}

	/**
	 * @return eBloodBank\User
	 * @since 0.1
	 */
	public static function fetch_single( $sql, array $params = array() ) {

		try {

			global $db;

			$stmt = $db->prepare( $sql, array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL) );
			$stmt->execute( $params );

			$results = $stmt->fetchObject( 'eBloodBank\User' );
			$stmt->closeCursor();

			return $results;

		} catch ( \PDOException $ex ) {
			return FALSE;
		}

	}

	/**
	 * @return bool
	 * @since 0.1
	 */
	public static function update( $id, array $data ) {

		try {

			global $db;

			$id = (int) $id;

			if ( empty( $id ) ) {
				return FALSE;
			}

			$columns = array();
			foreach( array_keys( $data ) as $key ) {
				$columns[] = "{$key}=?";
			}
			$columns = implode( ', ', $columns );

			$stmt = $db->prepare( "UPDATE user SET {$columns} WHERE user_id = {$id}" );
			$updated = (bool) $stmt->execute( array_values( $data ) );
			$stmt = $stmt->closeCursor();

			return $updated;

		} catch( \PDOException $ex ) {
			return FALSE;
		}

	}

	/**
	 * @return int
	 * @since 0.1
	 */
	public static function insert( array $data ) {

		try {

			global $db;

			$id = 0;

			$data = array_merge( array(
				'user_status' => 0,
			), $data );

			$columns = implode( '`, `', array_keys( $data ) );
			$holders = implode( ', ',  array_fill( 0, count( $data ), '?' ) );

			$stmt = $db->prepare( "INSERT INTO user (`$columns`) VALUES ($holders)" );
			$inserted = (bool) $stmt->execute( array_values( $data ) );
			$stmt = $stmt->closeCursor();

			if ( $inserted ) {
				$id = $db->lastInsertId();
			}

			return $id;

		} catch( \PDOException $ex ) {
			return FALSE;
		}

	}

	/**
	 * @return bool
	 * @since 0.1
	 */
	public static function delete( $id ) {

		try {

			global $db;

			$id = (int) $id;

			if ( empty( $id ) ) {
				return FALSE;
			}

			$stmt = $db->prepare( "DELETE FROM user WHERE user_id = $id" );
			$deleted = (bool) $stmt->execute();
			$stmt = $stmt->closeCursor();

			return $deleted;

		} catch( \PDOException $ex ) {
			return FALSE;
		}

	}

}
