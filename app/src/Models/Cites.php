<?php
namespace eBloodBank\Models;

/**
* @since 1.0
*/
class Cites
{
    /**
     * @access private
     * @return void
     * @since 1.0
     */
    private function __construct()
    {
    }

    /**
     * @return Generator
     * @since 1.0
     */
    public static function fetchAll()
    {
        return self::fetchEach('SELECT * FROM city');
    }

    /**
     * @return City
     * @since 1.0
     */
    public static function fetchByID($id)
    {
        $id = (int) $id;

        if (empty($id)) {
            return false;
        }

        return self::fetchFirst('SELECT * FROM city WHERE city_id = ?', array( $id ));
    }

    /**
     * @return City
     * @since 1.0
     */
    public static function fetchFirst($sql, array $params = array())
    {
		try {

			global $db;

			$stmt = $db->prepare($sql);
			$stmt->execute($params);

			$city = $stmt->fetchObject('eBloodBank\Models\User');
            $stmt->closeCursor();

			return $city;

		} catch ( \PDOException $ex ) {
			// TODO: Logging Exceptions
		}
    }

    /**
     * @return Generator
     * @since 1.0
     */
    public static function fetchEach($sql, array $params = array())
    {
		try {

			global $db;

			$stmt = $db->prepare($sql);
			$stmt->execute($params);

			while ($city = $stmt->fetchObject('eBloodBank\Models\City')) {
				yield $city;
			}

            $stmt->closeCursor();

		} catch ( \PDOException $ex ) {
			// TODO: Logging Exceptions
		}
    }

    /**
     * @return bool
     * @since 1.0
     */
    public static function update($id, array $data)
    {
        try {
            global $db;

            $id = (int) $id;

            if (empty($id)) {
                return false;
            }

            $params = array();
            $set_stmt = array();

            foreach ($data as $key => $value) {
                $set_stmt[] = "`$key` = ?";
                $params[] = $value;
            }

            $set_stmt = implode(', ', $set_stmt);
            $set_stmt = "SET $set_stmt";

            $where_stmt = 'WHERE city_id = ?';
            $params[] = $id;

            $stmt = $db->prepare("UPDATE city $set_stmt $where_stmt");
            $updated = (bool) $stmt->execute($params);
            $stmt->closeCursor();

            return $updated;
        } catch (\PDOException $ex) {
            return false;
        }
    }

    /**
     * @return int
     * @since 1.0
     */
    public static function insert(array $data)
    {
        try {
            global $db;

            $id = 0;
            $columns = implode('`, `', array_keys($data));
            $holders = implode(', ',  array_fill(0, count($data), '?'));

            $stmt = $db->prepare("INSERT INTO city (`$columns`) VALUES ($holders)");
            $inserted = (bool) $stmt->execute(array_values($data));
            $stmt->closeCursor();

            if ($inserted) {
                $id = $db->lastInsertId();
            }

            return $id;
        } catch (\PDOException $ex) {
            return false;
        }
    }

    /**
     * @return bool
     * @since 1.0
     */
    public static function delete($id)
    {
        try {
            global $db;

            $id = (int) $id;

            if (empty($id)) {
                return false;
            }

            $stmt = $db->prepare("DELETE FROM city WHERE city_id = ?");
            $deleted = (bool) $stmt->execute(array( $id ));
            $stmt->closeCursor();

            return $deleted;
        } catch (\PDOException $ex) {
            return false;
        }
    }
}
