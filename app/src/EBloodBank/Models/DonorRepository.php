<?php
/**
 * Donor Entity Repository
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
class DonorRepository extends EntityRepository
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

        if (isset($criteria['blood_group']) && 'any' === $criteria['blood_group']) {
            unset($criteria['blood_group']); // Remove the blood-group criteria.
        }

        if (isset($criteria['district']) && -1 == $criteria['district']) {
            unset($criteria['district']); // Remove the district criteria.
        }

        if (isset($criteria['city']) && empty($criteria['district'])) {

            $districts = array();

            if ($criteria['city'] instanceof City) {
                $districts = $criteria['city']->get('districts');
            } elseif (EBB\isValidID($criteria['city'])) {
                $em = $this->getEntityManager();
                $city = $em->find('Entities:City', $criteria['city']);
                if ( ! empty($city)) {
                    $districts = $city->get('districts');
                }
            }

            if (! empty($districts)) {

                $criteria['district'] = array();

                foreach ($districts as $district) {
                    $criteria['district'][] = (int) $district->get('id');
                }

            }

        }

        unset($criteria['city']); // Remove the city criteria in any condition.

        if (! isset($criteria['status'])) {
            if (! EBB\isCurrentUserCan('approve_donor')) {
                $criteria['status'] = 'approved';
            }
        } elseif ('any' === $criteria['status']) {
            unset($criteria['status']); // Remove the status criteria.
        }

        return $criteria;
    }
}
