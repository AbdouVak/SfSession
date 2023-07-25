<?php

namespace App\Controller;

use App\Entity\Programme;
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

    #[Route('/session/removeSta/{stagiaire_id}/{session_id}', name: 'remove_stagiaire')]
    #[ParamConverter('stagiaire', options: ['id' => 'stagiaire_id'])]
    #[ParamConverter('session', options: ['id' => 'session_id'])]
    public function removeStagiaire(Session $session,Stagiaire $stagiaire,ManagerRegistry $doctrine): Response{
        $session->removeStagiaire($stagiaire);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($session);
        $entityManager->flush();
        return $this->redirectToRoute('show_session',['id'=>$session->getId()]);    
    }

    // route/fonction pour ajouter un stagiaire d'une session
    #[Route('/session/addSta/{stagiaire_id}/{session_id}', name: 'add_Stagiaire')]
    #[ParamConverter('stagiaire', options: ['id' => 'stagiaire_id'])]
    #[ParamConverter('session', options: ['id' => 'session_id'])]
    public function addStagiaire(Session $session,Stagiaire $stagiaire,ManagerRegistry $doctrine): Response
    {
        $session->addStagiaire($stagiaire);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($session);
        $entityManager->flush();
        return $this->redirectToRoute('show_session',['id'=>$session->getId()]);    
    }

    // route/fonction pour supprimer un programme d'une session
    #[Route('/session/removeProgramme/{programme_id}/{session_id}', name: 'remove_Programme')]
    #[ParamConverter('programme', options: ['id' => 'programme_id'])]
    #[ParamConverter('session', options: ['id' => 'session_id'])]
    public function removeProgramme(Session $session,Programme $programme,ManagerRegistry $doctrine): Response{
        
        $entityManager = $doctrine->getManager();
        // faire ca au lieux de $notreEntité->removeNotreEntitéEntitéASupprimé(EntitéASupprimé)
        $entityManager->remove($programme);
        $entityManager->persist($session);
        $entityManager->flush();
        return $this->redirectToRoute('show_session',['id'=>$session->getId()]);    
    }

    // route/fonction pour ajouter un programme dans une session
    #[Route('/session/addProgramme/{module_id}/{session_id}', name: 'add_Programme')]
    #[ParamConverter('module', options: ['id' => 'module_id'])]
    #[ParamConverter('session', options: ['id' => 'session_id'])]
    public function addProgramme(Session $session,Module $module,ManagerRegistry $doctrine): Response{
        //crée un programme
        $programme = new Programme();
        $programme->setSession($session);
        $programme->setModule($module);
        $programme->setJour($_POST['jour']);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($programme);
        $entityManager->flush();

        return $this->redirectToRoute('show_session',['id'=>$session->getId()]);    
    }

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session, SessionRepository $sessionRepository): Response{
        $stagiairesNI = $sessionRepository->findNonInscrits($session->getId());
        $moduleAjoutable = $sessionRepository->findModuleAjoutable($session->getId());

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'stagiairesNI' =>$stagiairesNI,
            "moduleAjoutable" => $moduleAjoutable
        ]);    
    }    

    #[Route('/session', name: 'app_session')]
    public function index(ManagerRegistry $doctrine): Response{
        $sessions = $doctrine->getRepository(Session::class)->findBy([],["dateDebut"=>"DESC"]);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }
}
