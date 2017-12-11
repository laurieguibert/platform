<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Level;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Level controller.
 *
 * @Route("level")
 */
class LevelController extends Controller
{
    /**
     * Lists all level entities.
     *
     * @Route("/", name="level_index")
     * @Method("GET")
     * @ApiDoc(
     *  description="Show all levels",
     *  section="Level",
     *  output= {"class"=Level::class},
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $levels = $em->getRepository('AppBundle:Level')->findAll();
        if(empty($levels)){
            return new Response("No level registered !", 404);
        } else {
            $data = $this->get('serializer')->normalize([
                'levels' => $levels
            ]);
            return new JsonResponse($data, 200);
        }
    }

    /**
     * Creates a new level entity.
     *
     * @Route("/new", name="level_new")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Create new level",
     *  section="Level",
     *  input={
     *   "class"="AppBundle\Form\LevelType",
     *  },
     *  output= {"class"=Level::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function newAction(Request $request)
    {
        $level = new Level();
        $form = $this->createForm('AppBundle\Form\LevelType', $level);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($level);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($level), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($form);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);

            return new JsonResponse($data, 400);
        }
    }

    /**
     * Finds and displays a level entity.
     *
     * @Route("/{id}", name="level_show")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Show a level",
     *  section="Level",
     *  output= {"class"=Level::class},
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function showAction($id)
    {
        $level = $this->getDoctrine()->getRepository('AppBundle:Level')->findOneById($id);
        if ($level === null) {
            return new Response("Level not found", 404);
        }
        $data = $this->get('serializer')->normalize([
            'level' => $level
        ]);

        return new JsonResponse($data, 200);
    }

    /**
     * Displays a form to edit an existing level entity.
     *
     * @Route("/{id}/edit", name="level_edit")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Update a level",
     *  section="Level",
     *  input={
     *   "class"="AppBundle\Form\LevelType",
     *  },
     *  output= { "class"=Level::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function editAction(Request $request, Level $level)
    {
        $editForm = $this->createForm('AppBundle\Form\LevelType', $level);
        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($level);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($level), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Deletes a level entity.
     *
     * @Route("/{id}", name="level_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Delete a level",
     *  section="Level",
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $level = $em->getRepository('AppBundle:Level')
            ->find($request->get('id'));

        if ($level) {
            $em->remove($level);
            $em->flush();

            return new Response("Level " . $request->get('id') . " was deleted !", 200 );
        } else {
            return new Response("Level " . $request->get('id') . " doesn't exist !", 404);
        }
    }
}
