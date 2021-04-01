<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Meeting;
use App\Entity\State;
use App\Form\MeetingType;
use App\Form\SearchForm;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeetingController extends AbstractController
{

    #[Route('/meeting', name: 'meeting_index',)]
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
    #[Route('/meeting/add',name: 'meeting_add')]
    public function add(EntityManagerInterface $em, Request $request): Response {

        $user=$this->getUser();
        $status = new State();
        $status->setLabel('Créée');
        $campus = $user->getCampus();
        $meeting = new Meeting();
        $meetingForm = $this->createForm(MeetingType::class,$meeting);
        $meetingForm->handleRequest($request);

        $currentMeeting = new Meeting();
        $userForm = $this->createForm(MeetingType::class,$currentMeeting);
        $userForm->handleRequest($request);

        if($meetingForm->isSubmitted() && $meetingForm->isValid()) {
            $meeting->setOrganisedBy($user);
            $status->addMeeting($meeting);
            $campus->addMeeting($meeting);
            $em->persist($meeting);
            $em->flush();
            $this->addFlash('success', 'The meeting was sucessfully created !');
            return $this->redirectToRoute('meeting_index',[]);

        }
        dump($meeting);
        $request->get('meeting');
        return $this->render('meeting/add.html.twig',[
            'meetingForm'=>$meetingForm->createView(),
            'userForm'=>$userForm->createView()
        ]);
    }
}
