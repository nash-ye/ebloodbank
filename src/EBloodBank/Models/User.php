<?php
/**
 * User entity class file
 *
 * @package    EBloodBank
 * @subpackage Models
 * @since      1.0
 */
namespace EBloodBank\Models;

use InvalidArgumentException;
use EBloodBank as EBB;
use EBloodBank\Roles;
use EBloodBank\Traits\EntityMeta;

/**
 * User entity class
 *
 * @since 1.0
 *
 * @Entity(repositoryClass="EBloodBank\Models\UserRepository")
 * @Table(name="user")
 */
class User extends Entity
{
    use EntityMeta;

    /**
     * User ID
     * 
     * @var   int
     * @since 1.0
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="user_id")
     */
    protected $id = 0;

    /**
     * User name
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="string", name="user_name")
     */
    protected $name;

    /**
     * User email
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="string", name="user_email", unique=true)
     */
    protected $email;

    /**
     * User password
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="string", name="user_pass")
     */
    protected $pass;

    /**
     * User role
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="string", name="user_role")
     */
    protected $role;

    /**
     * User creation datetime
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="datetime", name="user_created_at")
     */
    protected $created_at;

    /**
     * User status
     * 
     * @var   string
     * @since 1.0
     *
     * @Column(type="string", name="user_status")
     */
    protected $status;

    /**
     * User meta
     * 
     * @var   array
     * @since 1.0
     *
     * @Column(type="json", name="user_meta")
     */
    protected $meta = [];

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
     * @since 1.2
     */
    public function canAddUser()
    {
        return $this->hasCapability('add_user');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canViewUsers()
    {
        return $this->hasCapability('view_users');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canViewUser(User $user)
    {
        return $this->canViewUsers();
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canEditUsers()
    {
        return $this->hasCapability('edit_users');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canEditUser(User $user)
    {
        return ($user->get('id') == $this->get('id') || $this->canEditUsers());
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canDeleteUsers()
    {
        return $this->hasCapability('delete_users');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canDeleteUser(User $user)
    {
        return ($this->canDeleteUsers() && $user->get('id') != $this->get('id'));
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canActivateUsers()
    {
        return $this->hasCapability('activate_users');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canActivateUser(User $user)
    {
        return ($this->canActivateUsers() && $user->get('id') != $this->get('id'));
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canAddDonor()
    {
        return $this->hasCapability('add_donor');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canViewDonors()
    {
        return $this->hasCapability('view_donors');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canViewDonor(Donor $donor)
    {
        return $this->canViewDonors();
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canEditDonors()
    {
        return $this->hasCapability('edit_donors');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canEditDonor(Donor $donor)
    {
        if (! $this->canEditDonors()) {
            return false;
        }

        if (! $this->hasCapability('edit_others_donors')) {
            if ($this->get('id') != $donor->get('created_by')->get('id')) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canDeleteDonors()
    {
        return $this->hasCapability('delete_donors');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canDeleteDonor(Donor $donor)
    {
        if (! $this->canDeleteDonors()) {
            return false;
        }

        if (! $this->hasCapability('delete_others_donors')) {
            if ($this->get('id') != $donor->get('created_by')->get('id')) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canApproveDonors()
    {
        return $this->hasCapability('approve_donors');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canApproveDonor(Donor $donor)
    {
        return $this->canApproveDonors();
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canAddCity()
    {
        return $this->hasCapability('add_city');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canViewCities()
    {
        return $this->hasCapability('view_cities');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canViewCity(City $city)
    {
        return $this->canViewCities();
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canEditCities()
    {
        return $this->hasCapability('edit_cities');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canEditCity(City $city)
    {
        return $this->canEditCities();
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canDeleteCities()
    {
        return $this->hasCapability('delete_cities');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canDeleteCity(City $city)
    {
        return $this->canDeleteCities();
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canAddDistrict()
    {
        return $this->hasCapability('add_district');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canViewDistricts()
    {
        return $this->hasCapability('view_districts');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canViewDistrict(District $district)
    {
        return $this->canViewDistricts();
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canEditDistricts()
    {
        return $this->hasCapability('edit_districts');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canEditDistrict(District $district)
    {
        return $this->canEditDistricts();
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canDeleteDistricts()
    {
        return $this->hasCapability('delete_districts');
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canDeleteDistrict(District $district)
    {
        return $this->canDeleteDistricts();
    }

    /**
     * @return bool
     * @since 1.2
     */
    public function canEditSettings()
    {
        return $this->hasCapability('edit_settings');
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
