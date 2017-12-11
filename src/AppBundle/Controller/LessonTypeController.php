<?php

namespace AppBundle\Controller;

use AppBundle\Entity\LessonType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lessontype controller.
 *
 * @Route("lessontype")
 */
class LessonTypeController extends Controller
{
    /**
     * Lists all lessonType entities.
     *
     * @Route("/", name="lessontype_index")
     * @Method("GET")
     * @ApiDoc(
     *  description="Show all lessons type",
     *  section="LessonType",
     *  output= {"class"=LessonType::class},
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lessonTypes = $em->getRepository('AppBundle:LessonType')->findAll();
        if(empty($lessonTypes)){
            return new Response("No lesson type registered !", 404);
        } else {
            $data = $this->get('serializer')->normalize([
                'lessonTypes' => $lessonTypes
            ]);
            return new JsonResponse($data, 200);
        }
    }

    /**
     * Creates a new lessonType entity.
     *
     * @Route("/new", name="lessontype_new")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Create new lesson type",
     *  section="LessonType",
     *  input={
     *   "class"="AppBundle\Form\LessonTypeType",
     *  },
     *  output= {"class"=LessonType::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function newAction(Request $request)
    {
        $lessonType = new Lessontype();
        $form = $this->createForm('AppBundle\Form\LessonTypeType', $lessonType);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lessonType);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($lessonType), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($form);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);

            return new JsonResponse($data, 400);
        }
    }

    /**
     * Finds and displays a lessonType entity.
     *
     * @Route("/{id}", name="lessontype_show")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Show a lesson type",
     *  section="LessonType",
     *  output= {"class"=LessonType::class},
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function showAction($id)
    {
        $lessonType = $this->getDoctrine()->getRepository('AppBundle:LessonType')->findOneById($id);
        if ($lessonType === null) {
            return new Response("Le type de cours n'existe pas", 404);
        }
        $data = $this->get('serializer')->normalize([
            'lessonType' => $lessonType
        ]);

        return new JsonResponse($data);
    }

    /**
     * Displays a form to edit an existing lessonType entity.
     *
     * @Route("/{id}/edit", name="lessontype_edit")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Update a lesson type",
     *  section="LessonType",
     *  input={
     *   "class"="AppBundle\Form\LessonTypeType",
     *  },
     *  output= { "class"=LessonType::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function editAction(Request $request, LessonType $lessonType)
    {
        $editForm = $this->createForm('AppBundle\Form\LessonTypeType', $lessonType);
        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($lessonType);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($lessonType));
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Deletes a lessonType entity.
     *
     * @Route("/{id}", name="lessontype_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Delete a lesson type",
     *  section="LessonType",
     *  statusCodes={
     *     200="Successful",
     *     400="Not found"
     *  }
     * )
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $lessonType = $em->getRepository('AppBundle:LessonType')
            ->find($request->get('id'));

        if ($lessonType) {
            $em->remove($lessonType);
            $em->flush();

            return new Response("Le type de cours " . $request->get('id') . " a été supprimé", 202 );
        } else {
            return new Response("Le type de cours " . $request->get('id') . " n'existe pas");
        }
    }
}
