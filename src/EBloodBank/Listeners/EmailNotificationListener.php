<?php
/**
 * @package EBloodBank
 * @since   1.6
 */
namespace EBloodBank\Listeners;

use Exception;
use EBloodBank\Options;
use EBloodBank\Models\User;
use EBloodBank\Models\Donor;
use EBloodBank\Traits\MailerTrait;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @since 1.6
 */
class EmailNotificationListener
{
    use MailerTrait;

    /**
     * @param  ContainerInterface $container
     * @return EmailNotificationListener
     * @since  1.6
     * @static
     */
    public static function factory(ContainerInterface $container)
    {
        $listener = new static();
        $listener->setMailer($container->get('mailer'));

        return $listener;
    }

    /**
     * @param  GenericEvent $event
     * @return void
     * @since  1.6
     */
    public function __invoke(GenericEvent $event, $eventName)
    {
        try {
            $subject = $event->getSubject();

            if ('donor.created' === $eventName && $subject instanceof Donor) {
                $message = $this->getMailer()->createMessage();
    
                $message->setSubject(sprintf(__('[%s] New Donor'), Options::getOption('site_name')));
                $message->setFrom(Options::getOption('site_email'));
                $message->setTo(Options::getOption('site_email'));
    
                $messageBody  = sprintf(__('New donor addition on %s:'), Options::getOption('site_name')) . "\r\n\r\n";
                $messageBody .= sprintf(__('Name: %s'), $subject->get('name')) . "\r\n";
                $messageBody .= sprintf(__('Gender: %s'), $subject->getGenderTitle()) . "\r\n";
                $messageBody .= sprintf(__('Blood Group: %s'), $subject->get('blood_group')) . "\r\n";
                $messageBody .= sprintf(__('City\District: %1$s\%2$s'), $subject->get('district')->get('city')->get('name'), $subject->get('district')->get('name'));
    
                $message->setBody($messageBody, 'text/plain');
    
                $this->getMailer()->send($message);
            } elseif ('user.created' === $eventName && $subject instanceof User) {
                $message = $this->getMailer()->createMessage();
    
                $message->setSubject(sprintf(__('[%s] New User Registration'), Options::getOption('site_name')));
                $message->setFrom(Options::getOption('site_email'));
                $message->setTo(Options::getOption('site_email'));
    
                $messageBody  = sprintf(__('New user registration on %s:'), Options::getOption('site_name')) . "\r\n\r\n";
                $messageBody .= sprintf(__('Username: %s'), $subject->get('name')) . "\r\n";
                $messageBody .= sprintf(__('E-mail: %s'), $subject->get('email')) . "\r\n";
    
                $message->setBody($messageBody, 'text/plain');
    
                $this->getMailer()->send($message);
            }
        } catch (Exception $ex) {
            // TODO: Log the exception.
        }
    }
}
