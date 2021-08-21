<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/users/me", name="api_get_current_user", methods={"GET"})
     * 
     * @return JsonResponse
     */
    public function getCurrentUser()
    {
        return $this->json(
            [
                'currDate' => new DateTime(),
                'email' => $this->getUser() ? $this->getUser()->getEmail() : null,
                'userId' => $this->getUser() ? $this->getUser()->getId() : null
            ]
        );
    }
    /**
     * @Route("/users/register", name="api_register_user", methods={"POST"})
     * 
     * @return JsonResponse
     */
    public function registerUser(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        $dataRequest = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($dataRequest['email']);
        $roles[] = 'ROLE_USER';
        $user->setRoles($roles);
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $dataRequest['password']
            )
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('piotrzakrzewski@piotrzakrzewski89.pl')
            ->setTo($dataRequest['email'])
            ->setBody(
                $this->renderView(
                    'emails/registration.html.twig'
                ),
                'text/html'
            );

        $mailer->send($message);

        return new JsonResponse(array('User Created' ,$dataRequest['email'] ));
    }
}
