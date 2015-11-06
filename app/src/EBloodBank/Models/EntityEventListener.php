<?php
/**
 * Entity event listener class file
 *
 * @package    EBloodBank
 * @subpackage Models
 * @since      1.2
 */
namespace EBloodBank\Models;

use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Entity event listener
 *
 * @since 1.2
 */
class EntityEventListener
{
    /**
     * @return void
     * @since 1.2
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        $classMetaData = $em->getClassMetadata(get_class($entity));
        $classReflection = $classMetaData->getReflectionClass();

        if (in_array('EBloodBank\Traits\EntityMeta', $classReflection->getTraitNames())) {
            $entity->setMetaEntityManager($em);
        }
    }
}
