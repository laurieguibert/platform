<?php
/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 12:01
 */

namespace AppBundle\Controller\Security;


use Lcobucci\JWT\Builder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Method({"POST"})
     */
    public function loginAction(Request $request) {
        $user = $this->getUser();

        $token = (new Builder())
            ->setIssuedAt(time())
            ->setExpiration(time() + 3600)
            ->set('username', $user->getUsername())
            ->set('email', $user->getEmail())
            ->getToken();

        $data = $this->get('serializer')->serialize([
            'token' => $token->__toString()
        ], 'json');

        return new Response($data);
    }
}