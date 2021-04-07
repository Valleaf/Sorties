<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    #[Route('/place', name: 'place')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $place = new Place();
        $placeForm = $this->createForm(PlaceType::class,$place);
        $placeForm->handleRequest($request);

        if($placeForm->isSubmitted() && $placeForm->isValid()){
            $em->persist($place);
            $em->flush();
            $this->addFlash('success', 'Le lieu a bien été ajouté');
            return $this->redirectToRoute('home');

        }
        return $this->render('place/index.html.twig', [
            'placeForm' => $placeForm->createView(),
        ]);
    }
}
