<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Country controller.
 *
 * @Route("country")
 */
class CountryController extends Controller
{
    /**
     * Lists all country entities.
     *
     * @Route("/", name="country_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $countries = $em->getRepository('AppBundle:Country')->findAll();

        $data = $this->get('serializer')->normalize([
            'countries' => $countries
        ]);

        return new JsonResponse($data);
    }

    /**
     * Finds and displays a country entity.
     *
     * @Route("/{id}", name="country_show")
     * @Method("GET")
     */
    public function showAction(Country $country)
    {

        return $this->render('country/show.html.twig', array(
            'country' => $country,
        ));
    }
}
