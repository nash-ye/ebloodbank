<?php
namespace eBloodBank\Models;

use eBloodBank\Kernal\Model;
use eBloodBank\Kernal\Roles;

/**
 * @since 1.0
 */
class User extends Model
{
    /**
     * @var string
     * @since 1.0
     */
    const TABLE = 'user';

    /**
     * @var string
     * @since 1.0
     */
    const PK_ATTR = 'user_id';

    /**
     * @var string
     * @since 1.0
     */
    const META_TABLE = 'user_meta';

    /**
     * @var string
     * @since 1.0
     */
    const META_FK_ATTR = 'user_id';

    /**
     * @var int
     * @since 1.0
     */
    public $user_id = 0;

    /**
     * @var string
     * @since 1.0
     */
    public $user_logon;

    /**
     * @var string
     * @since 1.0
     */
    public $user_pass;

    /**
     * @var string
     * @since 1.0
     */
    public $user_rtime;

    /**
     * @var string
     * @since 1.0
     */
    public $user_role = 'default';

    /**
     * @var string
     * @since 1.0
     */
    public $user_status = 'activated';

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
}
