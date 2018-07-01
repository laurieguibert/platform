<?php
/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 12:01
 */

namespace AppBundle\Controller\Security;


use AppBundle\Entity\User;
use Lcobucci\JWT\Builder;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Method({"POST"})
     * @ApiDoc(
     *  description="Login form",
     *  section="Security",
     *  resource = true,
     *  input = "AppBundle\Form\UserType",
     *  output = "AppBundle\Entity\User",
     *  statusCodes = {
     *      200 = "Successful",
     *      400 = "Validation errors"
     *  }
     * )
     */
    public function loginAction(Request $request) {
        $user = $this->getUser();
        if(!$user){
            return new Response("Bad credentials !");
        }

        $token = (new Builder())
            ->setIssuedAt(time())
            ->setExpiration(time() + 21600)
            ->set('username', $user->getUsername())
            ->set('email', $user->getEmail())
            ->getToken();

        $data = [
            "status" => "200 (ok)"
        ];

        /*$headers = apache_request_headers();
        $headers['Authorization'] = "Bearer " . $token->__toString();*/

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Authorization', "Bearer " . $token->__toString());

        return $response;
    }

    /**
     * Register new user.
     *
     * @Route("/register", name="registration")
     * @Method({"POST"})
     * @ApiDoc(
     *  description="Register new user",
     *  section="Security",
     *  input={
     *   "class"="AppBundle\Form\UserRegistrationType",
     *  },
     *  output= { "class"=User::class},
     *  statusCodes={
     *     200="Successful",
     *     400="Validation errors"
     *  }
     * )
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserRegistrationType', $user);
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
}