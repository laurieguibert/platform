<?php
/**
 * Created by PhpStorm.
 * User: lguib
 * Date: 22/11/2017
 * Time: 11:45
 */

namespace AppBundle\Controller\Security\Authenticator;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class UserLoginAuthenticator extends AbstractGuardAuthenticator
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getCredentials(Request $request){
        if(!$request->isMethod('POST')){
            throw new MethodNotAllowedHttpException(['POST']);
        }

        $data = json_decode($request->getContent(), true);

        if(isset($data['username']) && isset($data['password'])){
            return array(
                'username' => $data['username'],
                'password' => $data['password'],
            );
        } else {
            throw new CustomUserMessageAuthenticationException("Username ou mot de passe manquant");
        }
    }

    public function getUser($credentials, UserProviderInterface $userProvider){
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if($this->encoder->isPasswordValid($user, $credentials['password'])){
            return true;
        } else {
            throw new CustomUserMessageAuthenticationException('Bad credentials');
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw $authException;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}