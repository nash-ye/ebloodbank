<?php
/**
 * Locales Class
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

use GlobIterator;

/**
 * @since 1.0
 */
class Locales
{
    /**
     * @var Locale
     * @since 1.0
     * @static
     */
    protected static $defaultLocale;

    /**
     * @var Locale
     * @since 1.0
     * @static
     */
    protected static $currentLocale;

    /**
     * @return Locale
     * @since 1.0
     * @static
     */
    public static function getDefaultLocale()
    {
        return self::$defaultLocale;
    }

    /**
     * @return Locale
     * @since 1.0
     * @static
     */
    public static function getCurrentLocale()
    {
        if (empty(self::$currentLocale)) {
            return self::$defaultLocale;
        } else {
            return self::$currentLocale;
        }
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function isDefaultLocale(Locale $locale)
    {
        $defaultLocale = self::getDefaultLocale();

        if (empty($defaultLocale)) {
            return false;
        }

        return ($defaultLocale->getCode() === $locale->getCode());
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function isCurrentLocale(Locale $locale)
    {
        $currentLocale = self::getCurrentLocale();

        if (empty($currentLocale)) {
            return false;
        }

        return ($currentLocale->getCode() === $locale->getCode());
    }

    /**
     * @return void
     * @since 1.0
     * @static
     */
    public static function setDefaultLocale(Locale $locale)
    {
        self::$defaultLocale = $locale;
    }

    /**
     * @return void
     * @since 1.0
     * @static
     */
    public static function setCurrentLocale(Locale $locale)
    {
        self::$currentLocale = $locale;
    }

    /**
     * @return Locale[]
     * @since 1.0
     * @static
     */
    public static function getAvailableLocales()
    {
        $locales = array();

        foreach (new GlobIterator(EBB_LOCALES_DIR . '/*.mo') as $moFile) {
            if ($moFile->isFile() && $moFile->isReadable()) {
                $moFilePath = $moFile->getRealPath();
                $localeCode = $moFile->getBasename('.mo');
                $locales[$localeCode] = new Locale($localeCode, $moFilePath);
            }
        }

        return $locales;
    }
}
