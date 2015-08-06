<?php
namespace EBloodBank\Models;

use Doctrine\ORM\EntityRepository;

/**
* @since 1.0
*/
class UserRepository extends EntityRepository
{
    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public function findAll()
    {
        if (isCurrentUserCan('approve_user')) {
            return parent::findAll();
        } else {
            return parent::findBy(array( 'user_status' => 'activated' ));
        }
    }
}
