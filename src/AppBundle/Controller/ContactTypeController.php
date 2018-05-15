<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contacttype controller.
 *
 * @Route("contacttype")
 */
class ContactTypeController extends Controller
{
    /**
     * Lists all contactType entities.
     *
     * @Route("/", name="contacttype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contactTypes = $em->getRepository('AppBundle:ContactType')->findAll();

        if(empty($contactTypes)){
            return new Response("No contact type registered !", 404);
        } else {
            $data = $this->get('serializer')->normalize([
                'contactTypes' => $contactTypes
            ]);
            return new JsonResponse($data, 200);
        }
    }

    /**
     * Creates a new contactType entity.
     *
     * @Route("/new", name="contacttype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $contactType = new Contacttype();
        $form = $this->createForm('AppBundle\Form\ContactTypeType', $contactType);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactType);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($contactType), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($form);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);

            return new JsonResponse($data, 400);
        }
    }

    /**
     * Finds and displays a contactType entity.
     *
     * @Route("/{id}", name="contacttype_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $contactType = $this->getDoctrine()->getRepository('AppBundle:ContactType')->findOneById($id);
        if ($contactType === null) {
            return new Response("Contact type with id " . $id . " doesn't exist", 404);
        }

        $data = $this->get('serializer')->normalize([
            'contactType' => $contactType
        ]);

        return new JsonResponse($data, 200);
    }

    /**
     * Displays a form to edit an existing contactType entity.
     *
     * @Route("/{id}/edit", name="contacttype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ContactType $contactType)
    {
        $editForm = $this->createForm('AppBundle\Form\ContactTypeType', $contactType);
        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactType);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($contactType), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Deletes a contactType entity.
     *
     * @Route("/{id}", name="contacttype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $contactType = $em->getRepository('AppBundle:ContactType')
            ->find($request->get('id'));

        if ($contactType) {
            $em->remove($contactType);
            $em->flush();

            return new Response("Contact type " . $request->get('id') . " was deleted !", 200);
        } else {
            return new Response("Contact type " . $request->get('id') . " not found !", 404);
        }
    }
}
