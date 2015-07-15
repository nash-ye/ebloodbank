<?php
namespace eBloodBank\Kernal;

/**
 * @since 1.0
 */
class Database
{

    /**
     * @since 1.0
     */
    private function __construct()
    {
    }

    /**
     * @since 1.0
     */
    public static function getInstance()
    {
        static $db;
        if (is_null($db)) {

            try {
                $db_host = constant('eBloodBank\DB_HOST');
                $db_user = constant('eBloodBank\DB_USER');
                $db_pass = constant('eBloodBank\DB_PASS');
                $db_name = constant('eBloodBank\DB_NAME');
                $db = new \PDO(sprintf('mysql:host=%s;dbname=%s;charset=utf8;', $db_host, $db_name), $db_user, $db_pass);
                $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\Exception $ex) {
                // TODO: Logging Exceptions.
                die('Database Error');
            }
        }
        return $db;
    }

    /**
     * @return mixed
     * @since 1.0
     */
    public static function fetchFirstRow($sql, array $params = null, $fetch_style = null)
    {
        try {

            $stmt = self::getInstance()->prepare($sql);
            $stmt->execute($params);

            $row = $stmt->fetch($fetch_style);
            unset($stmt);

            return $row;

        } catch (\Exception $ex) {
            // TODO: Logging Exceptions
        }
    }

    /**
     * @return mixed
     * @since 1.0
     */
    public static function fetchFirstObject($sql, array $params = null, $class_name = 'stdClass')
    {
        try {

            $stmt = self::getInstance()->prepare($sql);
            $stmt->execute($params);

            $object = $stmt->fetchObject($class_name);
            unset($stmt);

            return $object;

        } catch (\Exception $ex) {
            // TODO: Logging Exceptions
        }
    }

    /**
     * @return Generator
     * @since 1.0
     */
    public static function fetchEachRow($sql, array $params = null, $fetch_style = null)
    {
        try {

            $stmt = self::getInstance()->prepare($sql);
            $stmt->execute($params);

            while ($row = $stmt->fetch($fetch_style)) {
                yield $row;
            }

            unset($stmt);

        } catch (\Exception $ex) {
            // TODO: Logging Exceptions
        }
    }

    /**
     * @return Generator
     * @since 1.0
     */
    public static function fetchEachObject($sql, array $params = null, $types = null, $class_name = 'stdClass')
    {
        try {

            $stmt = self::getInstance()->prepare($sql);

            if (! empty($types) && count($params) == count($types)) {
                $types = self::process_types($types);
                for($i = 0; $i < count($params); $i++) {
                    $stmt->bindParam($i + 1, $params[$i], $types[$i]);
                }
            }

            $stmt->execute();

            while ($object = $stmt->fetchObject($class_name)) {
                yield $object;
            }

            unset($stmt);

        } catch (\Exception $ex) {
            // TODO: Logging Exceptions
        }
    }

    /**
     * @return bool
     * @since 1.0
     */
    public static function update($table, $id, array $data, array $conditions)
    {
        try {
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

            $conditions = self::process_fields($conditions);

            $clauses = [];
            $clauses[] = '1 = 1';
            foreach (array_keys($conditions) as $field) {
                $clauses[] = "`$field` = ?";
            }
            $clauses = implode(' AND ', $clauses);

            $stmt = self::getInstance()->prepare("UPDATE $table $set_stmt WHERE $where");
            $updated = (bool) $stmt->execute($params);
            unset($stmt);

            return $updated;

        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * @return int
     * @since 1.0
     */
    public static function insert($table, array $fields)
    {

        try {

            $db = self::getInstance();
            $fields = self::process_fields($fields);

            $columns = implode('`, `', array_keys($fields));
            $holders = implode(', ',  array_fill(0, count($fields), '?'));

            $stmt = $db->prepare("INSERT INTO $table (`$columns`) VALUES ($holders)");

            foreach (array_values($fields) as $index => $args) {
                $stmt->bindParam(++$index, $args['value'], $args['type']);
            }

            $inserted = (bool) $stmt->execute();
            unset($stmt);

            if ($inserted) {
                return $db->lastInsertId();
            }

        } catch (\Exception $ex) {
            // TODO: Logging Exceptions
        }

        return false;

    }

    /**
     * @return Generator
     * @since 1.0
     */
    public static function delete($table, array $fields)
    {
        try {

            if (empty($table)) {
                return false;
            }

            $fields = self::process_fields($fields);

            $clauses = [];
            $clauses[] = '1 = 1';
            foreach (array_keys($fields) as $field) {
                $clauses[] = "`$field` = ?";
            }
            $clauses = implode(' AND ', $clauses);

            $stmt = self::getInstance()->prepare("DELETE FROM $table WHERE $clauses");

            foreach (array_values($fields) as $index => $args) {
                $stmt->bindParam(++$index, $args['value'], $args['type']);
            }

            $deleted = (bool) $stmt->execute();
            unset($stmt);

            return $deleted;

        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * @return array
     * @since 1.0
     */
    protected static function process_fields(array $fields)
    {
        if (empty($fields)) {
            return $fields;
        }

        foreach ($fields as $field => $args) {

            if (! is_array($args)) {
                $fields[$field] = ['value' => $args, 'type'  => 'str'];
            }

            if(! isset($args['value']) || ! isset($args['type'])) {
                continue;
            }

            $args['type'] = self::process_type($args['type']);

        }

        return $fields;
    }

    /**
     * @return array
     * @since 1.0
     */
    protected static function process_types(array $types)
    {
        if (! empty($types)) {
            foreach ($types as &$type) {
                $type = self::process_type($type);
            }
        }

        return $types;
    }

    /**
     * @return array
     * @since 1.0
     */
    protected static function process_type($type)
    {
        switch ($type) {
            case 'bool':
                $type = \PDO::PARAM_BOOL;
                break;
            case 'null':
                $type = \PDO::PARAM_NULL;
                break;
            case 'int':
                $type = \PDO::PARAM_INT;
                break;
            default:
            case 'str':
                $type = \PDO::PARAM_STR;
                break;
        }

        return $type;
    }

}
