<?php

namespace App\Controller;

use App\Entity\Formation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController
{
    #[Route('/formation', name: 'app_formation')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $formations = $doctrine->getRepository(Formation::class)->findBy([],["nom"=>"DESC"]);
        return $this->render('formation/index.html.twig', [
            'formations' => $formations
        ]);
    }
}
