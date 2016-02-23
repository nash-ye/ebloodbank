<?php
/**
 * Themes class file
 *
 * @package EBloodBank
 * @since   1.3
 */
namespace EBloodBank;

use DirectoryIterator;

/**
 * Themes class
 *
 * @since 1.3
 */
class Themes
{
    /**
     * The default theme.
     *
     * @var Theme
     * @since 1.3
     * @static
     */
    protected static $defaultTheme;

    /**
     * The current theme.
     *
     * @var Theme
     * @since 1.3
     * @static
     */
    protected static $currentTheme;

    /**
     * @access private
     * @since 1.3
     */
    private function __construct()
    {
    }

    /**
     * Get the default theme.
     *
     * @return Theme
     * @since 1.3
     * @static
     */
    public static function getDefaultTheme()
    {
        return self::$defaultTheme;
    }

    /**
     * Get the current theme, or fallback to the default theme if no theme is active.
     *
     * @return Theme
     * @since 1.3
     * @static
     */
    public static function getCurrentTheme()
    {
        if (empty(self::$currentTheme)) {
            return self::$defaultTheme;
        } else {
            return self::$currentTheme;
        }
    }

    /**
     * Whether the given theme is the default.
     *
     * @return bool
     * @since 1.3
     * @static
     */
    public static function isDefaultTheme(Theme $theme)
    {
        $defaultTheme = self::getDefaultTheme();

        if (empty($defaultTheme)) {
            return false;
        }

        return ($defaultTheme->getName() === $theme->getName());
    }

    /**
     * Whether the given theme is the current.
     *
     * @return bool
     * @since 1.3
     * @static
     */
    public static function isCurrentLocale(Theme $theme)
    {
        $currentTheme = self::getCurrentTheme();

        if (empty($currentTheme)) {
            return false;
        }

        return ($currentTheme->getName() === $theme->getName());
    }

    /**
     * Set the default theme.
     *
     * @return void
     * @since 1.3
     * @static
     */
    public static function setDefaultTheme(Theme $theme)
    {
        self::$defaultTheme = $theme;
    }

    /**
     * Set the current theme.
     *
     * @return void
     * @since 1.3
     * @static
     */
    public static function setCurrentTheme(Theme $theme)
    {
        self::$currentTheme = $theme;
    }

    /**
     * Get the available themes.
     *
     * @return Theme[]
     * @since 1.3
     * @static
     */
    public static function getAvailableThemes()
    {
        $themes = [];

        foreach (new DirectoryIterator(EBB_THEMES_DIR) as $themeDir) {
            if (! $themeDir->isDot() && $themeDir->isDir()) {
                $themeName = $themeDir->getFilename();
                $themeDirPath = $themeDir->getRealPath();
                $themes[$themeName] = new Theme($themeName, $themeDirPath);
            }
        }

        return $themes;
    }
}
