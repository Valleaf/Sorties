<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    #[Route('/city', name: 'city')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $city = new City();
        $cityForm = $this->createForm(CityType::class,$city);
        $cityForm->handleRequest($request);

        if ($cityForm->isSubmitted() && $cityForm->isValid()) {
            $em->persist($city);
            $em->flush();
            $this->addFlash('success', 'La ville a bien été ajoutée');
            return $this->redirectToRoute('home');
        }

        return $this->render('city/index.html.twig', [
            'cityForm' => $cityForm->createView(),
        ]);
    }
}
