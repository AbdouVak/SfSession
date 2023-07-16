<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Repository\StagiaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{

    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]
    public function show(Stagiaire $stagiaire, ManagerRegistry $doctrine,StagiaireRepository $stagiaireRepository): Response
    {
        $sessions = $stagiaireRepository->findSessions($stagiaire->getId());
        return $this->render('stagiaire/show.html.twig', [
            "stagiaire" => $stagiaire,
            "sessions" => $sessions
        ]);
    }

    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(): Response
    {
        return $this->render('stagiaire/index.html.twig', [
            'controller_name' => 'StagiaireController',
        ]);
    }
}
