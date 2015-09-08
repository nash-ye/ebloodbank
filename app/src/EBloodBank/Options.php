<?php
namespace EBloodBank;

use EBloodBank\Models\Variable;

/**
 * @since 1.0
 */
class Options
{
    /**
     * @return mixed
     * @since 1.0
     * @static
     */
    public static function getOption($name)
    {
        $em = main()->getEntityManager();

        if (empty($em)) {
            return;
        }

        $var = $em->find('Entities:Variable', $name);

        if (! empty($var)) {
            return $var->get('value');
        }
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function addOption($name, $value)
    {
        $em = main()->getEntityManager();

        if (empty($em)) {
            return false;
        }

        $var = $em->find('Entities:Variable', $name);

        if (! empty($var)) {
            return false;
        }

        $var = new Variable();
        $var->set('name', $name, true);
        $var->set('value', $value, true);

        $em->persist($var);
        $em->flush();

        return true;
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function updateOption($name, $value)
    {
        $em = main()->getEntityManager();

        if (empty($em)) {
            return false;
        }

        $var = $em->find('Entities:Variable', $name);

        if (empty($var)) {
            return false;
        }

        $var->set('value', $value, true);

        $em->flush($var);

        return true;
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function deleteOption($name)
    {
        $em = main()->getEntityManager();

        if (empty($em)) {
            return false;
        }

        $var = $em->getReference('Entities:Variable', $name);

        if (empty($var)) {
            return false;
        }

        $em->remove($var);
        $em->flush();

        return true;
    }
}
