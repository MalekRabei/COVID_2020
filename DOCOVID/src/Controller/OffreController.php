<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;

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


// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/offre")
 */
class OffreController extends AbstractController
{
    /**
     * @Route("/", name="offre_index", methods={"GET"})
     */
    public function index(OffreRepository $offreRepository,PaginatorInterface $paginator, Request $request): Response
    {

        $alloffres = $offreRepository->findAll();
        $offres = $paginator->paginate(
            // Doctrine Query, not results
            $alloffres,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );
        $offres->setCustomParameters([
            'position' => 'centered',
            'size' => 'large',
            'rounded' => true,
        ]);
        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
        ]);
    }
    /**
     * @Route("/userOffers", name="offre_user", methods={"GET"})
     */
    public function userList(OffreRepository $offreRepository , PaginatorInterface $paginator, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $usr= $this->get('security.token_storage')->getToken()->getUser()->getId();
        $user=$entityManager->getRepository(User::class)->find($usr);

        $alloffres = $offreRepository->findOffreByUser($usr);
        // Paginate the results of the query
        $offres = $paginator->paginate(
            // Doctrine Query, not results
            $alloffres,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        //var_dump($usr);
        return $this->render('offre/user.html.twig', [
            'offres' => $offres,
            'user' =>  $user
        ]);
    }

    /**
     * @Route("/new", name="offre_new", methods={"GET","POST"})
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
        $user=$entityManager->getRepository(User::class)->find($usr);

        //create offre
        $offre = new Offre();
        $offre ->setLivraison($livraison);
        $currentdate= new \DateTime('now');
        $offre ->setDateOffre ($currentdate);
        $offre ->setTimeOffre($currentdate);
        $offre ->setIdUser($user);
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('offre_user');
        }

        return $this->render('offre/new.html.twig', [
            'offre' => $offre,
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
        //get Offre
        $OffreArray = $entityManager->getRepository(Offre::class)->findOffre($id);
        $idOffre = $OffreArray[0]["idOffre"];
        $offre = $entityManager->getRepository(Offre::class)->find($idOffre);
       // var_dump($OffreArray);
       // var_dump($idOffre);
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($livraison);
            $entityManager->flush();

            return $this->redirectToRoute('confirmation');
        }

        return $this->render('livraison/newOffre.html.twig', [
            'offre' => $offre,
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }
    

    /**
     * @Route("/{id}", name="offre_show", methods={"GET"})
     */
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="offre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Offre $offre): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offre_index');
        }

        return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Offre $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offre_index');
    }
}
