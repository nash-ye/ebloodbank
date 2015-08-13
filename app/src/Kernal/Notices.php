<?php
namespace EBloodBank\Kernal;

/**
 * @since 1.0
 */
class Notices
{
    /**
     * @var array
     * @since 1.0
     * @static
     */
    private static $notices = array();

    /**
     * @return array
     * @since 1.0
     * @static
     */
    public static function getNotices()
    {
        return self::$notices;
    }

    /**
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
     * @return bool
     * @since 1.0
     * @static
     */
    public static function hasNotice($code)
    {
        return isset(self::$notices[$code]);
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function hasNotices($type = 'all')
    {
        if ('all' === $type) {

            return ! empty(self::$notices);

        } else {

            foreach (self::$notices as $notice) {
                if ($notice->type === $type) {
                    return true;
                }
            }

            return false;

        }
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function addNotice($code, $msg, $type = 'fault')
    {
        if (! $code || self::hasNotice($code)) {
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
     * @return void
     * @since 1.0
     * @static
     */
    public static function removeNotice($code)
    {
        if (! $code || ! self::hasNotice($code)) {
            return false;
        }

        unset(self::$notices[$code]);

        return true;
    }
}
