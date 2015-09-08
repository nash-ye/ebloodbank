<?php
/**
 * User Entity Repository
 *
 * @package EBloodBank
 * @subpackage Models
 * @since 1.0
 */
namespace EBloodBank\Models;

use EBloodBank as EBB;

/**
* @since 1.0
*/
class UserRepository extends EntityRepository
{
    /**
     * Finds entities by a set of criteria.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array The objects.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $criteria = $this->parseCriteria($criteria);
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Finds a single entity by a set of criteria.
     *
     * @param array $criteria
     * @param array|null $orderBy
     *
     * @return object|null The entity instance or NULL if the entity can not be found.
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $criteria = $this->parseCriteria($criteria);
        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * @return array
     * @since 1.0
     */
    protected function parseCriteria(array $criteria)
    {
        if (! isset($criteria['status'])) {
            if (! EBB\isCurrentUserCan('activate_user')) {
                $criteria['status'] = 'activated';
            }
        } elseif ('any' === $criteria['status']) {
            unset($criteria['status']);
        }

        return $criteria;
    }
}
