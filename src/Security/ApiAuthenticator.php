<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Description of ApiAuthenticator
 *
 * @author Nekon
 */
class ApiAuthenticator extends AbstractGuardAuthenticator{
    //put your code here
    public function checkCredentials($credentials, UserInterface $user) {
        return true;
    }

    public function getCredentials(Request $request) {
        return array('api_key'=>$request->headers->get("API-KEY"));
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        $apiKey = $credentials["api_key"];
        if(!$apiKey){
            return;
        }
        return $userProvider->loadUserByUsername($apiKey);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        $data = array(
            'error'=>TRUE,
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        );
        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        
    }

    public function start(Request $request, AuthenticationException $authException = null) {
        $data = array(
            'error'=>TRUE,
            'message' => 'API Key Authentication is Required'
        );
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request) {
        return $request->headers->has("API-KEY");
    }

    public function supportsRememberMe() {
        
    }

}
