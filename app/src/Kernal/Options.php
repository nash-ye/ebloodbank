<?php
namespace eBloodBank\Kernal;

/**
 * @since 1.0
 */
class Options
{
    /**
     * @var array
     * @since 1.0
     */
    protected static $options = array();

    /**
     * @return mixed
     * @since 1.0
     */
    public static function get_option($key)
    {
        if (isset(self::$options[$key])) {
            return self::$options[$key];
        }
    }

    /**
     * @return mixed
     * @since 1.0
     */
    public static function set_option($key, $value)
    {
        self::$options[$key] = $value;
    }
}
