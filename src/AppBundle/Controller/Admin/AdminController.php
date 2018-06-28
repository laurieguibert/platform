<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Admin controller.
 *
 * @Route("admin")
 *
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * Lists all user entities.
     *
     * @Route("/users", name="admin_user_index")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Show all users",
     *  section="User",
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function listUsersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBunde:User')->findAll();
        if(empty($users)){
            return new Response("No user registered !", 404);
        }

        $data = $this->get('serializer')->normalize([
            'users' => $users
        ]);
        return new JsonResponse($data, 200);
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/user/{id}", name="admin_user_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Delete user",
     *  section="User",
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function deleteAction(Request $request, User $user)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('AppBundle:User')
            ->find($request->get('id'));

        if ($user) {
            $em->remove($user);
            $em->flush();

            return new Response("User " . $request->get('id') . " was deleted !", 200);
        } else {
            return new Response("User " . $request->get('id') . " not found !", 404);
        }
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/user/new", name="admin_user_new")
     * @Method({"POST"})
     * @ApiDoc(
     *  description="Create new user by admin",
     *  section="User",
     *  input={
     *   "class"="AppBundle\Form\UserType",
     *  },
     *  output= { "class"=User::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function newUserAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->submit(json_decode($request->getContent(), true));

        if($form->isValid()){
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($user), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($form);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * List users asking for training
     *
     * @Route("/user/asking_trainer", name="admin_user_asking_trainer")
     * @Method({"GET"})
     * @ApiDoc(
     *  description="List users asking for training",
     *  section="User",
     *  output= { "class"=User::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function listAskingTrainersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->getUsersAskingTrainer();
        if(empty($users)){
            return new Response("No user waiting for training validation", 404);
        }

        $data = $this->get('serializer')->normalize([
            'users' => $users
        ]);
        return new JsonResponse($data, 200);
    }

}
