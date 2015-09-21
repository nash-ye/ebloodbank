<?php
/**
 * User Model
 *
 * @package EBloodBank
 * @subpackage Models
 * @since 1.0
 */
namespace EBloodBank\Models;

use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Roles;
use EBloodBank\Traits\EntityMeta;

/**
 * @since 1.0
 *
 * @Entity(repositoryClass="EBloodBank\Models\UserRepository")
 * @Table(name="user")
 */
class User extends Entity
{
    use EntityMeta;

    /**
     * @var int
     * @since 1.0
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="user_id")
     */
    protected $id = 0;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="user_name")
     */
    protected $name;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="user_email", unique=true)
     */
    protected $email;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="user_pass")
     */
    protected $pass;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="user_role")
     */
    protected $role;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="datetime", name="user_created_at")
     */
    protected $created_at;

    /**
     * @var string
     * @since 1.0
     *
     * @Column(type="string", name="user_status")
     */
    protected $status;

    /**
     * @return bool
     * @since 1.0
     */
    public function isExists()
    {
        $id = (int) $this->get('id');
        return ! empty($id);
    }

    /**
     * @return \EBloodBank\Role
     * @since 1.0
     */
    public function getRole()
    {
        return Roles::getRole($this->get('role'));
    }

    /**
     * @return string
     * @since 1.0
     */
    public function getRoleTitle()
    {
        $role = $this->getRole();

        if (empty($role)) {
            return $this->get('role');
        } else {
            return $role->getTitle();
        }
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function hasCapability($cap)
    {
        $role = $this->getRole();

        if (empty($role)) {
            return false;
        }

        return $role->hasCapability($cap);
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function isPending()
    {
        return 'pending' === $this->get('status');
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function isActivated()
    {
        return 'activated' === $this->get('status');
    }

    /**
     * @return mixed
     * @since 1.0
     * @static
     */
    public static function sanitize($key, $value)
    {
        switch ($key) {
            case 'id':
                $value = EBB\sanitizeInteger($value);
                break;
            case 'name':
                $value = EBB\sanitizeTitle($value);
                break;
            case 'email':
                $value = EBB\sanitizeEmail($value);
                break;
            case 'role':
            case 'status':
                $value = EBB\sanitizeSlug($value);
                break;
            case 'created_at':
                break;
        }
        return $value;
    }

    /**
     * @throws \InvalidArgumentException
     * @return bool
     * @since 1.0
     * @static
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'id':
                if (! EBB\isValidID($value)) {
                    throw new InvalidArgumentException(__('Invalid user ID.'));
                }
                break;
            case 'name':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgumentException(__('Invalid user name.'));
                }
                break;
            case 'email':
                if (! EBB\isValidEmail($value)) {
                    throw new InvalidArgumentException(__('Invalid user e-mail.'));
                }
                break;
            case 'pass':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgumentException(__('Invalid user password.'));
                }
                break;
            case 'role':
                if (! is_string($value) || ! Roles::isExists($value)) {
                    throw new InvalidArgumentException(__('Invalid user role.'));
                }
                break;
            case 'created_at':
                break;
            case 'status':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgumentException(__('Invalid user status.'));
                }
                break;
        }
        return true;
    }
}
