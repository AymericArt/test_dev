<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
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

    #[Route('/personne', name: 'app_personne')]
    public function index(Request $request): Response
    {
        $personne = new Personne();
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        /**
         * Le contrôle de l'âge est dans l'entité afin de centraliser les contrôles.
         * Methode : loadValidatorMetadata()
         */
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($personne);
            $this->entityManager->flush($personne);
        }

        return $this->render('personne/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
