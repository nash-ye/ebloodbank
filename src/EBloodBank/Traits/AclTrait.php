<?php
/**
 * @package    EBloodBank
 * @subpackage Traits
 * @since      1.5
 */
namespace EBloodBank\Traits;

use EBloodBank\AclInterface;

/**
 * @since 1.5
 */
trait AclTrait
{
    /**
     * @var \EBloodBank\AclInterface
     * @since 1.5
     */
    protected $acl;

    /**
     * @return \EBloodBank\AclInterface
     * @since 1.5
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @return void
     * @since 1.5
     */
    public function setAcl(AclInterface $acl)
    {
        $this->acl = $acl;
    }
}
