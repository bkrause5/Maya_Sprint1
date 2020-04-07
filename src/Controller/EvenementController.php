<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;


class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="evenement")
     */
    public function index()
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }

    /**
     * @Route("/evenement/creer", name="produit_creer")
     */
    public function creerEvenement(EntityManagerInterface $entityManager): Response
    {


        // créer l'objet
        $evenement = new Evenement();
        $evenement->setTitre('Summer day');
        $evenement->setDescription('Un êvenement musical durant la belle saison');
        $evenement->setDate(2018);
        $evenement->setHoraires(15);




        $entityManager->persist($evenement);

        // exécuter les requêtes (indiquées avec persist) ici il s'agit de l'ordre INSERT qui sera exécuté
        $entityManager->flush();

        return new Response('Nouvelle êvenement enregistré, son id est : '.$evenement->getId());
    }
    /**
     * @Route("/produitautomatique/{id}", name="produitautomatique_lire")
     */
    public function lireautomatique1(Evenement $evenement)
    {

        return new Response('Voici le libellé de levenement lu automatiquement : '
            .$evenement->getTitre().
                'avec comme description' .$evenement->getDescription().
            ' crée le '.$evenement->getDate()->format('Y')).
            'à cette heure' .$evenement->getHoraires()->format('h');

    }

    /**
     * @Route("/evenement/modifier/{id}", name="evenement_modifier")
     */
    public function modifier($id)
    {
        // 1  recherche de l'evenement
        $entityManager = $this->getDoctrine()->getManager();
        $evenement = $entityManager->getRepository(Evenement::class)->find($id);

        // en cas de d'evenement inexistant, affichage page 404
        if (!$evenement) {
            throw $this->createNotFoundException(
                'Aucun evenement avec l\'id '.$id
            );
        }

        // 2 modification des propriétés
        $evenement->setTitre('Summer day');
        // 3 exécution de l'update
        $entityManager->flush();

        // redirection vers l'affichage de l'evenement
        return $this->redirectToRoute('evenement_lire', [
            'id' => $evenement->getId()
        ]);
    }

    /**
     * @Route("/evenement/supprimer/{id}", name="evenement_supprimer")
     */
    public function supprimer($id)
    {
        // 1  recherche de l'evenement
        $entityManager = $this->getDoctrine()->getManager();
        $evenement = $entityManager->getRepository(Evenement::class)->find($id);

        // en cas d'evenement inexistant, affichage page 404
        if (!$evenement) {
            throw $this->createNotFoundException(
                'Aucun evenement avec l\'id '.$id
            );
        }

        // 2 suppression de l'evenement
        $entityManager->remove(($evenement));
        // 3 exécution du delete
        $entityManager->flush();

        // affichage réponse
        return new Response('Levenement a été supprimé, id : '.$id);
    }




}
