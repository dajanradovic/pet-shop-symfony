<?php

namespace App\Controller;

use App\Form\UserEditType;
use App\Form\UserAvatarType;
use App\Service\FileUploader;
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
    public function avatar(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $form = $this->createForm(UserAvatarType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form->get('avatar')->getData();
            
            if ($avatar) {

                    $fileUploader->overrideDefaultTargetDirectory($this->getParameter('avatar_images_directory'));

                    if ($user->getAvatar()){
                        
                        $fileUploader->deletePhoto($user->getAvatar());
                    }

                    $avatarFileName = $fileUploader->upload($avatar);
                
                    $user->setAvatar($avatarFileName);
            
            }

            
            $entityManager = $doctrine->getManager();
                        
            $entityManager->flush();

            return $this->redirectToRoute('me_edit');
        }

        return $this->renderForm('user/avatar.html.twig',[
            'form' => $form
        ]);
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher, EmailVerifier $emailVerifier): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

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
