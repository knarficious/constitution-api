<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * Description of UserDataPersister
 *
 * @author franck
 */
class UserDataPersister implements DataPersisterInterface 
{
    private $entityManager;
    private $userPasswordEncoder;
    private $mailer;
    
    public function __construct(
            EntityManagerInterface $entityManager,
            UserPasswordEncoderInterface $userPasswordEncoder,
            MailerInterface $mailer) 
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->mailer =$mailer;
    }

    /**
     * 
     * @param User $data
     */
    public function persist($data) 
    {
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }
        
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        
        $this->sendEmail($data);
    }

    public function remove($data) 
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

    public function supports($data): bool 
    {
        return $data instanceof User;
    }
    
    private function sendEmail(User $user)
    {
        $email = (new Email())
                ->from('admin@franckruer.fr')
                ->to($user->getEmail())
                ->subject('Bienvenue')
                ->html('<p>Vous avez créer un compte sur la plateforme </p><p>Bienvenue</p>');
        
        $this->mailer->send($email);
    }

}
