<?php
/**
 * Donor entity repository class file
 *
 * @package    EBloodBank
 * @subpackage Models
 * @since      1.0
 */
namespace EBloodBank\Models;

use EBloodBank as EBB;

/**
 * Donor entity repository class
 *
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
        if (isset($criteria['blood_group_alternatives']) && $criteria['blood_group_alternatives']) {
            if (isset($criteria['blood_group']) && is_string($criteria['blood_group'])) {
                switch ($criteria['blood_group']) {
                    case 'A+':
                        $criteria['blood_group'] = ['A+', 'A-', 'O+', 'O-'];
                        break;
                    case 'A-':
                        $criteria['blood_group'] = ['A-', 'O-'];
                        break;
                    case 'B+':
                        $criteria['blood_group'] = ['B+', 'B-', 'O+', 'O-'];
                        break;
                    case 'B+':
                        $criteria['blood_group'] = ['B+', 'B-', 'O+', 'O-'];
                        break;
                    case 'B-':
                        $criteria['blood_group'] = ['B-', 'O-'];
                        break;
                    case 'O+':
                        $criteria['blood_group'] = ['O+', 'O-'];
                        break;
                    case 'O-':
                        $criteria['blood_group'] = ['O-'];
                        break;
                    case 'AB+':
                        $criteria['blood_group'] = ['any'];
                        break;
                    case 'AB-':
                        $criteria['blood_group'] = ['A-', 'B-', 'O-', 'AB-'];
                        break;
                }
            }
        }

        unset($criteria['blood_group_alternatives']); // Remove the alternative blood-groups criteria.

        if (isset($criteria['blood_group']) && 'any' === $criteria['blood_group']) {
            unset($criteria['blood_group']); // Remove the blood-group criteria.
        }

        if (isset($criteria['district']) && -1 == $criteria['district']) {
            unset($criteria['district']); // Remove the district criteria.
        }

        if (isset($criteria['city']) && empty($criteria['district'])) {
            $districts = [];

            if ($criteria['city'] instanceof City) {
                $districts = $criteria['city']->get('districts');
            } elseif (EBB\isValidID($criteria['city'])) {
                $em = $this->getEntityManager();
                $city = $em->find('Entities:City', $criteria['city']);
                if (! empty($city)) {
                    $districts = $city->get('districts');
                }
            }

            if (! empty($districts)) {
                $criteria['district'] = [];

                foreach ($districts as $district) {
                    $criteria['district'][] = (int) $district->get('id');
                }
            }
        }

        unset($criteria['city']); // Remove the city criteria in any condition.

        if (isset($criteria['status']) && 'any' === $criteria['status']) {
            unset($criteria['status']); // Remove the status criteria.
        }

        return $criteria;
    }
}
