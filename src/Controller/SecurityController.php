<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Psr\Log\LoggerInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/authentication_token", name="authentication_token", methods={"POST"})
     */
    public function login(Request $request)
    {
        $user = $this->getDoctrine()
                ->getRepository('App:User')
                ->findOneBy(['username' => $request->getUser()]);
        
        if (!$user)
        {
            throw $this->createNotFoundException();
        }
        
        $isValid = $this->get('security.password_encoder')
                ->isPasswordValid($user, $request->getPassword());
        
        if (!$isValid)
        {
            throw new BadCredentialsException();
        }
        
        $token = $this->get('lexik_jwt_authentication.encoder')
                ->encode([
                    'username' => $user->getUsername(),
                    'exp' => time() + 3600
                ]);

        return new JsonResponse(['token' => $token]);
    }
    
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger)
    {
        $values = json_decode($request->getContent());
        if(isset($values->username,$values->email,$values->password)) {
            $user = new User();
            $user->setUsername($values->username);
            $user->setEmail($values->email);
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setRoles($user->getRoles());
            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'L\'utilisateur a été créé',
                'values' => $values
            ];

            return new JsonResponse($data, 201);
        }
        else {
            $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner les clés username, email et password',
                'values' => $values
        ];
        return new JsonResponse($data, 500); 
            
        }

    }
}
