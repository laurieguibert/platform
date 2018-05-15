<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserLessonStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Userlessonstatus controller.
 *
 * @Route("userlessonstatus")
 */
class UserLessonStatusController extends Controller
{
    /**
     * Lists all userLessonStatus entities.
     *
     * @Route("/", name="userlessonstatus_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userLessonStatus = $em->getRepository('AppBundle:UserLessonStatus')->findAll();

        if(empty($userLessonStatus)){
            return new Response("No user lesson status registered !", 404);
        } else {
            $data = $this->get('serializer')->normalize([
                'userLessonStatus' => $userLessonStatus
            ]);
            return new JsonResponse($data, 200);
        }
    }

    /**
     * Creates a new userLessonStatus entity.
     *
     * @Route("/new", name="userlessonstatus_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $userLessonStatus = new Userlessonstatus();
        $form = $this->createForm('AppBundle\Form\UserLessonStatusType', $userLessonStatus);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userLessonStatus);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($userLessonStatus), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($form);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);

            return new JsonResponse($data, 400);
        }
    }

    /**
     * Finds and displays a userLessonStatus entity.
     *
     * @Route("/{id}", name="userlessonstatus_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $userLessonStatus = $this->getDoctrine()->getRepository('AppBundle:UserLessonStatus')->findOneById($id);
        if ($userLessonStatus === null) {
            return new Response("User lesson status with id " . $id . " doesn't exist", 404);
        }

        $data = $this->get('serializer')->normalize([
            'userLessonStatus' => $userLessonStatus
        ]);

        return new JsonResponse($data, 200);
    }

    /**
     * Displays a form to edit an existing userLessonStatus entity.
     *
     * @Route("/{id}/edit", name="userlessonstatus_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UserLessonStatus $userLessonStatus)
    {
        $editForm = $this->createForm('AppBundle\Form\UserLessonStatusType', $userLessonStatus);
        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($userLessonStatus);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($userLessonStatus), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Deletes a userLessonStatus entity.
     *
     * @Route("/{id}", name="userlessonstatus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $userLessonStatus = $em->getRepository('AppBundle:UserLessonStatus')
            ->find($request->get('id'));

        if ($userLessonStatus) {
            $em->remove($userLessonStatus);
            $em->flush();

            return new Response("User lesson status type " . $request->get('id') . " was deleted !", 200);
        } else {
            return new Response("User lesson status type " . $request->get('id') . " not found !", 404);
        }
    }
}
