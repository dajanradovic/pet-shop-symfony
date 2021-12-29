<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Form\PetType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\FileUploader;

/**
 * @Route("/pets", requirements={"_locale": "en|es|fr"}, name="pets_")
 */
class PetController extends AbstractController
{
    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {
        $pet = new Pet();
        $form = $this->createForm(PetType::class, $pet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $petsFile = $form->get('pet_photos')->getData();
            
            if ($petsFile) {

                $petPhotosArray = [];

                foreach($petsFile as $petFile){
                    $petFileName = $fileUploader->upload($petFile);
                    $petPhotosArray[] = $petFileName;
                }

                $pet->setPhotos($petPhotosArray);
            
            }
            
            $entityManager = $doctrine->getManager();
            
            $pet = $form->getData();
            $pet->setUser($this->getUser());
            
            $entityManager->persist($pet);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('pet/add.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Pet $id, Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader) 
    {
        // $id->setImageName(null);
        $form = $this->createForm(PetType::class, $id);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $petsFile = $form->get('pet_photos')->getData();
            
            //$id = $form->getData();

            $photosToRemoveArray = explode(',', $request->request->get('pet')['photos_to_remove']);
            $filteredArrayWithRemovedPhotos = $id->filterPhotosToRemove($photosToRemoveArray);
            foreach($photosToRemoveArray as $photo){
                if($photo && $photo != ''){
                    $fileUploader->deletePhoto($photo);
                }
            }

            if ($petsFile) {

                //$petPhotosArray = [];

                foreach($petsFile as $petFile){
                    $petFileName = $fileUploader->upload($petFile);
                    $filteredArrayWithRemovedPhotos[] = $petFileName;
                }
            }
            $id->setPhotos($filteredArrayWithRemovedPhotos);

            
            $entityManager = $doctrine->getManager();
            
            //$id->setUser($this->getUser());
            
           // $entityManager->persist($id);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
        $pet = $form->getData();
        return $this->renderForm('pet/edit.html.twig', [
            'form' => $form,
            'pet' => $pet
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(Pet $id){

        return $this->render('pet/details.html.twig', [
            'pet' => $id
         ]);

    }
    
}
