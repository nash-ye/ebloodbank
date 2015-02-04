<?php

namespace eBloodBank;

/**
* @since 0.5.4
*/
final class Stocks extends Models {

	/**
	 * @access private
	 * @return void
	 * @since 0.5.4
	 */
	private function __construct() {}

	/**
	 * @return eBloodBank\Stock[]
	 * @since 0.5.4
	 */
	public static function fetch_all() {

		return self::fetch_multi( 'SELECT * FROM stock' );

	}

	/**
	 * @return eBloodBank\Stock
	 * @since 0.5.4
	 */
	public static function fetch_by_ID( $id ) {

		$id = (int) $id;

		if ( empty( $id ) ) {
			return FALSE;
		}

		return self::fetch_single( 'SELECT * FROM stock WHERE stock_id = ?', array( $id ) );

	}

	/**
	 * @return eBloodBank\Stock[]
	 * @since 0.5.4
	 */
	public static function fetch_multi( $sql, array $params = array() ) {

		try {

			global $db;

			$stmt = $db->prepare( $sql, array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL) );
			$stmt->execute( $params );

			$results = $stmt->fetchAll( \PDO::FETCH_CLASS, 'eBloodBank\Stock' );
			$stmt->closeCursor();

			return $results;

		} catch ( \PDOException $ex ) {
			return FALSE;
		}

	}

	/**
	 * @return eBloodBank\Stock
	 * @since 0.5.4
	 */
	public static function fetch_single( $sql, array $params = array() ) {

		try {

			global $db;

			$stmt = $db->prepare( $sql, array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL) );
			$stmt->execute( $params );

			$results = $stmt->fetchObject( 'eBloodBank\Stock' );
			$stmt->closeCursor();

			return $results;

		} catch ( \PDOException $ex ) {
			return FALSE;
		}

	}

	/**
	 * @return bool
	 * @since 0.5.4
	 */
	public static function update( $id, array $data ) {

		try {

			global $db;

			$id = (int) $id;

			if ( empty( $id ) ) {
				return FALSE;
			}

			$params = array();
			$set_stmt = array();

			foreach( $data as $key => $value ) {
				$set_stmt[] = "`$key` = ?";
				$params[] = $value;
			}

			array_push( $params,  $id );

			$set_stmt = implode( ', ', $set_stmt );
			$set_stmt = "SET $set_stmt";

			$stmt = $db->prepare( "UPDATE stock $set_stmt WHERE stock_id = ?" );
			$updated = (bool) $stmt->execute( $params );
			$stmt = $stmt->closeCursor();

			return $updated;

		} catch( \PDOException $ex ) {
			return FALSE;
		}

	}

	/**
	 * @return int
	 * @since 0.5.4
	 */
	public static function insert( array $data ) {

		try {

			global $db;

			$id = 0;
			$columns = implode( '`, `', array_keys( $data ) );
			$holders = implode( ', ',  array_fill( 0, count( $data ), '?' ) );

			$stmt = $db->prepare( "INSERT INTO stock (`$columns`) VALUES ($holders)" );
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
	 * @since 0.5.4
	 */
	public static function delete( $id ) {

		try {

			global $db;

			$id = (int) $id;

			if ( empty( $id ) ) {
				return FALSE;
			}

			$stmt = $db->prepare( "DELETE FROM stock WHERE stock_id = ?" );
			$deleted = (bool) $stmt->execute( array( $id ) );
			$stmt = $stmt->closeCursor();

			return $deleted;

		} catch( \PDOException $ex ) {
			return FALSE;
		}

	}

}
