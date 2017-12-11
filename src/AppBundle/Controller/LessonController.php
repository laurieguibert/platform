<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Lesson controller.
 *
 * @Route("lesson")
 */
class LessonController extends Controller
{
    /**
     * Lists all lesson entities.
     *
     * @Route("/", name="lesson_index")
     * @Method("GET")
     * @ApiDoc(
     *  description="Show all lessons",
     *  section="Lesson",
     *  output= {"class"=Lesson::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lessons = $em->getRepository('AppBundle:Lesson')->findAll();
        $data = $this->get('serializer')->normalize([
            'lessons' => $lessons
        ]);
        return new JsonResponse($data);
    }

    /**
     * Creates a new lesson entity.
     *
     * @Route("/new", name="lesson_new")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Create new lesson",
     *  section="Lesson",
     *  input={
     *   "class"="AppBundle\Form\LessonType",
     *  },
     *  output= {"class"=Lesson::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function newAction(Request $request)
    {
        $lesson = new Lesson();
        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();
        $lesson->setName($data['name']);
        $lesson->setDescription($data['description']);
        $lesson->setDuration($data['duration']);
        $lesson->setCertificate($data['certificate']);
        $lesson_level = $em->getRepository('AppBundle:Level')->find($data['level']);
        $lesson_duration_type = $em->getRepository('AppBundle:DurationType')->find($data['duration_type']);
        $lesson_sector = $em->getRepository('AppBundle:Sector')->find($data['sector']);
        $lesson_type = $em->getRepository('AppBundle:LessonType')->find($data['lesson_type']);
        $lesson->setLessonType($lesson_type);
        $lesson->setLevel($lesson_level);
        $lesson->setSector($lesson_sector);
        $lesson->setDurationType($lesson_duration_type);
        $validator = $this->get('validator');
        $errors = $validator->validate($lesson);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new Response($errorsString);
        }
        $em->persist($lesson);
        $em->flush();
        return new JsonResponse($this->get('serializer')->normalize($lesson), 200);
    }

    /**
     * Finds and displays a lesson entity.
     *
     * @Route("/{id}", name="lesson_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     * @ApiDoc(
     *  description="Show a lesson",
     *  section="Lesson",
     *  output= {"class"=Lesson::class},
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function showAction($id)
    {
        $lesson = $this->getDoctrine()->getRepository('AppBundle:Lesson')->findOneById($id);
        if ($lesson === null) {
            return new Response("Lesson with id " . $id . " doesn't exist", 404);
        }

        $data = $this->get('serializer')->normalize([
            'lesson' => $lesson
        ]);

        return new JsonResponse($data, 200);
    }

    /**
     * Displays a form to edit an existing lesson entity.
     *
     * @Route("/{id}/edit", name="lesson_edit")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Update a lesson",
     *  section="Lesson",
     *  input={
     *   "class"="AppBundle\Form\LessonType",
     *  },
     *  output= { "class"=Lesson::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function editAction(Request $request, Lesson $lesson)
    {
        $editForm = $this->createForm('AppBundle\Form\LessonType', $lesson);

        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($lesson);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($lesson), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Deletes a lesson entity.
     *
     * @Route("/{id}", name="lesson_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Delete a lesson",
     *  section="Lesson",
     *  statusCodes={
     *     200="Successful",
     *     400="Not found"
     *  }
     * )
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $lesson = $em->getRepository('AppBundle:Lesson')
            ->find($request->get('id'));

        if ($lesson) {
            $em->remove($lesson);
            $em->flush();

            return new Response("Lesson " . $request->get('id') . " was deleted !", 200);
        } else {
            return new Response("Lesson " . $request->get('id') . " not found !", 404);
        }
    }

    /**
     * Finds and displays lessons by sector.
     *
     * @Route("/sector/{name}", name="lesson_by_sector")
     * @Method("GET")
     * @ApiDoc(
     *  description="Show lessons by sector",
     *  section="Lesson",
     *  output= {"class"=Lesson::class},
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     *
     */
    public function getBySectorName($name){
        $sector = $this->getDoctrine()->getManager()->getRepository('AppBundle:Sector')->findByName($name);
        $lessons = $this->getDoctrine()->getManager()->getRepository('AppBundle:Lesson')->findBySector($sector);
        if(!$lessons){
            return new Response("No lesson registered for sector " . $name . " !", 404);
        }

        return new JsonResponse($this->get('serializer')->normalize($lessons), 200);
    }

    /**
     * Finds and displays lessons by duration type and duration.
     *
     * @Route("/duration/{duration}/{type}", name="lesson_by_duration")
     * @Method("GET")
     * @ApiDoc(
     *  description="Show lessons by duration and duration type",
     *  section="Lesson",
     *  output= {"class"=Lesson::class},
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     *
     */
    public function getByDuration($duration, $type){
        $durationType = $this->getDoctrine()->getManager()->getRepository('AppBundle:DurationType')->findOneByName($type);
        if(!$durationType){
            return new Response("The duration type '" . $type . "' doesn't exist !", 404);
        }
        $lessons = $this->getDoctrine()->getRepository('AppBundle:Lesson')->getByDurationAndDurationType($duration, $durationType->getId());
        if(!$lessons){
            return new Response("No lesson registered for duration '" . $duration . " " . $type . "' !", 404);
        }

        return new JsonResponse($this->get('serializer')->normalize($lessons), 200);
    }
}
