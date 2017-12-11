<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DurationType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Show all durations type",
     *  section="Duration",
     *  output= { "class"=DurationType::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Not found"
     *  }
     * )
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $durationTypes = $em->getRepository('AppBundle:DurationType')->findAll();
        if(empty($durationTypes)){
            return new Response("No duration type registered !", 400);
        } else {
            $data = $this->get('serializer')->normalize([
                'durationTypes' => $durationTypes
            ]);
            return new JsonResponse($data, 200);
        }
    }

    /**
     * Creates a new durationType entity.
     *
     * @Route("/new", name="durationtype_new")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Create new duration type",
     *  section="Duration",
     *  input={
     *   "class"="AppBundle\Form\DurationTypeType",
     *  },
     *  output= { "class"=DurationType::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
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
            return new JsonResponse($this->get('serializer')->normalize($durationType), 200);
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
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Show a duration type",
     *  section="Duration",
     *  output= { "class"=DurationType::class},
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function showAction($id)
    {
        $durationType = $this->getDoctrine()->getRepository('AppBundle:DurationType')->findOneById($id);
        if ($durationType === null) {
            return new Response("Duration type not found", 404);
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
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Update a duration type",
     *  section="Duration",
     *  input={
     *   "class"="AppBundle\Form\DurationTypeType",
     *  },
     *  output= { "class"=DurationType::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function editAction(Request $request, DurationType $durationType)
    {
        $editForm = $this->createForm('AppBundle\Form\DurationTypeType', $durationType);
        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($durationType);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($durationType), 200);
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
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Delete a duration type",
     *  section="Duration",
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $durationType = $em->getRepository('AppBundle:DurationType')
            ->find($request->get('id'));

        if ($durationType) {
            $em->remove($durationType);
            $em->flush();

            return new Response("Duration type " . $request->get('id') . " was deleted !", 200 );
        } else {
            return new Response("Duration type " . $request->get('id') . " not found !", 404);
        }
    }
}
