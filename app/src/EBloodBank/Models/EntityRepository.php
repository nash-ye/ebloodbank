<?php
/**
 * Entity Repository
 *
 * @package EBloodBank
 * @subpackage Models
 * @since 1.0
 */
namespace EBloodBank\Models;

/**
* @since 1.0
*/
class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Count all entities.
     *
     * @return int
     * @since 1.0
     */
    public function countAll()
    {
        return $this->countBy(array());
    }

    /**
     * Count entities (optionally filtered by a criteria).
     *
     * @return int
     * @since 1.0
     */
    public function countBy($criteria = array())
    {
        $em = $this->getEntityManager();
        $entityName = $this->getEntityName();
        $criteria = $this->parseCriteria($criteria);
        $persister = $em->getUnitOfWork()->getEntityPersister($entityName);

        return $persister->count($criteria);
    }

    /**
     * @return array
     * @since 1.0
     */
    protected function parseCriteria(array $criteria)
    {
        return $criteria;
    }
}
