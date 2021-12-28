<?php

namespace App\Controller;

use App\Entity\Pet;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $pets = $doctrine->getRepository(Pet::class)->findAll();

        return $this->render('home/index.html.twig', [
            'pets' => $pets,
        ]);
    }
}
