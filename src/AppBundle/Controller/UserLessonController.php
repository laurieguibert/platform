<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserLesson;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Userlesson controller.
 *
 * @Route("userlesson")
 */
class UserLessonController extends Controller
{
    /**
     * Lists all userLesson entities.
     *
     * @Route("/", name="userlesson_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userLessons = $em->getRepository('AppBundle:UserLesson')->findAll();

        if(empty($userLessons)){
            return new Response("No user lessons registered !", 404);
        } else {
            $data = $this->get('serializer')->normalize([
                'userLessons' => $userLessons
            ]);
            return new JsonResponse($data, 200);
        }
    }

    /**
     * Creates a new userLesson entity.
     *
     * @Route("/new", name="userlesson_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $userLesson = new Userlesson();
        $form = $this->createForm('AppBundle\Form\UserLessonType', $userLesson);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userLesson);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($userLesson), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($form);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);

            return new JsonResponse($data, 400);
        }
    }

    /**
     * Finds and displays a userLesson entity.
     *
     * @Route("/{id}", name="userlesson_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $userLesson = $this->getDoctrine()->getRepository('AppBundle:UserLesson')->findOneById($id);
        if ($userLesson === null) {
            return new Response("User lesson not found", 404);
        }
        $data = $this->get('serializer')->normalize([
            'userLesson' => $userLesson
        ]);

        return new JsonResponse($data, 200);
    }

    /**
     * Displays a form to edit an existing userLesson entity.
     *
     * @Route("/{id}/edit", name="userlesson_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UserLesson $userLesson)
    {
        $editForm = $this->createForm('AppBundle\Form\UserLessonType', $userLesson);
        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($userLesson);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($userLesson), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Deletes a userLesson entity.
     *
     * @Route("/{id}", name="userlesson_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $userLesson = $em->getRepository('AppBundle:UserLesson')
            ->find($request->get('id'));

        if ($userLesson) {
            $em->remove($userLesson);
            $em->flush();

            return new Response("User lesson " . $request->get('id') . " was deleted !", 200 );
        } else {
            return new Response("User lesson " . $request->get('id') . " doesn't exist !", 404);
        }
    }
}
