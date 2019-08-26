<?php
/**
 * Locales class file
 *
 * @package EBloodBank
 * @since   1.0
 */
namespace EBloodBank;

use GlobIterator;

/**
 * Locales class
 *
 * @since 1.0
 */
class Locales
{
    /**
     * The default locale.
     *
     * @var Locale
     * @since 1.0
     * @static
     */
    protected static $defaultLocale;

    /**
     * The current locale.
     *
     * @var Locale
     * @since 1.0
     * @static
     */
    protected static $currentLocale;

    /**
     * Get the default locale.
     *
     * @return Locale
     * @since 1.0
     * @static
     */
    public static function getDefaultLocale()
    {
        return self::$defaultLocale;
    }

    /**
     * Get the current locale, or the default locale if it's not specified.
     *
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
     * Whether the given locale is the default.
     *
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
     * Whether the given locale is the current.
     *
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
     * Set the default locale.
     *
     * @return void
     * @since 1.0
     * @static
     */
    public static function setDefaultLocale(Locale $locale)
    {
        self::$defaultLocale = $locale;
    }

    /**
     * Set the current locale.
     *
     * @return void
     * @since 1.0
     * @static
     */
    public static function setCurrentLocale(Locale $locale)
    {
        self::$currentLocale = $locale;
    }

    /**
     * Find a locale.
     *
     * @return Locale|null
     * @since 1.3
     * @static
     */
    public static function findLocale($code, $dirPath = '')
    {
        if (empty($code)) {
            return;
        }

        if (empty($dirPath)) {
            $dirPath = EBB_LOCALES_DIR;
        }

        $dirPath = trimTrailingSlash($dirPath);
        $localePath = realpath("{$dirPath}/{$code}.mo");

        if (! $localePath || ! is_readable($localePath)) {
            return;
        }

        if (dirname($localePath) !== realpath($dirPath)) {
            return;
        }

        return new Locale($code, $localePath);
    }

    /**
     * Get the available locales.
     *
     * @return Locale[]
     * @since 1.0
     * @static
     */
    public static function getAvailableLocales($dirPath = '')
    {
        $locales = [];

        if (empty($dirPath)) {
            $dirPath = EBB_LOCALES_DIR;
        }

        $dirPath = trimTrailingSlash($dirPath);

        foreach (new GlobIterator("{$dirPath}/*.mo") as $moFile) {
            if ($moFile->isFile() && $moFile->isReadable()) {
                $moFilePath = $moFile->getRealPath();
                $localeCode = $moFile->getBasename('.mo');
                $locales[$localeCode] = new Locale($localeCode, $moFilePath);
            }
        }

        return $locales;
    }
}
