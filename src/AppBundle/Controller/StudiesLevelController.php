<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Situation controller.
 *
 * @Route("studiesLevel")
 */
class StudiesLevelController extends Controller
{
    /**
     * Lists all situation entities.
     *
     * @Route("/", name="studies_level_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $studiesLevel = $em->getRepository('AppBundle:StudiesLevel')->findAll();

        if(empty($studiesLevel)){
            return new Response("No situation registered !", 404);
        } else {
            $data = $this->get('serializer')->normalize([
                'studies_level' => $studiesLevel
            ]);
            return new JsonResponse($data, 200);
        }
    }

}
