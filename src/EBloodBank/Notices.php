<?php
/**
 * Notices class file
 *
 * @package EBloodBank
 * @since   1.0
 */
namespace EBloodBank;

/**
 * Notices class
 *
 * @since 1.0
 */
class Notices
{
    /**
     * The notices list.
     *
     * @var array
     * @since 1.0
     * @static
     */
    private static $notices = array();

    /**
     * @access private
     * @since 1.0
     */
    private function __construct()
    {
    }

    /**
     * Get all notices.
     *
     * @return array
     * @since 1.0
     * @static
     */
    public static function getNotices()
    {
        return self::$notices;
    }

    /**
     * Get a notice.
     *
     * @return object
     * @since 1.0
     * @static
     */
    public static function getNotice($code)
    {
        if (self::isExists($code)) {
            return self::$notices[$code];
        }
    }

    /**
     * Whether a given notice is exists.
     *
     * @return bool
     * @since 1.0
     * @static
     */
    public static function isExists($code)
    {
        return isset(self::$notices[$code]);
    }

    /**
     * Add a new notice.
     *
     * @return bool
     * @since 1.0
     * @static
     */
    public static function addNotice($code, $msg, $type = 'warning')
    {
        if (! $code || self::isExists($code)) {
            return false;
        }

        self::$notices[$code] = (object) array(
            'code' => $code,
            'type' => $type,
            'msg'  => $msg,
        );

        return true;
    }

    /**
     * Remove an existing notice.
     *
     * @return void
     * @since 1.0
     * @static
     */
    public static function removeNotice($code)
    {
        if (! $code || ! self::isExists($code)) {
            return false;
        }

        unset(self::$notices[$code]);

        return true;
    }
}
