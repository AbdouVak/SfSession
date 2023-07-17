<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{


    #[Route('/stagiaire/add', name: 'add_stagiaire')]
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]
    public function add(ManagerRegistry $doctrine, Stagiaire $stagiaire = null, Request $request): Response{   
        if(!$stagiaire){
            $stagiaire = new Stagiaire();
        }
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $stagiaire = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($stagiaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire');
        }

        // vue pour afficher formulaire d'ajout
        return $this->render('stagiaire/add.html.twig', [
            'formAddStagiaire' => $form->createView(),
            'edit'=> $stagiaire->getId()
        ]);
    }

    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $stagiaires = $doctrine->getRepository(Stagiaire::class)->findBy([], ["nom" => "DESC"]);
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires
        ]);
    }

    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]
    public function show(Stagiaire $stagiaire, ManagerRegistry $doctrine, StagiaireRepository $stagiaireRepository): Response
    {
        $sessions = $stagiaireRepository->findSessions($stagiaire->getId());
        return $this->render('stagiaire/show.html.twig', [
            "stagiaire" => $stagiaire,
            "sessions" => $sessions
        ]);
    }

}