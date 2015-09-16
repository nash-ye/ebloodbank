<?php
namespace EBloodBank;

use InvalidArgumentException;
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
    public static function addOption($name, $value, $sanitize = false, $validate = true)
    {
        $em = main()->getEntityManager();

        if (empty($em)) {
            return false;
        }

        if ($sanitize) {
            $value = self::sanitizeOption($name, $value);
        }

        if ($validate && ! self::validateOption($name, $value)) {
            return false;
        }

        $var = $em->find('Entities:Variable', $name);

        if (! empty($var)) {
            return false;
        }

        $var = new Variable();
        $var->set('name', $name, $sanitize, $validate);
        $var->set('value', $value, $sanitize, $validate);

        $em->persist($var);
        $em->flush();

        return true;
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function updateOption($name, $value, $sanitize = false, $validate = true)
    {
        $em = main()->getEntityManager();

        if (empty($em)) {
            return false;
        }

        if ($sanitize) {
            $value = self::sanitizeOption($name, $value);
        }

        if ($validate && ! self::validateOption($name, $value)) {
            return false;
        }

        $var = $em->find('Entities:Variable', $name);

        if (empty($var)) {
            return false;
        }

        $var->set('value', $value, $sanitize, $validate);

        $em->flush($var);

        return true;
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    public static function submitOption($name, $value, $sanitize = false, $validate = true)
    {
        $em = main()->getEntityManager();

        if (empty($em)) {
            return false;
        }

        if ('' === strval($value)) {
            return self::deleteOption($name);
        } else{
            $currentOption = self::getOption($name);
            if (is_null($currentOption)) {
                return self::addOption($name, $value, $sanitize, $validate);
            } else {
                return self::updateOption($name, $value, $sanitize, $validate);
            }
        }
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

    /**
     * @return mixed
     * @since 1.0
     * @static
     */
    static public function sanitizeOption($name, $value)
    {
        switch ($name) {
            case 'site_url':
                $value = sanitizeURL($value);
                break;
            case 'site_name':
            case 'site_slogan':
            case 'site_locale':
                $value = trim(sanitizeSpecialChars($value));
                break;
            case 'site_email':
                $value = sanitizeEmail($value);
                break;
            case 'self_registration':
                break;
            case 'default_user_role':
                break;
            case 'default_user_status':
                break;
            case 'entities_per_page':
                $value = sanitizeInteger($value);
                break;
        }
        return $value;
    }

    /**
     * @return bool
     * @since 1.0
     * @static
     */
    static public function validateOption($name, $value)
    {
        switch ($name) {
            case 'site_url':
                if (empty($value) || ! isValidURL($value)) {
                    throw new InvalidArgumentException(__('Invalid site URL.'));
                }
                break;
            case 'site_name':
                if (empty($value) || ! is_string($value)) {
                    throw new InvalidArgumentException(__('Invalid site name.'));
                }
                break;
            case 'site_slogan':
                break;
            case 'site_locale':
                if (! empty($value)) {
                    $locales = Locales::getAvailableLocales();
                    if (empty($locales) || ! isset($locales[$value])) {
                        throw new InvalidArgumentException(__('Invalid site locale.'));
                    }
                }
                break;
            case 'site_email':
                if (empty($value) || ! isValidEmail($value)) {
                    throw new InvalidArgumentException(__('Invalid site e-mail address.'));
                }
                break;
            case 'self_registration':
                if (empty($value) || ! in_array($value, ['on', 'off'])) {
                    throw new InvalidArgumentException(__('Invalid self-registration status.'));
                }
                break;
            case 'default_user_role':
                if (empty($value) || ! Roles::isExists($value)) {
                    throw new InvalidArgumentException(__('Invalid default user role.'));
                }
                break;
            case 'default_user_status':
                if (empty($value) || ! in_array($value, ['pending', 'activated'])) {
                    throw new InvalidArgumentException(__('Invalid default user status.'));
                }
                break;
            case 'entities_per_page':
                if (empty($value) || ! isValidInteger($value) || $value < 1) {
                    throw new InvalidArgumentException(__('Invalid entities per page count.'));
                }
                break;
        }
        return true;
    }
}
