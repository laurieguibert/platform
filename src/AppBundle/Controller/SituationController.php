<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Situation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('situation/index.html.twig', array(
            'situations' => $situations,
        ));
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($situation);
            $em->flush();

            return $this->redirectToRoute('situation_show', array('id' => $situation->getId()));
        }

        return $this->render('situation/new.html.twig', array(
            'situation' => $situation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a situation entity.
     *
     * @Route("/{id}", name="situation_show")
     * @Method("GET")
     */
    public function showAction(Situation $situation)
    {
        $deleteForm = $this->createDeleteForm($situation);

        return $this->render('situation/show.html.twig', array(
            'situation' => $situation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing situation entity.
     *
     * @Route("/{id}/edit", name="situation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Situation $situation)
    {
        $deleteForm = $this->createDeleteForm($situation);
        $editForm = $this->createForm('AppBundle\Form\SituationType', $situation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('situation_edit', array('id' => $situation->getId()));
        }

        return $this->render('situation/edit.html.twig', array(
            'situation' => $situation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a situation entity.
     *
     * @Route("/{id}", name="situation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Situation $situation)
    {
        $form = $this->createDeleteForm($situation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($situation);
            $em->flush();
        }

        return $this->redirectToRoute('situation_index');
    }

    /**
     * Creates a form to delete a situation entity.
     *
     * @param Situation $situation The situation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Situation $situation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('situation_delete', array('id' => $situation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
