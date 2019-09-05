<?php
/**
 * @package    EBloodBank
 * @subpackage Traits
 * @since      1.6
 */
namespace EBloodBank\Traits;

/**
 * Helper methods to get the repository of the application entities.
 *
 * @since 1.6
 */
trait EntityRepositoryTrait
{
    use EntityManagerTrait;

    /**
     * @return \EBloodBank\Models\UserRepository
     * @since  1.6
     */
    public function getUserRepository()
    {
        static $repository = null;

        if (is_null($repository)) {
            $repository = $this->getEntityManager()->getRepository('Entities:User');
        }

        return $repository;
    }

    /**
     * @return \EBloodBank\Models\CityRepository
     * @since  1.6
     */
    public function getCityRepository()
    {
        static $repository = null;

        if (is_null($repository)) {
            $repository = $this->getEntityManager()->getRepository('Entities:City');
        }

        return $repository;
    }

    /**
     * @return \EBloodBank\Models\DistrictRepository
     * @since  1.6
     */
    public function getDistrictRepository()
    {
        static $repository = null;

        if (is_null($repository)) {
            $repository = $this->getEntityManager()->getRepository('Entities:District');
        }

        return $repository;
    }

    /**
     * @return \EBloodBank\Models\DonorRepository
     * @since  1.6
     */
    public function getDonorRepository()
    {
        static $repository = null;

        if (is_null($repository)) {
            $repository = $this->getEntityManager()->getRepository('Entities:Donor');
        }

        return $repository;
    }

    /**
     * @return \EBloodBank\Models\VariableRepository
     * @since  1.6
     */
    public function getVariableRepository()
    {
        static $repository = null;

        if (is_null($repository)) {
            $repository = $this->getEntityManager()->getRepository('Entities:Variable');
        }

        return $repository;
    }
}
