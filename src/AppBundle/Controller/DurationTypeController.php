<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DurationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Durationtype controller.
 *
 * @Route("durationtype")
 */
class DurationTypeController extends Controller
{
    /**
     * Lists all durationType entities.
     *
     * @Route("/", name="durationtype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $durationTypes = $em->getRepository('AppBundle:DurationType')->findAll();
        if(empty($durationTypes)){
            return new Response("No duration type registered !");
        } else {
            $data = $this->get('serializer')->normalize([
                'durationTypes' => $durationTypes
            ]);
            return new JsonResponse($data);
        }
    }

    /**
     * Creates a new durationType entity.
     *
     * @Route("/new", name="durationtype_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $durationType = new DurationType();
        $form = $this->createForm('AppBundle\Form\DurationTypeType', $durationType);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($durationType);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($durationType));
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($form);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);

            return new JsonResponse($data, 400);
        }
    }

    /**
     * Finds and displays a durationType entity.
     *
     * @Route("/{id}", name="durationtype_show")
     * @Method({"GET"})
     */
    public function showAction($id)
    {
        $durationType = $this->getDoctrine()->getRepository('AppBundle:DurationType')->findOneById($id);
        if ($durationType === null) {
            return new Response("Le type de durée n'existe pas", 404);
        }
        $data = $this->get('serializer')->normalize([
            'durationType' => $durationType
        ]);

        return new JsonResponse($data);
    }

    /**
     * Displays a form to edit an existing durationType entity.
     *
     * @Route("/{id}/edit", name="durationtype_edit")
     * @Method({"POST"})
     */
    public function editAction(Request $request, DurationType $durationType)
    {
        $editForm = $this->createForm('AppBundle\Form\DurationTypeType', $durationType);
        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($durationType);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($durationType));
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Deletes a durationType entity.
     *
     * @Route("/{id}", name="durationtype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, DurationType $durationType)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $durationType = $em->getRepository('AppBundle:DurationType')
            ->find($request->get('id'));

        if ($durationType) {
            $em->remove($durationType);
            $em->flush();

            return new Response("Le type de durée " . $request->get('id') . " a été supprimé", 202 );
        } else {
            return new Response("Le type de durée " . $request->get('id') . " n'existe pas");
        }
    }
}
