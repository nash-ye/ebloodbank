<?php
namespace eBloodBank\Kernal;

/**
 * @since 1.0
 */
class Roles
{
    /**
     * @var Role[]
     * @since 1.0
     */
    private static $roles = array();

    /**
     * @return bool
     * @since 1.0
     */
    public static function addRole(Role $role)
    {
        if (self::isExists($role->slug)) {
            return false;
        }

        self::$roles[ $role->slug ] = $role;
        return true;
    }

    /**
     * @return bool
     * @since 1.0
     */
    public static function removeRole($slug)
    {
        if (! self::isExists($slug)) {
            return false;
        }

        unset(self::$roles[ $slug ]);
        return true;
    }

    /**
     * @return bool
     * @since 1.0
     */
    public static function isExists($slug)
    {
        return isset(self::$roles[ $slug ]);
    }

    /**
     * @return Role
     * @since 1.0
     */
    public static function getRole($slug)
    {
        if (self::isExists($slug)) {
            return self::$roles[ $slug ];
        }
    }

    /**
     * @return Role[]
     * @since 1.0
     */
    public static function getRoles()
    {
        return self::$roles;
    }
}
