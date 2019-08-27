<?php

namespace EBloodBank\Proxies\__CG__\EBloodBank\Models;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class User extends \EBloodBank\Models\User implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = [];



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', 'id', 'name', 'email', 'pass', 'role', 'created_at', 'status', 'meta'];
        }

        return ['__isInitialized__', 'id', 'name', 'email', 'pass', 'role', 'created_at', 'status', 'meta'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (User $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function isExists()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isExists', []);

        return parent::isExists();
    }

    /**
     * {@inheritDoc}
     */
    public function getRole()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRole', []);

        return parent::getRole();
    }

    /**
     * {@inheritDoc}
     */
    public function getRoleTitle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRoleTitle', []);

        return parent::getRoleTitle();
    }

    /**
     * {@inheritDoc}
     */
    public function canAddUser()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canAddUser', []);

        return parent::canAddUser();
    }

    /**
     * {@inheritDoc}
     */
    public function canViewUsers()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canViewUsers', []);

        return parent::canViewUsers();
    }

    /**
     * {@inheritDoc}
     */
    public function canViewUser(\EBloodBank\Models\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canViewUser', [$user]);

        return parent::canViewUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function canEditUsers()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canEditUsers', []);

        return parent::canEditUsers();
    }

    /**
     * {@inheritDoc}
     */
    public function canEditUser(\EBloodBank\Models\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canEditUser', [$user]);

        return parent::canEditUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function canDeleteUsers()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canDeleteUsers', []);

        return parent::canDeleteUsers();
    }

    /**
     * {@inheritDoc}
     */
    public function canDeleteUser(\EBloodBank\Models\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canDeleteUser', [$user]);

        return parent::canDeleteUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function canActivateUsers()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canActivateUsers', []);

        return parent::canActivateUsers();
    }

    /**
     * {@inheritDoc}
     */
    public function canActivateUser(\EBloodBank\Models\User $user)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canActivateUser', [$user]);

        return parent::canActivateUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function canAddDonor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canAddDonor', []);

        return parent::canAddDonor();
    }

    /**
     * {@inheritDoc}
     */
    public function canViewDonors()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canViewDonors', []);

        return parent::canViewDonors();
    }

    /**
     * {@inheritDoc}
     */
    public function canViewDonor(\EBloodBank\Models\Donor $donor)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canViewDonor', [$donor]);

        return parent::canViewDonor($donor);
    }

    /**
     * {@inheritDoc}
     */
    public function canEditDonors()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canEditDonors', []);

        return parent::canEditDonors();
    }

    /**
     * {@inheritDoc}
     */
    public function canEditDonor(\EBloodBank\Models\Donor $donor)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canEditDonor', [$donor]);

        return parent::canEditDonor($donor);
    }

    /**
     * {@inheritDoc}
     */
    public function canDeleteDonors()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canDeleteDonors', []);

        return parent::canDeleteDonors();
    }

    /**
     * {@inheritDoc}
     */
    public function canDeleteDonor(\EBloodBank\Models\Donor $donor)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canDeleteDonor', [$donor]);

        return parent::canDeleteDonor($donor);
    }

    /**
     * {@inheritDoc}
     */
    public function canApproveDonors()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canApproveDonors', []);

        return parent::canApproveDonors();
    }

    /**
     * {@inheritDoc}
     */
    public function canApproveDonor(\EBloodBank\Models\Donor $donor)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canApproveDonor', [$donor]);

        return parent::canApproveDonor($donor);
    }

    /**
     * {@inheritDoc}
     */
    public function canAddCity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canAddCity', []);

        return parent::canAddCity();
    }

    /**
     * {@inheritDoc}
     */
    public function canViewCities()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canViewCities', []);

        return parent::canViewCities();
    }

    /**
     * {@inheritDoc}
     */
    public function canViewCity(\EBloodBank\Models\City $city)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canViewCity', [$city]);

        return parent::canViewCity($city);
    }

    /**
     * {@inheritDoc}
     */
    public function canEditCities()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canEditCities', []);

        return parent::canEditCities();
    }

    /**
     * {@inheritDoc}
     */
    public function canEditCity(\EBloodBank\Models\City $city)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canEditCity', [$city]);

        return parent::canEditCity($city);
    }

    /**
     * {@inheritDoc}
     */
    public function canDeleteCities()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canDeleteCities', []);

        return parent::canDeleteCities();
    }

    /**
     * {@inheritDoc}
     */
    public function canDeleteCity(\EBloodBank\Models\City $city)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canDeleteCity', [$city]);

        return parent::canDeleteCity($city);
    }

    /**
     * {@inheritDoc}
     */
    public function canAddDistrict()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canAddDistrict', []);

        return parent::canAddDistrict();
    }

    /**
     * {@inheritDoc}
     */
    public function canViewDistricts()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canViewDistricts', []);

        return parent::canViewDistricts();
    }

    /**
     * {@inheritDoc}
     */
    public function canViewDistrict(\EBloodBank\Models\District $district)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canViewDistrict', [$district]);

        return parent::canViewDistrict($district);
    }

    /**
     * {@inheritDoc}
     */
    public function canEditDistricts()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canEditDistricts', []);

        return parent::canEditDistricts();
    }

    /**
     * {@inheritDoc}
     */
    public function canEditDistrict(\EBloodBank\Models\District $district)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canEditDistrict', [$district]);

        return parent::canEditDistrict($district);
    }

    /**
     * {@inheritDoc}
     */
    public function canDeleteDistricts()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canDeleteDistricts', []);

        return parent::canDeleteDistricts();
    }

    /**
     * {@inheritDoc}
     */
    public function canDeleteDistrict(\EBloodBank\Models\District $district)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canDeleteDistrict', [$district]);

        return parent::canDeleteDistrict($district);
    }

    /**
     * {@inheritDoc}
     */
    public function canEditSettings()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'canEditSettings', []);

        return parent::canEditSettings();
    }

    /**
     * {@inheritDoc}
     */
    public function hasCapability($cap)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasCapability', [$cap]);

        return parent::hasCapability($cap);
    }

    /**
     * {@inheritDoc}
     */
    public function isPending()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isPending', []);

        return parent::isPending();
    }

    /**
     * {@inheritDoc}
     */
    public function isActivated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isActivated', []);

        return parent::isActivated();
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'get', [$key]);

        return parent::get($key);
    }

    /**
     * {@inheritDoc}
     */
    public function display($key, $format = 'html')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'display', [$key, $format]);

        return parent::display($key, $format);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $sanitize = false, $validate = true)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'set', [$key, $value, $sanitize, $validate]);

        return parent::set($key, $value, $sanitize, $validate);
    }

    /**
     * {@inheritDoc}
     */
    public function getMeta(string $key, $fallback = '')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMeta', [$key, $fallback]);

        return parent::getMeta($key, $fallback);
    }

    /**
     * {@inheritDoc}
     */
    public function setMeta(string $key, $value, $sanitize = false, $validate = true)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMeta', [$key, $value, $sanitize, $validate]);

        return parent::setMeta($key, $value, $sanitize, $validate);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteMeta(string $key)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'deleteMeta', [$key]);

        return parent::deleteMeta($key);
    }

}
