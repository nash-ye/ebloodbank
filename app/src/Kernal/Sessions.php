<?php
namespace eBloodBank\Kernal;

use eBloodBank\Models\Users;

/**
 * @since 1.0
 */
class Sessions
{

    /**
     * @var eBloodBank\User
     * @since 1.0
     */
    private static $current_user;

    /**
     * @since 1.0
     */
    private function __construct()
    {
    }

    /**
     * @return bool
     * @since 1.0
     */
    public static function isSignedIn()
    {
        return (! empty($_SESSION['signed']));
    }

    /**
     * @return Models\User
     * @since 1.0
     */
    public static function getCurrentUser()
    {
        if (is_null(self::$current_user)) {
            if (self::isSignedIn()) {
                $user_id = (int) $_SESSION['user_id'];
                $user = Users::fetchByID($user_id);

                if (! empty($user)) {
                    self::$current_user = $user;
                }
            }
        }

        return self::$current_user;
    }

    /**
     * @return int
     * @since 1.0
     */
    public static function getCurrentUserID()
    {
        return self::getCurrentUser()->getID();
    }

    /**
     * @return bool
     * @since 1.0
     */
    public static function signIn($user_logon, $user_pass)
    {
        if (self::isSignedIn()) {
            return false;
        }

        if (! $user_logon || ! $user_pass) {
            return false;
        }

        $user = Users::fetchByLogon($user_logon);

        if (empty($user)) {
            return false;
        }

        if (! password_verify($user_pass, $user->get('user_pass'))) {
            return false;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['user_id'] = $user->getID();
        $_SESSION['signin_time'] = time();
        $_SESSION['signed'] = true;

        session_regenerate_id(true);

        return true;
    }

    /**
     * @return void
     * @since 1.0
     */
    public static function signOut()
    {
        if (self::isSignedIn()) {
            $_SESSION = array();
            return session_destroy();
        }

        return true;
    }

}
