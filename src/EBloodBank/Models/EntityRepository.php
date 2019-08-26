<?php
/**
 * Abstract entity repository class file
 *
 * @package    eBloodBank
 * @subpackage Models
 * @since      1.0
 */
namespace EBloodBank\Models;

/**
 * Abstract entity repository class
 *
 * @since 1.0
 */
abstract class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Count all entities.
     *
     * @return int
     * @since 1.0
     */
    public function countAll()
    {
        return $this->countBy([]);
    }

    /**
     * Count entities (optionally filtered by a criteria).
     *
     * @return int
     * @since 1.0
     */
    public function countBy($criteria = [])
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
