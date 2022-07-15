<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Form\PizzaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pizzaRepo = $entityManager->getRepository(Pizza::class);
        $pizza = new Pizza();
        $form = $this->createForm(PizzaType::class, $pizza);

        $allPizza = $pizzaRepo->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pizza);
            $entityManager->flush($pizza);
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'allPizza' => $allPizza
        ]);
    }
}
