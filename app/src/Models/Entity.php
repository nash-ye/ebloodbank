<?php
/**
 * Base Model
 *
 * @package EBloodBank
 * @subpackage Models
 * @since 1.0
 */
namespace EBloodBank\Models;

/**
 * @since 1.0
 *
 * @MappedSuperclass
 */
abstract class Entity
{
    /**
     * @return mixed
     * @since 1.0
     */
    public function get($key)
    {
        if (property_exists($this, $key)) {
            return $this->$key;
        }
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function isExists()
    {
        $id = (int) $this->get('id');
        return ! empty($id);
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
            case 'plain':
                echo $this->get($key);
                break;

        }
    }

    /**
     * @return mixed
     * @since 1.0
     */
    static public function sanitize($key, $value)
    {
        return $value;
    }

    /**
     * @return bool
     * @since 1.0
     */
    static public function validate($key, $value)
    {
        return true;
    }

    /**
     * @return void
     * @since 1.0
     */
    public function set($key, $value, $sanitize = false, $validate = true)
    {
        if (property_exists($this, $key)) {
            if ($sanitize) {
                $value = static::sanitize($key, $value);
            }
            if (! $validate || static::validate($key, $value)) {
                $this->$key = $value;
            }
        }
    }
}
