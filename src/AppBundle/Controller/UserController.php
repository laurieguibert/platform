<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Model\ChangePassword;
use AppBundle\Form\UserChangePasswordType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * User controller.
 *
 * @Route("user")
 *
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     * @ApiDoc(
     *  description="Show all users",
     *  section="User",
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();
        if(empty($users)){
            return new Response("No user registered !", 404);
        }

        $data = $this->get('serializer')->normalize([
            'users' => $users
        ]);
        return new JsonResponse($data, 200);
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"POST"})
     * @ApiDoc(
     *  description="Create new user",
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
    public function newAction(Request $request, UserPasswordEncoderInterface $encoder)
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
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     * @ApiDoc(
     *  description="Show a user",
     *  section="User",
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function showAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneById($id);
        if ($user === null) {
            return new Response("User not found !", 404);
        }
        $data = $this->get('serializer')->normalize([
            'user' => $user
        ]);

        return new JsonResponse($data, 200);
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit/password", name="user_edit_password")
     * @Method({"POST"})
     * @Security("has_role('ROLE_USER')")
     * @ApiDoc(
     *  description="Update user password",
     *  section="User",
     *  input={
     *   "class"="AppBundle\Form\UserChangePasswordType",
     *  },
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function updatePasswordAction(Request $request, User $user, UserPasswordEncoderInterface $encoder){
        $changePasswordModel = new ChangePassword();
        $editForm = $this->createForm('AppBundle\Form\UserChangePasswordType', $changePasswordModel);

        $editForm->submit(json_decode($request->getContent(), true));
        $request = json_decode($request->getContent(), true);
        if($editForm->isValid()){
            $user->setPassword($encoder->encodePassword($user, $request['newPassword']['first']));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($user), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"POST"})
     * @Security("has_role('ROLE_USER')")
     * @ApiDoc(
     *  description="Update user data",
     *  section="User",
     *  input={
     *   "class"="AppBundle\Form\UserUpdateType",
     *  },
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function editAction(Request $request, User $user)
    {
        $editForm = $this->createForm('AppBundle\Form\UserUpdateType', $user);

        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($user), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
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

        if ($user) {
            $user->setStatus(0);
            $em->persist($user);
            $em->flush();

            return new Response("User " . $request->get('id') . " was deleted !", 200);
        } else {
            return new Response("User " . $request->get('id') . " not found !", 404);
        }
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}/lessons", name="user_lessons")
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     * @ApiDoc(
     *  description="Show user's lessons",
     *  section="User",
     *  statusCodes={
     *     200="Successful",
     *     404="Not found"
     *  }
     * )
     */
    public function getLessonsAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneById($id);
        if($user === null){
            return new Response("No user registered with this id", 404);
        } else {
            $lessons = $user->getUserLesson();
            if (!isset($lessons)) {
                return new Response("No lesson registered for the user !", 404);
            } else {
                $data = $this->get('serializer')->normalize([
                    'lessons' => $lessons
                ]);

                return new JsonResponse($data, 200);
            }
        }
    }
}
