<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SummaryStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('summarystatus/index.html.twig', array(
            'summaryStatuses' => $summaryStatuses,
        ));
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($summaryStatus);
            $em->flush();

            return $this->redirectToRoute('summarystatus_show', array('id' => $summaryStatus->getId()));
        }

        return $this->render('summarystatus/new.html.twig', array(
            'summaryStatus' => $summaryStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a summaryStatus entity.
     *
     * @Route("/{id}", name="summarystatus_show")
     * @Method("GET")
     */
    public function showAction(SummaryStatus $summaryStatus)
    {
        $deleteForm = $this->createDeleteForm($summaryStatus);

        return $this->render('summarystatus/show.html.twig', array(
            'summaryStatus' => $summaryStatus,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing summaryStatus entity.
     *
     * @Route("/{id}/edit", name="summarystatus_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SummaryStatus $summaryStatus)
    {
        $deleteForm = $this->createDeleteForm($summaryStatus);
        $editForm = $this->createForm('AppBundle\Form\SummaryStatusType', $summaryStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('summarystatus_edit', array('id' => $summaryStatus->getId()));
        }

        return $this->render('summarystatus/edit.html.twig', array(
            'summaryStatus' => $summaryStatus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a summaryStatus entity.
     *
     * @Route("/{id}", name="summarystatus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, SummaryStatus $summaryStatus)
    {
        $form = $this->createDeleteForm($summaryStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($summaryStatus);
            $em->flush();
        }

        return $this->redirectToRoute('summarystatus_index');
    }

    /**
     * Creates a form to delete a summaryStatus entity.
     *
     * @param SummaryStatus $summaryStatus The summaryStatus entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SummaryStatus $summaryStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('summarystatus_delete', array('id' => $summaryStatus->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
