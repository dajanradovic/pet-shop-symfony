<?php

namespace App\Controller;

use App\Form\UserEditType;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Address;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/me", requirements={"_locale": "en|es|fr"}, name="me_")
 */
class UserController extends AbstractController
{
        /**
     * @Route("/avatar", name="avatar")
     */
    public function avatar(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher, EmailVerifier $emailVerifier): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            dump("prvi");
            $entityManager = $doctrine->getManager();

            if($form->get('plainPassword')->getData()){

                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                );
                
                
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();
           
            if(!$user->isVerified()){
                
                return $this->redirectToRoute('app_logout');
            }

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('user/edit.html.twig', [
            'form' => $form
        ]);
    }
}
