<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session,ManagerRegistry $doctrine): Response
    {
        $stagiairesNI = $doctrine->getRepository(Stagiaire::class)->findNonInscrits($session->getId());
        
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'stagiairesNI' =>$stagiairesNI,
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
