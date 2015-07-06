<?php
namespace eBloodBank\Models;

/**
* @since 1.0
*/
final class Donors
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
        return self::fetchEach('SELECT * FROM donor');
    }

    /**
     * @return Donor
     * @since 1.0
     */
    public static function fetchByID($id)
    {
        $id = (int) $id;

        if (empty($id)) {
            return false;
        }

        return self::fetchFirst('SELECT * FROM donor WHERE donor_id = ?', array( $id ));
    }

    /**
     * @return Generator
     * @since 1.0
     */
    public static function fetchByArgs(array $args)
    {
        $params = array();
        $where_stmt = array();

        $args = array_merge(array(
            'blood_group' => 'all',
            'distr_id'    => 0,
            'city_id'     => 0,
            'status'      => 'all',
            'name'        => '',
        ), $args);

        if (empty($args['status'])) {
            $args['status'] = 'all';
        }

        if (empty($args['blood_group'])) {
            $args['blood_group'] = 'all';
        }

        $args['distr_id'] = (int) $args['distr_id'];
        $args['city_id']  = (int) $args['city_id'];

        if (! empty($args['name'])) {
            $where_stmt[] = 'donor_name LIKE ?';
            $params[] = '%' . $args['name'] . '%';
        }

        if ('all' !== $args['status']) {
            $where_stmt[] = 'donor_status = ?';
            $params[] = $args['status'];
        }

        if ('all' !== $args['blood_group']) {
            $where_stmt[] = 'donor_blood_group = ?';
            $params[] = $args['blood_group'];
        }

        if (isVaildID($args['distr_id'])) {
            $where_stmt[] = 'donor_distr_id = ?';
            $params[] = $args['distr_id'];
        } elseif (isVaildID($args['city_id'])) {
            $where_stmt[] = 'donor_distr_id IN ( SELECT distr_id FROM district WHERE distr_city_id = ? )';
            $params[] = $args['city_id'];
        }

        $where_stmt = implode(' AND ', $where_stmt);

        if (! empty($where_stmt)) {
            $where_stmt = " WHERE {$where_stmt}";
        }

        return self::fetchEach("SELECT * FROM donor {$where_stmt}", $params);
    }

    /**
     * @return Donor
     * @since 1.0
     */
    public static function fetchFirst($sql, array $params = array())
    {
		try {

			global $db;

			$stmt = $db->prepare($sql);
			$stmt->execute($params);

			$donor = $stmt->fetchObject('eBloodBank\Models\Donor');
            $stmt->closeCursor();

			return $donor;

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

			while ($donor = $stmt->fetchObject('eBloodBank\Models\Donor')) {
				yield $donor;
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

            $where_stmt = 'WHERE donor_id = ?';
            $params[] = $id;

            $stmt = $db->prepare("UPDATE donor $set_stmt $where_stmt");
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
                'donor_rtime' => gmdate('Y-m-d H:i:s'),
                'donor_status' => 'pending',
            ), $data);

            $columns = implode('`, `', array_keys($data));
            $holders = implode(', ',  array_fill(0, count($data), '?'));

            $stmt = $db->prepare("INSERT INTO donor (`$columns`) VALUES ($holders)");
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

            $stmt = $db->prepare("DELETE FROM donor WHERE donor_id = ?");
            $deleted = (bool) $stmt->execute(array( $id ));
            $stmt->closeCursor();

            return $deleted;
        } catch (\PDOException $ex) {
            return false;
        }
    }
}
