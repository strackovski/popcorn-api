<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Symfony\Component\Security\Core\Role\Role;

/**
 * Class JWTAuthenticatedListener
 *
 * @package      App\EventListener
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class JWTAuthenticatedListener
{
    /**
     * Handle JWTAuthenticatedEvent event
     *
     * @param JWTAuthenticatedEvent $event
     *
     * @return void
     */
    public function onJWTAuthenticated(JWTAuthenticatedEvent $event): void
    {
        $token = $event->getToken();
        $payload = $event->getPayload();
        $user = $token->getUser();

        if (isset($payload['roles']) && \is_array($payload['roles'])) {
            $roles = array_map(
                function ($roleName) {
                    return new Role($roleName);
                },
                $payload['roles']
            );

            $serialized = serialize(
                [
                    \is_object($user) ? clone $user : $user,
                    true,
                    $roles,
                    null,
                ]
            );

            $token->unserialize($serialized);
        }
    }
}
