<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Api controller.
 *
 * @Route("api")
 *
 */
class ApiController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/account", name="api_account")
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user === null) {
            return new Response("User not found !", 404);
        }
        $data = $this->get('serializer')->normalize([
            'user' => $user
        ]);

        return new JsonResponse($data,202);
    }
}
