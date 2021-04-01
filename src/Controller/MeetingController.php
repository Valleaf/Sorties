<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Meeting;
use App\Form\SearchForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeetingController extends AbstractController
{
    #[Route('/meeting', name: 'meeting_index')]
    public function index(Request $request): Response
    {

        $user=$this->getUser();
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class,$data);
        $form->handleRequest($request);
        $meetingRepo = $this->getDoctrine()->getRepository(Meeting::class);
        $meetings = $meetingRepo->findActive($data, $user);
        return $this->render('meeting/index.html.twig', [
            'meetings' => $meetings,
            'form' => $form->createView(),
        ]);
    }
}
