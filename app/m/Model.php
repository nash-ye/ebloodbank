<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
abstract class Model {

	/**
	 * @return void
	 * @since 0.4.2
	 */
	public function __construct( $data = array() ) {

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
	 * @since 0.4.2
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
	 * @since 0.4.2
	 */
	public function get( $key ) {

		if ( isset( $this->$key ) ) {
			return $this->$key;
		}

	}

	/**
	 * @return array
	 * @since 0.4.2
	 */
	public function to_array() {
		return get_object_vars( $this );
	}

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
	public function get( $meta_key, $single = TRUE ) {

		try {

			global $db;

			if ( $single ) {
				$meta_value = NULL;
			} else {
				$meta_value = array();
			}

			if ( ! $this->id || ! $meta_key ) {
				return $meta_value;
			}

			if ( ! isset( $this->meta[ $meta_key ] ) ) {

				if ( $single ) {

					$stmt = $db->prepare( 'SELECT meta_id, meta_value FROM ' . static::TABLE . ' WHERE ' . static::FK_ATTR . ' = ? AND meta_key = ?', array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL) );
					$stmt->execute( array( $this->id, $meta_key ) );

					$row = $stmt->fetch( \PDO::FETCH_ASSOC );
					$stmt->closeCursor();

					if ( $row ) {
						$this->meta[ $meta_key ][ $row['meta_id'] ] = $row['meta_value'];
					}

				} else {

					$stmt = $db->prepare( 'SELECT meta_id, meta_value FROM ' . static::TABLE . ' WHERE ' . static::FK_ATTR . ' = ? AND meta_key = ?', array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL) );
					$stmt->execute( array( $this->id, $meta_key ) );

					$rows = $stmt->fetchAll( \PDO::FETCH_ASSOC );
					$stmt->closeCursor();

					if ( $rows ) {

						foreach( $rows as $row ) {
							$this->meta[ $meta_key ][ $row['meta_id'] ] = $row['meta_value'];
						}

					}

				}

			}

			if ( isset( $this->meta[ $meta_key ] ) ) {

				if ( $single ) {
					$meta_value = reset( $this->meta[ $meta_key ] );
				} else {
					$meta_value = (array) $this->meta[ $meta_key ];
				}

			}

		} catch ( \Exception $ex ) {
			// TODO: Logging
		}

		return $meta_value;

	}

	/**
	 * @return void
	 * @since 0.1
	 */
	public function submit( $meta_key, $meta_value ) {

		if ( empty( $meta_value ) ) {

			$this->delete( $meta_key );

		} else {

			$old_value = $this->get( $meta_key );

			if ( is_null( $old_value ) ) {

				$this->insert( $meta_key, $meta_value );

			} else {

				$this->update( $meta_key, $meta_value );

			}

		}

	}

	/**
	 * @return int
	 * @since 0.1
	 */
	public function insert( $meta_key, $meta_value ) {

		try {

			global $db;

			if ( ! $this->id || ! $meta_key ) {
				return FALSE;
			}

			$data = array(
				static::FK_ATTR => $this->id,
				'meta_value'    => $meta_value,
				'meta_key'      => $meta_key,
			);

			$columns = implode( '`, `', array_keys( $data ) );
			$holders = implode( ', ',  array_fill( 0, count( $data ), '?' ) );

			$stmt = $db->prepare( "INSERT INTO " . static::TABLE . " (`$columns`) VALUES ($holders)" );
			$inserted = (bool) $stmt->execute( array_values( $data ) );
			$stmt = $stmt->closeCursor();

			return ( $inserted ) ? $db->lastInsertId() : 0;

		} catch( Exception $ex ) {
			return FALSE;
		}

	}

	/**
	 * @return bool
	 * @since 0.1
	 */
	public function update( $meta_key, $meta_value, $prev_value = NULL ) {

		try {

			global $db;

			$where_stmt = array();
			$stmt_params = array();

			if ( ! $this->id  || ! $meta_key ) {
				return FALSE;
			}

			$stmt_params[] = $meta_value;

			$where_stmt[] = 'meta_key = ?';
			$stmt_params[] = $meta_key;

			$where_stmt[] = static::FK_ATTR  . ' = ?';
			$stmt_params[] = $this->id;

			if ( ! is_null( $prev_value ) ) {

				$where_stmt[] = 'meta_value = ?';
				$stmt_params[] = $prev_value;

			}

			$where_stmt = implode( ' AND ', $where_stmt );
			$where_stmt = "WHERE {$where_stmt}";

			$stmt = $db->prepare( "UPDATE " . static::TABLE . " SET meta_value = ? {$where_stmt}" );
			$updated = (bool) $stmt->execute( $stmt_params );
			$stmt = $stmt->closeCursor();

			return $updated;

		} catch( Exception $ex ) {
			return FALSE;
		}

	}

	/**
	 * @return int
	 * @since 0.1
	 */
	public function delete( $meta_key, $meta_value = NULL ) {

		try {

			global $db;

			$where_stmt = array();
			$stmt_params = array();

			if ( ! $this->id  || ! $meta_key ) {
				return FALSE;
			}

			$where_stmt[] = 'meta_key = ?';
			$stmt_params[] = $meta_key;

			$where_stmt[] = static::FK_ATTR  . ' = ?';
			$stmt_params[] = $this->id;

			if ( ! is_null( $meta_value ) ) {

				$where_stmt[] = 'meta_value = ?';
				$stmt_params[] = $meta_value;

			}

			$where_stmt = implode( ' AND ', $where_stmt );
			$where_stmt = "WHERE {$where_stmt}";

			$stmt = $db->prepare( "DELETE FROM " . static::TABLE . " $where_stmt" );
			$deleted = (bool) $stmt->execute( $stmt_params );
			$stmt = $stmt->closeCursor();

			return $deleted;

		} catch( \Exception $ex ) {
			return FALSE;
		}

	}

}