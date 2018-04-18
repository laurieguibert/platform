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

}
