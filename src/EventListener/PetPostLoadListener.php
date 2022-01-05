<?php

namespace App\EventListener;

use App\Entity\Pet;
use App\Entity\User;
use App\Entity\PetLike;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Address;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\Event\LifecycleEventArgs;


class PetPostLoadListener
{

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine){

        $this->doctrine = $doctrine;
    }

    public function postLoad(Pet $pet, LifecycleEventArgs $event): void
    {
        $petLike = $this->doctrine->getRepository(PetLike::class)->hasUserLiked($pet->getId());
       
        if($petLike){

            $pet->setHasUserLiked(true);
        }else{

            $pet->setHasUserLiked(false);
        }

    }

}