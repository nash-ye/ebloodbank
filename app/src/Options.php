<?php
namespace EBloodBank;

/**
 * @since 1.0
 */
class Options
{
    /**
     * @var array
     * @since 1.0
     * @static
     */
    private static $options = array();

    /**
     * @return mixed
     * @since 1.0
     * @static
     */
    public static function getOption($key)
    {
        if (isset(self::$options[$key])) {
            return self::$options[$key];
        }
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function addOption($key, $value)
    {
        if (isset(self::$options[$key])) {
            return false;
        }

        self::$options[$key] = $value;
        return true;
    }

    /**
     * @return void
     * @since 1.0
     * @static
     */
    public static function updateOption($key, $value)
    {
        if (! isset(self::$options[$key])) {
            return false;
        }

        self::$options[$key] = $value;
        return true;
    }

    /**
     * @return void
     * @since 1.0
     * @static
     */
    public static function deleteOption($key)
    {
        unset(self::$options[$key]);
    }
}
