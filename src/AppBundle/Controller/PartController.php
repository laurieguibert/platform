<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Part;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Part controller.
 *
 * @Route("part")
 */
class PartController extends Controller
{
    /**
     * Lists all part entities.
     *
     * @Route("/", name="part_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $parts = $em->getRepository('AppBundle:Part')->findAll();

        if(empty($parts)){
            return new Response("No parts registered !", 404);
        } else {
            $data = $this->get('serializer')->normalize([
                'parts' => $parts
            ]);
            return new JsonResponse($data, 200);
        }
    }

    /**
     * Creates a new part entity.
     *
     * @Route("/new", name="part_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $part = new Part();
        $form = $this->createForm('AppBundle\Form\PartType', $part);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($part);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($part), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($form);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);

            return new JsonResponse($data, 400);
        }
    }

    /**
     * Finds and displays a part entity.
     *
     * @Route("/{id}", name="part_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $part = $this->getDoctrine()->getRepository('AppBundle:Part')->findOneById($id);
        if ($part === null) {
            return new Response("Part with id " . $id . " doesn't exist", 404);
        }

        $data = $this->get('serializer')->normalize([
            'part' => $part
        ]);

        return new JsonResponse($data, 200);
    }

    /**
     * Displays a form to edit an existing part entity.
     *
     * @Route("/{id}/edit", name="part_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Part $part)
    {
        $editForm = $this->createForm('AppBundle\Form\PartType', $part);
        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($part);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($part), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Deletes a part entity.
     *
     * @Route("/{id}", name="part_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Part $part)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $part = $em->getRepository('AppBundle:Part')
            ->find($request->get('id'));

        if ($part) {
            $em->remove($part);
            $em->flush();

            return new Response("Part " . $request->get('id') . " was deleted !", 200);
        } else {
            return new Response("Part " . $request->get('id') . " not found !", 404);
        }
    }
}
