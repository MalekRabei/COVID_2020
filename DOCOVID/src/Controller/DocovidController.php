<?php

namespace App\Controller;



use App\Entity\Demande;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use FOS\RestBundle\Controller\AbstractFOSRestController;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DocovidController extends AbstractFOSRestController
{
    public function __construct( DemandeRepository $dr){

        $this->dr = $dr;
    }


   
    public function index()
    {
        return $this->render('docovid/index.html.twig', [
        ]);
    }

    /**
     * @Route("/apropos", name="apropos")
     */
    public function apropos()
    {
        return $this->render('docovid/apropos.html.twig', [
            'demandes' => $this->dr->findAll()
        ]);
    }
    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
    {
        return $this->render('docovid/faq.html.twig', [
            'demandes' => $this->dr->findAll()
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('docovid/contact.html.twig', [
            'demandes' => $this->dr->findAll()
        ]);
    }


    /**********************REST APIS */

    /*****create new demande */
     /**
     * @Route("/newApi", name="new_demande", methods={"GET"})
     */
    public function newDemande (Request $request){

        
        //inialize livraison
        $entityManager = $this->getDoctrine()->getManager();
        $livraison = new Livraison();
        $entityManager->persist($livraison);
        $entityManager->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formattedLivraison = $serializer->normalize($livraison);
       

        //create demande
        $demande = new Demande();
        $demande->setLivraison($livraison);
        $demande->setMateriel($request->get('materiel'));
        $demande->setQuantite($request->get('quantite'));
        $demande->setDateDemande($request->get('date_demande'));
        $demande->setTempsDemande($request->get('temps_demande'));
        
        $entityManager->persist($demande);
        $entityManager->flush();

        
        $formatted = $serializer->normalize($demande);
        var_dump($formatted);
        return new JsonResponse($formattedLivraison, $formatted);


    }
   
    /**********list demande */
     /**
     * @Route("/listApi", name="demande_index", methods={"GET"})
     */
    public function listDemande(DemandeRepository $demandeRepository): Response
    {
        $demandes =$demandeRepository->findAllDemandes() ;

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($demandes);
        var_dump($formatted);
        return new JsonResponse($formatted);
    }
    /***********create new livraison */
    /**
     * @Route("/{id}/livraisonApi", name="new_livraison", methods={"GET","POST"}  , requirements={"id":"\d+"})
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

        $ivraison->setNomReceveur($livraison);
        $ivraison->setPrenomReceveur($request->get('nom'));
        $ivraison->setTelephone($request->get('prenom'));
        $ivraison->setAdresse($request->get('adresse'));
        $ivraison->setVille($request->get('ville'));
        $ivraison->setCite($request->get('cite'));
        $ivraison->setCodePostal($request->get('codePostal'));

        $entityManager->persist($livraison);
        $entityManager->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($ivraison);
        var_dump($formatted);
        return new JsonResponse($formatted);
        
    }
    /*************get confirmation mail */

    
}
