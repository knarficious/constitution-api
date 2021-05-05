<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JWTCreateListener
 *
 * @author franck
 */
namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTEncodedEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreateListener 
{
   /**
    *
    * @var RequestStack 
    */
    private $requestStack;
    
    /**
     * 
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack) 
    {
        $this->requestStack = $requestStack;
    }
    
    /**
     * 
     * @param JWTCreatedEvent $event
     */
    public function onAuthenticationSuccessResponse(JWTCreatedEvent $event) 
    {
        if (!($request = $event->getRequest())) {
            return;
        }

        $user = $event->getUser();
        $payload = $event->getData();
        $payload['roles'] = $user->getRoles();

        $event->setData($payload);
    }
   
   
}
