<?php
/**
 * Roles Class
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

/**
 * @since 1.0
 */
class Roles
{
    /**
     * @var Role[]
     * @since 1.0
     * @static
     */
    private static $roles = array();

    /**
     * @since 1.0
     */
    private function __construct()
    {
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function addRole(Role $role)
    {
        if (self::isExists($role->getSlug())) {
            return false;
        }

        self::$roles[$role->getSlug()] = $role;
        return true;
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function removeRole($slug)
    {
        if (! $slug || ! self::isExists($slug)) {
            return false;
        }

        unset(self::$roles[$slug]);
        return true;
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function isExists($slug)
    {
        return isset(self::$roles[$slug]);
    }

    /**
     * @return Role
     * @since 1.0
     * @static
     */
    public static function getRole($slug)
    {
        if (self::isExists($slug)) {
            return self::$roles[$slug];
        }
    }

    /**
     * @return Role[]
     * @since 1.0
     * @static
     */
    public static function getRoles()
    {
        return self::$roles;
    }
}
