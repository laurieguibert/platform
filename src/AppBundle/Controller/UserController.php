<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
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
     * @ApiDoc(
     *  description="Listing des users",
     *  section="User"
     * )
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();
        $data = $this->get('serializer')->normalize([
            'users' => $users
        ]);
        return new JsonResponse($data);
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @ApiDoc(
     *  description="Création d'un user",
     *  section="User"
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
            return new JsonResponse($this->get('serializer')->normalize($user));
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($form);
            $data = [
                'type' => 'validation_error',
                'title' => 'There was a validation error',
                'errors' => $errors
            ];

            return new JsonResponse($data, 400);
        }
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method({"GET"})
     * @ApiDoc(
     *  description="Affichage d'un user",
     *  section="User"
     * )
     */
    public function showAction($id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneById($id);
        if ($user === null) {
            return new Response("L'utilisateur n'existe pas", 404);
        }
        $data = $this->get('serializer')->normalize([
            'user' => $user
        ]);

        return new JsonResponse($data);
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user, UserPasswordEncoderInterface $encoder)
    {
        $editForm = $this->createForm('AppBundle\Form\UserChangePasswordType', $user);

        $editForm->submit(json_decode($request->getContent(), true));

        if($editForm->isValid()){
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($user));
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($editForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($errors, 400);
        }
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('AppBundle:User')
            ->find($request->get('id'));

        if ($user) {
            $em->remove($user);
            $em->flush();

            return new Response("L'utilisateur " . $request->get('id') . " a été supprimé", 202 );
        } else {
            return new Response("L'utilisateur " . $request->get('id') . " n'existe pas");
        }
    }
}
