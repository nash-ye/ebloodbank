<?php
namespace eBloodBank\Models;

use eBloodBank\Kernal\Model;
use eBloodBank\Kernal\Roles;
use eBloodBank\Exceptions\InvaildProperty;

/**
 * @since 1.0
 *
 * @Entity(repositoryClass="eBloodBank\Models\UserRepository")
 * @Table(name="user")
 */
class User extends Model
{
    /**
     * @var int
     * @since 1.0
     *
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $user_id = 0;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $user_logon;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $user_pass;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $user_rtime;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $user_role = 'default';

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string")
     */
    protected $user_status = 'activated';

    /**
     * @return Role
     * @since 1.0
     */
    public function getRole()
    {
        return Roles::getRole($this->get('user_role'));
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function hasCaps(array $caps, $opt = 'AND')
    {
        $role = $this->getRole();

        if (empty($role)) {
            return false;
        }

        return $role->hasCaps($caps, $opt);
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function hasCap($cap)
    {
        $role = $this->getRole();

        if (empty($role)) {
            return false;
        }

        return $role->hasCap($cap);
    }

    /**
     * @return mixed
     * @since 1.0
     */
    public static function sanitize($key, $value)
    {
        switch ($key) {
            case 'user_id':
                $value = (int) $value;
                break;
            case 'user_logon':
            case 'user_role':
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                break;
        }
        return $value;
    }

    /**
     * @return bool
     * @since 1.0
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'user_id':
                if (! isVaildID($value)) {
                    throw new InvaildProperty(__('Invaild user ID'), 'invaild_user_id');
                }
                break;
            case 'user_logon':
                if (empty($value) || ! is_string($value)) {
                    throw new InvaildProperty(__('Invaild user name'), 'invaild_user_name');
                }
                break;
            case 'user_role':
                if (! Roles::isExists($value)) {
                    throw new InvaildProperty(__('Invaild user role'), 'invaild_user_role');
                }
                break;
        }
        return true;
    }
}
