<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SummaryStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Summarystatus controller.
 *
 * @Route("summarystatus")
 */
class SummaryStatusController extends Controller
{
    /**
     * Lists all summaryStatus entities.
     *
     * @Route("/", name="summarystatus_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $summaryStatuses = $em->getRepository('AppBundle:SummaryStatus')->findAll();

        if(empty($summaryStatuses)){
            return new Response("No summary status registered !", 404);
        } else {
            $data = $this->get('serializer')->normalize([
                'summaryStatuses' => $summaryStatuses
            ]);
            return new JsonResponse($data, 200);
        }
    }

    /**
     * Creates a new summaryStatus entity.
     *
     * @Route("/new", name="summarystatus_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $summaryStatus = new Summarystatus();
        $form = $this->createForm('AppBundle\Form\SummaryStatusType', $summaryStatus);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($summaryStatus);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($summaryStatus), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($form);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);

            return new JsonResponse($data, 400);
        }
    }

    /**
     * Finds and displays a summaryStatus entity.
     *
     * @Route("/{id}", name="summarystatus_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $summaryStatus = $this->getDoctrine()->getRepository('AppBundle:SummaryStatus')->findOneById($id);
        if ($summaryStatus === null) {
            return new Response("Summary status not found", 404);
        }
        $data = $this->get('serializer')->normalize([
            'summaryStatus' => $summaryStatus
        ]);

        return new JsonResponse($data, 200);
    }

    /**
     * Displays a form to edit an existing summaryStatus entity.
     *
     * @Route("/{id}/edit", name="summarystatus_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SummaryStatus $summaryStatus)
    {
        $editForm = $this->createForm('AppBundle\Form\SummaryStatusType', $summaryStatus);
        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($summaryStatus);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($summaryStatus), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Deletes a summaryStatus entity.
     *
     * @Route("/{id}", name="summarystatus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $summaryStatus = $em->getRepository('AppBundle:SummaryStatus')
            ->find($request->get('id'));

        if ($summaryStatus) {
            $em->remove($summaryStatus);
            $em->flush();

            return new Response("Summary status " . $request->get('id') . " was deleted !", 200 );
        } else {
            return new Response("Summary status " . $request->get('id') . " doesn't exist !", 404);
        }
    }
}
