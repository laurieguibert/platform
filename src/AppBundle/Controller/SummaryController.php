<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Summary;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('summary/index.html.twig', array(
            'summaries' => $summaries,
        ));
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($summary);
            $em->flush();

            return $this->redirectToRoute('summary_show', array('id' => $summary->getId()));
        }

        return $this->render('summary/new.html.twig', array(
            'summary' => $summary,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a summary entity.
     *
     * @Route("/{id}", name="summary_show")
     * @Method("GET")
     */
    public function showAction(Summary $summary)
    {
        $deleteForm = $this->createDeleteForm($summary);

        return $this->render('summary/show.html.twig', array(
            'summary' => $summary,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing summary entity.
     *
     * @Route("/{id}/edit", name="summary_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Summary $summary)
    {
        $deleteForm = $this->createDeleteForm($summary);
        $editForm = $this->createForm('AppBundle\Form\SummaryType', $summary);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('summary_edit', array('id' => $summary->getId()));
        }

        return $this->render('summary/edit.html.twig', array(
            'summary' => $summary,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a summary entity.
     *
     * @Route("/{id}", name="summary_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Summary $summary)
    {
        $form = $this->createDeleteForm($summary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($summary);
            $em->flush();
        }

        return $this->redirectToRoute('summary_index');
    }

    /**
     * Creates a form to delete a summary entity.
     *
     * @param Summary $summary The summary entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Summary $summary)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('summary_delete', array('id' => $summary->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
