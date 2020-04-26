<?php

namespace App\Controller;



use App\Entity\Demande;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;

use App\Form\ContactType;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use FOS\RestBundle\Controller\AbstractFOSRestController;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DocovidController extends AbstractFOSRestController
{
    public function __construct( DemandeRepository $dr){

        $this->dr = $dr;
    }


   
    public function index()
    {
        $form = $this ->createFormBuilder(null)
        ->add('query', TextType::class)
        ->getForm();
       
        return $this->render('docovid/index.html.twig', [
            'form'=> $form->createView()
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
     * @Route("/cgu", name="cgu")
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
    public function contact(Request $request,\Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            // On crée le message
            $message = (new \Swift_Message('Nouveau contact'))
                // On attribue l'expéditeur
                ->setFrom($contact['email'])
                // On attribue le destinataire
                ->setTo('rabeimelek9@gmail.com')
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'docovid/index.html.twig', compact('contact')
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            $this->addFlash('message', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.'); // Permet un message flash de renvoi
        }
        return $this->render('docovid/contact.html.twig',['contactForm'=>$form->createView()]);
    
    }


      /**
     * @Route("/search", name="search", methods={"GET","POST"} )
     */
    public function searchBar(Request $request){
        
        $entityManager = $this->getDoctrine()->getManager();
        $query= $request->get('q');
        $demandes = $entityManager->getRepository(Demande::class)->findDemandeByQuery($query);

        if (!$demandes) {
            $result['demandes']['error'] = "Pas de demandes avec ce matériel pour le moment";
        } else {
            $result['demandes'] = $this->getRealEntities($demandes);
        }

            return new Response(json_encode($result));

    }

    public function getRealEntities($demandes){
        foreach ($demandes as $demandes){
            $livraison = $demandes->getLivraison();

            //var_dump();
            $realEntities[$demandes->getId()]= [

                $demandes->getMateriel(),
                $demandes->getQuantite(),
                $demandes->getDateDemande(),
                $demandes->getTempsDemande(),
                $demandes->getMessage(),
                $livraison->getNomReceveur(),
                $livraison->getPrenomReceveur(),
                $livraison->getTelephone(),
                $livraison->getAdresse(),
                $livraison->getVille(),
                $livraison->getCite(),
                $livraison->getCodePostal(),
                $demandes->getId(),

                


                
            ];

        }
        return $realEntities;
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
        // e livraison tebda deja creer ba3d ta3mel creation mta3 demande w taffectilfa el livraison 
        $demande->setLivraison($livraison);
        //edhouma asemi les champs mta3 l demande
        $demande->setMateriel($request->get('materiel'));
        $demande->setQuantite($request->get('quantite'));
        $demande->setDateDemande($request->get('date_demande'));
        $demande->setTempsDemande($request->get('temps_demande'));
        $demande->setMessage($request->get('message'));

        //ba3d ypersisti fil base 
        $entityManager->persist($demande);
        $entityManager->flush();

        // w yconverti l json
        $formatted = $serializer->normalize($demande);
        var_dump($formatted);
        return new JsonResponse($formattedLivraison, $formatted);


    }
   
    /**********list demande */
     /**
     * @Route("/listApi", name="demande_list", methods={"GET"})
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

        $ivraison->setNomReceveur($request->get('nom'));
        $ivraison->setPrenomReceveur($request->get('prenom'));
        $ivraison->setTelephone($request->get('telephone'));
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
