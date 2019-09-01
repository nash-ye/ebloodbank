<?php
/**
 * @package    EBloodBank
 * @subpackage Traits
 * @since      1.6
 */
namespace EBloodBank\Traits;

use Aura\Session\Session;

/**
 * @since 1.6
 */
trait SessionTrait
{
    /**
     * @var \Aura\Session\Session
     * @since 1.6
     */
    protected $session;

    /**
     * @return \Aura\Session\Session
     * @since 1.6
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return void
     * @since 1.6
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }
}
