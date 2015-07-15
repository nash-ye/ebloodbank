<?php
namespace eBloodBank\Models;

use Doctrine\ORM\EntityRepository;

/**
* @since 1.0
*/
class DistrictRepository extends EntityRepository
{

    /**
    * @since 1.0
    */
    public function findByCityID($cityID, $orderBy = null, $limit = null, $offset = null)
    {
        return $this->findBy(array( 'distr_city_id' => (int) $cityID ), $orderBy, $limit, $offset);
    }
}
