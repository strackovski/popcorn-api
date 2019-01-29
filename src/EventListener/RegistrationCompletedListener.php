<?php

namespace App\EventListener;

use App\Service\Manager\UserManager;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RegistrationCompletedListener
 *
 * @package App\EventListener
 * @author  Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 */
class RegistrationCompletedListener implements EventSubscriberInterface
{
    /** @var UserManager */
    private $manager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RegistrationCompletedListener constructor.
     *
     * @param UserManager     $manager
     * @param LoggerInterface $logger
     */
    public function __construct(UserManager $manager, LoggerInterface $logger)
    {
        $this->manager = $manager;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_COMPLETED => [
                ['onRegistrationCompleted', -10],
            ],
        ];
    }

    /**
     * @param FilterUserResponseEvent $event
     *
     * @throws \Exception
     */
    public function onRegistrationCompleted(FilterUserResponseEvent $event): void
    {
        try {
            $this->manager->provisionUserDefaults($event->getUser());
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
