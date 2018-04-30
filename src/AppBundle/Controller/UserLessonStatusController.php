<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserLessonStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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

        $userLessonStatuses = $em->getRepository('AppBundle:UserLessonStatus')->findAll();

        return $this->render('userlessonstatus/index.html.twig', array(
            'userLessonStatuses' => $userLessonStatuses,
        ));
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userLessonStatus);
            $em->flush();

            return $this->redirectToRoute('userlessonstatus_show', array('id' => $userLessonStatus->getId()));
        }

        return $this->render('userlessonstatus/new.html.twig', array(
            'userLessonStatus' => $userLessonStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a userLessonStatus entity.
     *
     * @Route("/{id}", name="userlessonstatus_show")
     * @Method("GET")
     */
    public function showAction(UserLessonStatus $userLessonStatus)
    {
        $deleteForm = $this->createDeleteForm($userLessonStatus);

        return $this->render('userlessonstatus/show.html.twig', array(
            'userLessonStatus' => $userLessonStatus,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing userLessonStatus entity.
     *
     * @Route("/{id}/edit", name="userlessonstatus_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UserLessonStatus $userLessonStatus)
    {
        $deleteForm = $this->createDeleteForm($userLessonStatus);
        $editForm = $this->createForm('AppBundle\Form\UserLessonStatusType', $userLessonStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('userlessonstatus_edit', array('id' => $userLessonStatus->getId()));
        }

        return $this->render('userlessonstatus/edit.html.twig', array(
            'userLessonStatus' => $userLessonStatus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a userLessonStatus entity.
     *
     * @Route("/{id}", name="userlessonstatus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UserLessonStatus $userLessonStatus)
    {
        $form = $this->createDeleteForm($userLessonStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userLessonStatus);
            $em->flush();
        }

        return $this->redirectToRoute('userlessonstatus_index');
    }

    /**
     * Creates a form to delete a userLessonStatus entity.
     *
     * @param UserLessonStatus $userLessonStatus The userLessonStatus entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserLessonStatus $userLessonStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userlessonstatus_delete', array('id' => $userLessonStatus->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
