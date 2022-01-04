<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserCountController extends AbstractController
{
   public function __invoke(): Response
   {
        return $this->json([
            "test" => "dajan"
        ]);
   }

}
