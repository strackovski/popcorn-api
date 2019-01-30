<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AuthenticationSuccessListener
 *
 * @package      App\EventListener
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class AuthenticationSuccessListener
{
    /** @var JWTEncoderInterface */
    private $jwtEncoder;

    /**
     * @param JWTEncoderInterface $jwt
     *
     * @internal param UserManager $userManager
     */
    public function __construct(JWTEncoderInterface $jwt)
    {
        $this->jwtEncoder = $jwt;
    }

    /**
     * Authentication success event handler
     *
     * User successfully logged in, generate refresh tokens
     *
     * @param AuthenticationSuccessEvent $event
     *
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException*@throws \Exception
     * @throws \Exception
     * @throws \Exception
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $refreshToken = [
            'iat' => (new \DateTime())->getTimestamp(),
            'exp' => (new \DateTime())->add(new \DateInterval('P6M'))->getTimestamp(),
            'sub' => $user->getId(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ];

        $data['email'] = $user->getEmail();
        $data['refresh_token'] = $this->jwtEncoder->encode($refreshToken);
        $data['valid_until'] = strtotime('+1 day');
        $data['ttl'] = 86400;

        $event->setData($data);
    }
}
