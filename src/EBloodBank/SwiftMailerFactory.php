<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank;

use Swift_Mailer;
use Swift_SmtpTransport;
use Psr\Container\ContainerInterface;

/**
 * @since 1.6
 */
class SwiftMailerFactory
{
    /**
     * @param  ContainerInterface $container
     * @return \Swift_Mailer
     * @since  1.6
     */
    public function __invoke(ContainerInterface $container)
    {
        $transport = Swift_SmtpTransport::newInstance();
        $mailer = Swift_Mailer::newInstance($transport);

        return $mailer;
    }
}
