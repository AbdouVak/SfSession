<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Module;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
class SessionController extends AbstractController
{
    #[Route('/session/add', name: 'add_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function add(ManagerRegistry $doctrine, Session $session = null, Request $request): Response{   
        if(!$session){
            $session = new Session();
        }
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $session = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($session);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_session');
        }
    
        // vue pour afficher formulaire d'ajout
        return $this->render('session/add.html.twig', [
            'formAddsession' => $form->createView(),
            'edit'=> $session->getId()
        ]);
    }
    
    #[Route('/session/remove/{stagiaire_id}/{session_id}', name: 'remove_stagiaire')]
    #[ParamConverter('stagiaire', options: ['id' => 'stagiaire_id'])]
    #[ParamConverter('session', options: ['id' => 'session_id'])]
    public function removeStagiaire(Session $session,Stagiaire $stagiaire,ManagerRegistry $doctrine): Response
    {
        $session->removeStagiaire($stagiaire);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($session);
        $entityManager->flush();
        return $this->redirectToRoute('app_session');    
    }

    #[Route('/session/remove/{module_id}/{session_id}', name: 'remove_module')]
    #[ParamConverter('module', options: ['id' => 'module_id'])]
    #[ParamConverter('session', options: ['id' => 'session_id'])]
    public function removeModule(Session $session,ManagerRegistry $doctrine): Response
    {
        
        $entityManager = $doctrine->getManager();
        $entityManager->persist($session);
        $entityManager->flush();
        return $this->redirectToRoute('app_session');    
    }

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session, SessionRepository $sessionRepository): Response
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
    public function index(ManagerRegistry $doctrine): Response{
        $sessions = $doctrine->getRepository(Session::class)->findBy([],["nom"=>"DESC"]);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    

    
}
