<?php
/**
 * @package EBloodBank
 * @since   1.5
 */
namespace EBloodBank;

use EBloodBank\Models\User;
use EBloodBank\Models\Donor;
use EBloodBank\Models\City;
use EBloodBank\Models\Entity;
use EBloodBank\Models\District;

/**
 * @since 1.5
 */
class Acl extends \Zend\Permissions\Acl\Acl implements AclInterface
{
    /**
     * @return bool
     * @since 1.5
     */
    public function canActivateUser(User $subject, User $user)
    {
        $can = $this->isUserAllowed($subject, 'User', 'activate');
        return $can;
    }

    /**
     * @return bool
     * @since 1.5
     */
    public function canApproveDonor(User $subject, Donor $donor)
    {
        $can = $this->isUserAllowed($subject, 'Donor', 'approve');
        return $can;
    }

    /**
     * @return bool
     * @since 1.5
     */
    public function canReadEntity(User $subject, Entity $resource)
    {
        if ($resource instanceof User) {
            return $this->isUserAllowed($subject, 'User', 'read');
        } elseif ($resource instanceof Donor) {
            return $this->isUserAllowed($subject, 'Donor', 'read');
        } elseif ($resource instanceof City) {
            return $this->isUserAllowed($subject, 'City', 'read');
        } elseif ($resource instanceof District) {
            return $this->isUserAllowed($subject, 'District', 'read');
        }

        return false;
    }

    /**
     * @return bool
     * @since 1.5
     */
    public function canEditEntity(User $subject, Entity $resource)
    {
        if ($resource instanceof User) {
            return $this->canEditUser($subject, $resource);
        } elseif ($resource instanceof Donor) {
            return $this->canEditDonor($subject, $resource);
        } elseif ($resource instanceof City) {
            return $this->isUserAllowed($subject, 'City', 'edit');
        } elseif ($resource instanceof District) {
            return $this->isUserAllowed($subject, 'District', 'edit');
        }

        return false;
    }

    /**
     * @return bool
     * @since 1.5
     */
    protected function canEditUser(User $subject, User $user)
    {
        $can = $subject->get('id') === $user->get('id');
        if (! $can) {
            $can = $this->isUserAllowed($subject, 'User', 'edit');
        }

        return $can;
    }

    /**
     * @return bool
     * @since 1.5
     */
    protected function canEditDonor(User $subject, Donor $donor)
    {
        if ($subject->get('id') === $donor->get('created_by')->get('id')) {
            $can = $this->isUserAllowed($subject, 'Donor', 'edit');
        } else {
            $can = $this->isUserAllowed($subject, 'Donor', 'edit_others');
        }

        return $can;
    }

    /**
     * @return bool
     * @since 1.5
     */
    public function canDeleteEntity(User $subject, Entity $resource)
    {
        if ($resource instanceof User) {
            return $this->canDeleteUser($subject, $resource);
        } elseif ($resource instanceof Donor) {
            return $this->canDeleteDonor($subject, $resource);
        } elseif ($resource instanceof City) {
            return $this->isUserAllowed($subject, 'City', 'delete');
        } elseif ($resource instanceof District) {
            return $this->isUserAllowed($subject, 'District', 'delete');
        }

        return false;
    }

    /**
     * @return bool
     * @since 1.5
     */
    protected function canDeleteUser(User $subject, User $user)
    {
        $can = $subject->get('id') !== $user->get('id');
        if ($can) {
            $can = $this->isUserAllowed($subject, 'User', 'delete');
        }

        return $can;
    }

    /**
     * @return bool
     * @since 1.5
     */
    protected function canDeleteDonor(User $subject, Donor $donor)
    {
        if ($subject->get('id') === $donor->get('created_by')->get('id')) {
            $can = $this->isUserAllowed($subject, 'Donor', 'delete');
        } else {
            $can = $this->isUserAllowed($subject, 'Donor', 'delete_others');
        }

        return $can;
    }

    /**
     * @return bool
     * @since 1.5
     */
    public function isUserAllowed(User $subject, $resource = null, $privilege = null)
    {
        $accountRole = $subject->get('role');
        if (! $accountRole || ! $this->isAllowed($accountRole, $resource, $privilege)) {
            return false;
        }

        return true;
    }
}
