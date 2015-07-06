<?php
namespace eBloodBank\Models;

/**
* @since 1.0
*/
class Users
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
		return self::fetchEach('SELECT * FROM user');
    }

    /**
     * @return User
     * @since 1.0
     */
    public static function fetchByID($id)
    {
        $id = (int) $id;

        if (empty($id)) {
            return false;
        }

        return self::fetchFirst('SELECT * FROM user WHERE user_id = ?', array( $id ));
    }

    /**
     * @return User
     * @since 1.0
     */
    public static function fetchByLogon($logon)
    {
        if (empty($logon)) {
            return false;
        }

        return self::fetchFirst('SELECT * FROM user WHERE user_logon = ?', array( $logon ));
    }

    /**
     * @return User
     * @since 1.0
     */
	public static function fetchFirst($sql, array $params = array())
	{
		try {

			global $db;

			$stmt = $db->prepare($sql);
			$stmt->execute($params);

			$user = $stmt->fetchObject('eBloodBank\Models\User');
            $stmt->closeCursor();

			return $user;

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

			while ($user = $stmt->fetchObject('eBloodBank\Models\User')) {
				yield $user;
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

            $where_stmt = 'WHERE user_id = ?';
            $params[] = $id;

            $stmt = $db->prepare("UPDATE user $set_stmt $where_stmt");
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

            $data = array_merge(array(
                'user_rtime' => gmdate('Y-m-d H:i:s'),
                'user_status' => 'activated',
            ), $data);

            $columns = implode('`, `', array_keys($data));
            $holders = implode(', ',  array_fill(0, count($data), '?'));

            $stmt = $db->prepare("INSERT INTO user (`$columns`) VALUES ($holders)");
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

            $stmt = $db->prepare('DELETE FROM user WHERE user_id = ?');
            $deleted = (bool) $stmt->execute(array( $id ));
			$stmt->closeCursor();

            return $deleted;
        } catch (\PDOException $ex) {
            return false;
        }
    }
}
