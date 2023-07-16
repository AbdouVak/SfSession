<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Repository\SessionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session,ManagerRegistry $doctrine, SessionRepository $sessionRepository): Response
    {
        $stagiairesNI = $sessionRepository->findNonInscrits($session->getId());
        $moduleAjoutable = $sessionRepository->findModule($session->getId());

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'stagiairesNI' =>$stagiairesNI,
            "moduleAjoutable" => $moduleAjoutable
        ]);
    }

    #[Route('/session', name: 'app_session')]
    public function index(): Response
    {
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }

    

    
}
