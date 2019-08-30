<?php
/**
 * @package EBloodBank
 * @since   0.2
 */
namespace EBloodBank;

use EBloodBank\Models\User;
use EBloodBank\Models\Donor;
use EBloodBank\Models\Entity;

/**
 * @since 1.5
 */
interface AclInterface extends \Zend\Permissions\Acl\AclInterface
{
    /**
     * @return bool
     * @since 1.5
     */
    public function canActivateUser(User $subject, User $user);

    /**
     * @return bool
     * @since 1.5
     */
    public function canApproveDonor(User $subject, Donor $donor);

    /**
     * @return bool
     * @since 1.5
     */
    public function canReadEntity(User $subject, Entity $resource);

    /**
     * @return bool
     * @since 1.5
     */
    public function canEditEntity(User $subject, Entity $resource);

    /**
     * @return bool
     * @since 1.5
     */
    public function canDeleteEntity(User $subject, Entity $resource);

    /**
     * @return bool
     * @since 1.5
     */
    public function isUserAllowed(User $account, $resource = null, $privilege = null);
}
