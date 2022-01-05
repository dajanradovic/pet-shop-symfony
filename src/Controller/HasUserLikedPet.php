<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Entity\User;
use App\Entity\PetLike;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class HasUserLikedPet extends AbstractController
{
   public function __invoke(Pet $data, Request $request, ManagerRegistry $doctrine): Response
   {    
       $user_id = json_decode($request->getContent(), true)['user_id'];

       //$pet = $doctrine->getRepository(Pet::class)->findIfUserHasLiked($user_id);

       $petLike = $doctrine->getRepository(PetLike::class)->hasUserLiked($data->getId(), $user_id);

       $entityManager = $doctrine->getManager();

       if($petLike){

            $entityManager->remove($petLike);
            $entityManager->flush();

            return $this->json([
                "message" => 'deleted',
            ]);

       }else{

            $petLike = new PetLike();
            $petLike->setAuthor($doctrine->getRepository(User::class)->find($user_id));
            $petLike->setTarget($data);

            $entityManager->persist($petLike);
            $entityManager->flush();

            return $this->json([
                "message" => 'created',
            ]);
      
       }

       
   }

}
