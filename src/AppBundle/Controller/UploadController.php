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

class UploadController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/user/{id}/upload_cv", name="user_upload_cv")
     * @Method({"POST"})
     * @Security("has_role('ROLE_USER')")
     * @ApiDoc(
     *  description="Upload CV on user",
     *  section="User",
     *  input={
     *   "class"="AppBundle\Form\UserUploadCVType",
     *  },
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function uploadCVAction(Request $request, User $user)
    {
        $uploadForm = $this->createForm('AppBundle\Form\UserUploadCVType', $user);

        $uploadForm->submit(json_decode($request->getContent(), true));

        if($uploadForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse($this->get('serializer')->normalize($user), 200);
        } else {
            $formErrorsRecuperator = $this->get('AppBundle\Service\FormErrorsRecuperator');
            $errors = $formErrorsRecuperator->getFormErrors($uploadForm);
            $formErrorRenderer = $this->get('AppBundle\Service\FormErrorsRenderer');
            $data = $formErrorRenderer->renderErrors($errors);
            return new JsonResponse($data, 400);
        }
    }
}
