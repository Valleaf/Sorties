<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    #[Route('/',name: 'home')]
    public function index(): Response {


        return $this->render('default/index.html.twig');
    }
    /**
     * @Route ("/home/theme/{id}", name="home_theme",requirements={"id"="\d+"})
     */
    public function selectTheme($id): Response
    {
        $expire = 6 * 30 * 24 * 3600;
        $cookie = Cookie::create('theme')
            ->withValue($id);
        $this->addFlash('success', 'Choix du theme actif!');
        $response = $this->redirectToRoute('home');
        $response->headers->setCookie($cookie);
        return $response;

    }
    /**
     * @Route ("/home/langue/{id}", name="home_langue",requirements={"id"="\d+"})
     */
    public function selectLangue($id): Response {
    $expire = 6*30*24*3600;
    $cookie = Cookie::create('langue')
        ->withValue($id)
        ->withExpires($expire)
        ->withDomain('.meetup.com')
        ->withSecure(true);
    $this->addFlash('success', 'Choix de langue actif!');

        return $this->render('default/index.html.twig');
    }



}
