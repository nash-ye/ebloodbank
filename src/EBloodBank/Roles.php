<?php
/**
 * Roles class file
 *
 * @package EBloodBank
 * @since   1.0
 */
namespace EBloodBank;

/**
 * Roles class
 *
 * @since 1.0
 */
class Roles
{
    /**
     * The roles list.
     *
     * @var Role[]
     * @since 1.0
     * @static
     */
    private static $roles = [];

    /**
     * @access private
     * @since 1.0
     */
    private function __construct()
    {
    }

    /**
     * Add a new role.
     *
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
     * Remove an existing role.
     *
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
     * Whether a given role is exists.
     *
     * @return bool
     * @since 1.0
     * @static
     */
    public static function isExists($role)
    {
        if ($role instanceof Role) {
            $role = $role->getSlug();
        }

        return isset(self::$roles[$role]);
    }

    /**
     * Get a role.
     *
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
     * Get all roles.
     *
     * @return Role[]
     * @since 1.0
     * @static
     */
    public static function getRoles()
    {
        return self::$roles;
    }
}
