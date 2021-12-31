<?php

namespace App\EventListener;

use App\Entity\User;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Address;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;


class UserEmailUpdateListener
{

    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier){

        $this->emailVerifier = $emailVerifier;
    }

    public function preUpdate(User $user, LifecycleEventArgs $event): void
    {
        if(isset($event->getEntityChangeSet()['email'])){

            $user->setIsVerified(false);
            
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('dajo1986@gmail.com', 'PetShop'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig'));

        }
    }
}