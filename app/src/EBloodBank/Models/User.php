<?php
/**
 * User Model
 *
 * @package EBloodBank
 * @subpackage Models
 * @since 1.0
 */
namespace EBloodBank\Models;

use EBloodBank as EBB;
use EBloodBank\Roles;
use EBloodBank\Traits\EntityMeta;
use EBloodBank\Exceptions\InvalidArgument;

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
     * @return Role
     * @since 1.0
     */
    public function getRole()
    {
        return Roles::getRole($this->get('role'));
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
     * @var bool
     * @since 1.0
     */
    public function isPending()
    {
        return 'pending' === $this->get('status');
    }

    /**
     * @var bool
     * @since 1.0
     */
    public function isActivated()
    {
        return 'activated' === $this->get('status');
    }

    /**
     * @return mixed
     * @since 1.0
     */
    public static function sanitize($key, $value)
    {
        switch ($key) {
            case 'id':
                $value = EBB\sanitizeInteger($value);
                break;
            case 'email':
                $value = EBB\sanitizeEmail($value);
                break;
            case 'name':
            case 'role':
                $value = trim($value);
                break;
            case 'created_at':
                break;
        }
        return $value;
    }

    /**
     * @throws \EBloodBank\Exceptions\InvalidArgument
     * @return bool
     * @since 1.0
     */
    public static function validate($key, $value)
    {
        switch ($key) {
            case 'id':
                if (! EBB\isValidID($value)) {
                    throw new InvalidArgument(__('Invalid user ID.'), 'Invalid_user_id');
                }
                break;
            case 'name':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgument(__('Invalid user name.'), 'Invalid_user_name');
                }
                break;
            case 'email':
                if (! EBB\isValidEmail($value)) {
                    throw new InvalidArgument(__('Invalid user e-mail.'), 'Invalid_user_email');
                }
                break;
            case 'pass':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgument(__('Invalid user password.'), 'Invalid_user_pass');
                }
                break;
            case 'role':
                if (! is_string($value) || ! Roles::isExists($value)) {
                    throw new InvalidArgument(__('Invalid user role.'), 'Invalid_user_role');
                }
                break;
            case 'created_at':
                break;
            case 'status':
                if (! is_string($value) || empty($value)) {
                    throw new InvalidArgument(__('Invalid user status.'), 'Invalid_user_status');
                }
                break;
        }
        return true;
    }
}
