<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Summary;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Summary controller.
 *
 * @Route("summary")
 */
class SummaryController extends Controller
{
    /**
     * Lists all summary entities.
     *
     * @Route("/", name="summary_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $summaries = $em->getRepository('AppBundle:Summary')->findAll();

        if(empty($summaries)){
            return new Response("No summary registered !", 404);
        } else {
            $data = $this->get('serializer')->normalize([
                'summaries' => $summaries
            ]);
            return new JsonResponse($data, 200);
        }
    }

    /**
     * Creates a new summary entity.
     *
     * @Route("/new", name="summary_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $summary = new Summary();
        $form = $this->createForm('AppBundle\Form\SummaryType', $summary);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($summary);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($summary), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($form);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);

            return new JsonResponse($data, 400);
        }
    }

    /**
     * Finds and displays a summary entity.
     *
     * @Route("/{id}", name="summary_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $summary = $this->getDoctrine()->getRepository('AppBundle:Summary')->findOneById($id);
        if ($summary === null) {
            return new Response("Summary not found", 404);
        }
        $data = $this->get('serializer')->normalize([
            'summary' => $summary
        ]);

        return new JsonResponse($data, 200);
    }

    /**
     * Displays a form to edit an existing summary entity.
     *
     * @Route("/{id}/edit", name="summary_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Summary $summary)
    {
        $editForm = $this->createForm('AppBundle\Form\SummaryType', $summary);
        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($summary);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($summary), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Deletes a summary entity.
     *
     * @Route("/{id}", name="summary_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $summary = $em->getRepository('AppBundle:Summary')
            ->find($request->get('id'));

        if ($summary) {
            $em->remove($summary);
            $em->flush();

            return new Response("Summary " . $request->get('id') . " was deleted !", 200 );
        } else {
            return new Response("Summary " . $request->get('id') . " doesn't exist !", 404);
        }
    }
}
