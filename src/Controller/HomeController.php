<?php

namespace App\Controller;

use App\Entity\Session;
use App\Repository\SessionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(SessionRepository $sessionRepository): Response
    {
        $sessionEnCours = $sessionRepository->findSessionEnCours();
        $sessionFini = $sessionRepository->findSessionFini();
        $sessionAVenir= $sessionRepository->findSessionAVenir();

        return $this->render('home/index.html.twig', [
            "sessionAVenir" => $sessionAVenir,
            "sessionEnCours" => $sessionEnCours,
            "sessionFini" => $sessionFini
                
        ]);
        // $sessions = $doctrine->getRepository(Session::class)->findBy([],["dateDebut"=>"DESC"]);
        // return $this->render('home/index.html.twig', [
        //         "sessions" => $sessions
        // ]);
    }
    
}
