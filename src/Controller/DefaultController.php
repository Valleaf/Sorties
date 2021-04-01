<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    #[Route('/',name: 'home')]
    public function index(): Response {
        return $this->render('default/index.html.twig');
    }

    #[Route('/autre',name: 'default_autre')]
    public function autrePage(): Response{
        return $this->render('default/autre.html.twig',[]);
    }
}
