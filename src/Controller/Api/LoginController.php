<?php /** @noinspection PhpDeprecationInspection */

namespace App\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginController
 *
 * @package      App\Controller\Api
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class LoginController extends Controller
{
    /**
     * The security layer will intercept this request
     *
     * @Route("/login")
     * @Method({"POST"})
     *
     * @return Response
     */
    public function getToken(): Response
    {
        return new Response('', 401);
    }

    /**
     * The security layer will intercept this request
     *
     * @Route("/refresh_token")
     * @Method({"POST"})
     *
     * @return Response
     */
    public function refreshToken(): Response
    {
        return new Response('', 401);
    }
}
