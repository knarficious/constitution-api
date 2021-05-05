<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;

/**
 * Description of JwtEventSubscriber
 *
 * @author franck
 */
class JwtEventSubscriber implements EventSubscriberInterface
{
    /**
     *
     * @var SerializerInterface 
     */
    private $serializer;
    
    /**
     * 
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer) 
    {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents(): array 
    {
        return [
            Events::JWT_CREATED => 'onTokenCreated'
        ];
    }
    
    /**
     * 
     * @param JWTCreatedEvent $event
     */
    public function onTokenCreated(JWTCreatedEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
        $userData = $this->serializer->serialize( $user, 'json', ['groups' => ['user:read', 'user:write']] );
        $data = array_merge($data, json_decode($userData, true));

        $event->setData($data);
    }

}
