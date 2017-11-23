<?php

namespace AppBundle\Controller\Security\Authenticator;


use Lcobucci\JWT\Parser;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator {
    public function getCredentials(Request $request){
        if($request->headers->has('Authorization')){
            list($bearer, $token) = explode(' ', $request->headers->get('Authorization'));
            if($bearer != 'Bearer'){
                return false;
            }
            return $token;
        }
        return false;
    }

    public function getUser($credentials, UserProviderInterface $userProvider){
        if(!$credentials){
            throw new CustomUserMessageAuthenticationException('Token manquant');
        }

        try{
            $token = (new Parser())->parse($credentials);
            if($token->isExpired()){
                throw new UnauthorizedHttpException('none', 'Token expiré');
            }
        } catch (InvalidArgumentException $e) {
            throw new CustomUserMessageAuthenticationException('Token invalide');
        }

        return $userProvider->loadUserByUsername($token->getClaim('username'));
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if($exception instanceof UnauthorizedHttpException){
            throw $exception;
        }
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