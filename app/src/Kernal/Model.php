<?php
namespace eBloodBank\Kernal;

/**
 * @since 1.0
 */
abstract class Model
{
    /**
     * @return void
     * @since 1.0
     */
    public function __construct($data = array())
    {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        if (! $data || ! is_array($data)) {
            return;
        }

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    // Getters

    /**
     * @return int
     * @since 1.0
     */
    public function getID()
    {
        return (int) $this->get(static::PK_ATTR);
    }

    /**
     * @return mixed
     * @since 1.0
     */
    public function get($key)
    {
        return isset($this->$key) ? $this->$key : null;
    }

    /**
     * @return mixed
     * @since 1.0
     */
    public function display($key, $format = 'html')
    {
        switch ($format) {

            case 'attr':
                echo esc_attr($this->get($key));
                break;

            case 'html':
                echo esc_html($this->get($key));
                break;

            default:
                echo $this->get($key);
                break;

        }
    }
}

/**
 * @since 1.0
 */
trait Model_Meta
{
    /**
     * @var array
     * @since 1.0
     */
    protected $meta = array();

    /**
     * @return mixed
     * @since 1.0
     */
    public function getMeta($meta_key, $single = true)
    {
        try {
            global $db;

            if ($single) {
                $meta_value = null;
            } else {
                $meta_value = array();
            }

            if (! parent::get_ID() || ! $meta_key) {
                return $meta_value;
            }

            if (! isset($this->meta[ $meta_key ])) {
                if ($single) {
                    $stmt = $db->prepare('SELECT meta_id, meta_value FROM ' . static::META_TABLE . ' WHERE ' . static::META_FK_ATTR . ' = ? AND meta_key = ?', array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL));
                    $stmt->execute(array( parent::get_ID(), $meta_key ));

                    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                    $stmt->closeCursor();

                    if ($row) {
                        $this->meta[ $meta_key ][ $row['meta_id'] ] = $row['meta_value'];
                    }
                } else {
                    $stmt = $db->prepare('SELECT meta_id, meta_value FROM ' . static::META_TABLE . ' WHERE ' . static::META_FK_ATTR . ' = ? AND meta_key = ?', array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL));
                    $stmt->execute(array( parent::get_ID(), $meta_key ));

                    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $stmt->closeCursor();

                    if ($rows) {
                        foreach ($rows as $row) {
                            $this->meta[ $meta_key ][ $row['meta_id'] ] = $row['meta_value'];
                        }
                    }
                }
            }

            if (isset($this->meta[ $meta_key ])) {
                if ($single) {
                    $meta_value = reset($this->meta[ $meta_key ]);
                } else {
                    $meta_value = (array) $this->meta[ $meta_key ];
                }
            }
        } catch (\Exception $ex) {
            // TODO: Logging
        }

        return $meta_value;
    }

    /**
     * @return void
     * @since 1.0
     */
    public function submitMeta($meta_key, $meta_value)
    {
        if (empty($meta_value)) {
            $this->deleteMeta($meta_key);
        } else {
            $old_value = $this->getMeta($meta_key);

            if (is_null($old_value)) {
                $this->insertMeta($meta_key, $meta_value);
            } else {
                $this->updateMeta($meta_key, $meta_value);
            }
        }
    }

    /**
     * @return int
     * @since 1.0
     */
    public function insertMeta($meta_key, $meta_value)
    {
        try {
            global $db;

            if (! parent::get_ID() || ! $meta_key) {
                return false;
            }

            $data = array(
                static::META_FK_ATTR  => parent::get_ID(),
                'meta_value'          => $meta_value,
                'meta_key'            => $meta_key,
            );

            $columns = implode('`, `', array_keys($data));
            $holders = implode(', ',  array_fill(0, count($data), '?'));

            $stmt = $db->prepare("INSERT INTO " . static::META_TABLE . " (`$columns`) VALUES ($holders)");
            $inserted = (bool) $stmt->execute(array_values($data));
            $stmt = $stmt->closeCursor();

            return ($inserted) ? $db->lastInsertId() : 0;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function updateMeta($meta_key, $meta_value, $prev_value = null)
    {
        try {
            global $db;

            $where_stmt = array();
            $stmt_params = array();

            if (! parent::get_ID()  || ! $meta_key) {
                return false;
            }

            $stmt_params[] = $meta_value;

            $where_stmt[] = 'meta_key = ?';
            $stmt_params[] = $meta_key;

            $where_stmt[] = sprintf('%s = ?', static::META_FK_ATTR);
            $stmt_params[] = parent::get_ID();

            if (! is_null($prev_value)) {
                $where_stmt[] = 'meta_value = ?';
                $stmt_params[] = $prev_value;
            }

            $where_stmt = implode(' AND ', $where_stmt);
            $where_stmt = "WHERE {$where_stmt}";

            $stmt = $db->prepare("UPDATE " . static::META_TABLE . " SET meta_value = ? {$where_stmt}");
            $updated = (bool) $stmt->execute($stmt_params);
            $stmt = $stmt->closeCursor();

            return $updated;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * @return int
     * @since 1.0
     */
    public function deleteMeta($meta_key, $meta_value = null)
    {
        try {
            global $db;

            $where_stmt = array();
            $stmt_params = array();

            if (! parent::get_ID()  || ! $meta_key) {
                return false;
            }

            $where_stmt[] = 'meta_key = ?';
            $stmt_params[] = $meta_key;

            $where_stmt[] = sprintf('%s = ?', static::META_FK_ATTR);
            $stmt_params[] = parent::get_ID();

            if (! is_null($meta_value)) {
                $where_stmt[] = 'meta_value = ?';
                $stmt_params[] = $meta_value;
            }

            $where_stmt = implode(' AND ', $where_stmt);
            $where_stmt = "WHERE {$where_stmt}";

            $stmt = $db->prepare("DELETE FROM " . static::META_TABLE . " $where_stmt");
            $deleted = (bool) $stmt->execute($stmt_params);
            $stmt = $stmt->closeCursor();

            return $deleted;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
