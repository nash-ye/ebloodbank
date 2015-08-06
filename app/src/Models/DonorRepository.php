<?php
namespace EBloodBank\Models;

use Doctrine\ORM\EntityRepository;

/**
* @since 1.0
*/
class DonorRepository extends EntityRepository
{
    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public function findAll()
    {
        if (isCurrentUserCan('approve_donor')) {
            return parent::findAll();
        } else {
            return parent::findBy(array( 'donor_status' => 'published' ));
        }
    }
}
