<?php
/**
 * @package    EBloodBank
 * @subpackage Traits
 * @since      1.6
 */
namespace EBloodBank\Traits;

use Swift_Mailer;

/**
 * @since 1.6
 */
trait MailerTrait
{
    /**
     * @var   \Swift_Mailer
     * @since 1.6
     */
    protected $mailer;

    /**
     * @return \Swift_Mailer
     * @since  1.6
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @return void
     * @since  1.6
     */
    public function setMailer(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
}
