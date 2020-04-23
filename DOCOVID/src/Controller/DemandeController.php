<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;


use FOS\RestBundle\Controller\AbstractFOSRestController;

/**
 * @Route("/demande")
 */
class DemandeController extends AbstractFOSRestController
{
    /**
     * @Route("/list", name="demande_index", methods={"GET"})
     */
    public function index(DemandeRepository $demandeRepository): Response
    {
        return $this->render('demande/index.html.twig', [
            'demandes' => $demandeRepository->findAllDemandes(),
        ]);
    }

    /**
     * @Route("/userList", name="demande_user", methods={"GET"})
     */
    public function userList(DemandeRepository $demandeRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
        //var_dump($usr);
        return $this->render('demande/user.html.twig', [
            'demandes' => $demandeRepository->findDemandeByUser($usr),
        ]);
    }


    /**
     * @Route("/new", name="demande_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
   

        //inialize livraison
        $entityManager = $this->getDoctrine()->getManager();
        $livraison = new Livraison();
        $entityManager->persist($livraison);
        $entityManager->flush();
        //get current user
        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
       // var_dump($usr);
        $user=$entityManager->getRepository(User::class)->find($usr);
        //create demande
        $demande = new Demande();
        $demande->setLivraison($livraison);
        $currentdate= new \DateTime('now');
        $demande->setDateDemande($currentdate);
        $demande->setTempsDemande($currentdate);
        $demande->setIdUser($user);
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($demande);
            $entityManager->flush();

            return $this->redirectToRoute('demande_user');
        }

        return $this->render('demande/new.html.twig', [
            'demande' => $demande,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/livraison", name="livraison", methods={"GET","POST"})
     */
    public function livraison(Request $request, $id) : Response
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        //get livraison
        $livraisonArray = $entityManager->getRepository(Livraison::class)->findLivraison($id);
        $idLivraison = $livraisonArray[0]["idLivraison"];
        $livraison = $entityManager->getRepository(Livraison::class)->find($idLivraison);
        //get demande
        $demandeArray = $entityManager->getRepository(Demande::class)->findDemande($id);
        $idDemande = $demandeArray[0]["idDemande"];
        $demande = $entityManager->getRepository(Demande::class)->find($idDemande);
       // var_dump($demandeArray);
       // var_dump($idDemande);
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($livraison);
            $entityManager->flush();

            return $this->redirectToRoute('confirmation');
        }

        return $this->render('livraison/new.html.twig', [
            'demande' => $demande,
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }
    
     /**
     * @Route("/confirmation", name="confirmation")
     */
    public function contact()
    {
        return $this->render('demande/confirmation.html.twig');
    }

    /**
     * @Route("/livraisons", name="livraisons", methods={"GET"})
     */
    public function listLivraison(LivraisonRepository $livraisonRepository): Response
    {
        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="demande_show", methods={"GET"})
     */
    public function show(Demande $demande): Response
    {
        return $this->render('demande/show.html.twig', [
            'demande' => $demande,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="demande_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Demande $demande): Response
    {
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('demande_index');
        }

        return $this->render('demande/edit.html.twig', [
            'demande' => $demande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="demande_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Demande $demande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($demande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('demande_index');
    }
}
