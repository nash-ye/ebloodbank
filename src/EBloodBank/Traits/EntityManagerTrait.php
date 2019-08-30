<?php
/**
 * @package    EBloodBank
 * @subpackage Traits
 * @since      1.6
 */
namespace EBloodBank\Traits;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @since 1.6
 */
trait EntityManagerTrait
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     * @since 1.6
     */
    protected $entityManager;

    /**
     * @return \Doctrine\ORM\EntityManagerInterface
     * @since 1.6
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return void
     * @since 0.1
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
