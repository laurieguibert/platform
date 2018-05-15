<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Situation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Situation controller.
 *
 * @Route("situation")
 */
class SituationController extends Controller
{
    /**
     * Lists all situation entities.
     *
     * @Route("/", name="situation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $situations = $em->getRepository('AppBundle:Situation')->findAll();

        if(empty($situations)){
            return new Response("No situation registered !", 404);
        } else {
            $data = $this->get('serializer')->normalize([
                'situations' => $situations
            ]);
            return new JsonResponse($data, 200);
        }
    }

    /**
     * Creates a new situation entity.
     *
     * @Route("/new", name="situation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $situation = new Situation();
        $form = $this->createForm('AppBundle\Form\SituationType', $situation);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($situation);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($situation), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($form);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);

            return new JsonResponse($data, 400);
        }
    }

    /**
     * Finds and displays a situation entity.
     *
     * @Route("/{id}", name="situation_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $situation = $this->getDoctrine()->getRepository('AppBundle:Situation')->findOneById($id);
        if ($situation === null) {
            return new Response("Situation not found", 404);
        }
        $data = $this->get('serializer')->normalize([
            'situation' => $situation
        ]);

        return new JsonResponse($data, 200);
    }

    /**
     * Displays a form to edit an existing situation entity.
     *
     * @Route("/{id}/edit", name="situation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Situation $situation)
    {
        $editForm = $this->createForm('AppBundle\Form\SituationType', $situation);
        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($situation);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($situation), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Deletes a situation entity.
     *
     * @Route("/{id}", name="situation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $situation = $em->getRepository('AppBundle:Situation')
            ->find($request->get('id'));

        if ($situation) {
            $em->remove($situation);
            $em->flush();

            return new Response("Situation " . $request->get('id') . " was deleted !", 200 );
        } else {
            return new Response("Situation " . $request->get('id') . " doesn't exist !", 404);
        }
    }
}
