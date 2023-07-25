<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    #[Route('/formation/{id}', name: 'show_formation')]
    public function show(Formation $formation, FormationRepository $formationRepository): Response{
        $sessions = $formationRepository->findSessions($formation->getId());

        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
            'sessions' => $sessions
        ]);    
    }

    #[Route('/formation', name: 'app_formation')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $formations = $doctrine->getRepository(Formation::class)->findBy([],["nom"=>"DESC"]);
        return $this->render('formation/index.html.twig', [
            'formations' => $formations
        ]);
    }
}
