<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonneController extends AbstractController
{

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/personne', name: 'add_personne')]
    public function index(Request $request): Response
    {
        $personne = new Personne();
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        /**
         * Le contrôle de l'âge est dans l'entité afin de centraliser les contrôles.
         * Methode : loadValidatorMetadata()
         *
         * Nous arions pu utiliser les groupes
         */
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($personne);
            $this->entityManager->flush($personne);
        }

        return $this->render('personne/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/liste-des-personnes', name: 'list_personne')]
    public function list(Request $request, PersonneRepository $personneRepository): Response
    {
        $listePersonne = $personneRepository->findBy([], ['nom' => "ASC"]);

        return $this->render('personne/list.html.twig', [
            "listePersonne" => $listePersonne
        ]);
    }
}
