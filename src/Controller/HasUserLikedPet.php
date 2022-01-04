<?php

namespace App\Controller;

use App\Entity\Pet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\Persistence\ManagerRegistry;


class HasUserLikedPet extends AbstractController
{
   public function __invoke(Pet $data, Request $request, ManagerRegistry $doctrine): Pet
   {    
       $user_id = json_decode($request->getContent(), true)['user_id'];

       $pet = $doctrine->getRepository(Pet::class)->findIfUserHasLiked($user_id);
       dd($pet);
       return $data;
   }

}
