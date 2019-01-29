<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JWTCreatedListener
 *
 * @package      App\EventListener
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class JWTCreatedListener
{
    /** @var RequestStack */
    private $requestStack;

    /**
     * JWTCreatedListener constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Handle JWTCreatedEvent event
     *
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        if (!$this->requestStack->getCurrentRequest()) {
            return;
        }

        $payload = $event->getData();
        $user = $event->getUser();
        $payload['roles'] = $user->getRoles();

        $event->setData($payload);
    }
}
