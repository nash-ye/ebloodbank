<?php
/**
 * Donor Entity Repository
 *
 * @package EBloodBank
 * @subpackage Models
 * @since 1.0
 */
namespace EBloodBank\Models;

use EBloodBank\EntityManager;

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

        if (isset($criteria['blood_group'])) {
            if ('any' === $criteria['blood_group']) {
                unset($criteria['blood_group']);
            }
        }

        if (isset($criteria['district'])) {
            if (-1 === $criteria['district']) {
                unset($criteria['district']);
            }
        }

        if (isset($criteria['city_id'])) {
            $cityID = (int) $criteria['city_id'];
            if (isVaildID($cityID)) {
                $districtsIDs = array();
                $cityRepository = EntityManager::getCityRepository();
                $city = $cityRepository->find($cityID);
                if (! empty($city)) {
                    foreach ($city->get('districts') as $district) {
                        $districtsIDs[] = (int) $district->get('id');
                    }
                }
                $criteria['district'] = $districtsIDs;
            }
            unset($criteria['city_id']);
        }

        if (! isset($criteria['status'])) {
            if (! isCurrentUserCan('approve_donor')) {
                $criteria['status'] = 'published';
            }
        } elseif ('any' === $criteria['status']) {
            unset($criteria['status']);
        }

        return $criteria;
    }
}
