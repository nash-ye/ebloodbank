<?php
/**
 * Locales Class
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

/**
 * @since 1.0
 */
class Locales
{
    /**
     * @var array
     * @since 1.0
     * @static
     */
    protected static $locales = array();

    /**
     * @var string
     * @since 1.0
     * @static
     */
    protected static $currentLocale;

    /**
     * @var array
     * @since 1.0
     * @static
     */
    public static function getLocales()
    {
        return self::$locales;
    }

    /**
     * @var Locale
     * @since 1.0
     * @static
     */
    public static function getLocale($code)
    {
        if (! self::isLocaleExists($code)) {
            return;
        }

        return self::$locales[$code];
    }

    /**
     * @var bool
     * @since 1.0
     * @static
     */
    public static function isLocaleExists($code)
    {
        return $code && isset(self::$locales[$code]);
    }

    /**
     * @var bool
     * @since 1.0
     * @static
     */
    public static function addLocale(Locale $locale)
    {
        if (self::isLocaleExists($locale->getCode())) {
            return false;
        }

        self::$locales[$locale->getCode()] = $locale;
        return true;
    }

    /**
     * @var bool
     * @since 1.0
     * @static
     */
    public static function removeLocale($code)
    {
        if (! self::isLocaleExists($code)) {
            return false;
        }

        unset(self::$locales[$code]);
        return true;
    }

    /**
     * @var Locale
     * @since 1.0
     * @static
     */
    public static function getCurrentLocale()
    {
        return self::getLocale(self::$currentLocale);
    }

    /**
     * @var bool
     * @since 1.0
     * @static
     */
    public static function setCurrentLocale($code)
    {
        if (! self::isLocaleExists($code)) {
            return false;
        }

        self::$currentLocale = $code;
        return true;
    }
}
