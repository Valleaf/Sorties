<?php

namespace App\Controller;

use App\Data\CancelData;
use App\Data\SearchData;
use App\Entity\Meeting;
use App\Entity\State;
use App\Form\CancelForm;
use App\Form\MeetingType;
use App\Form\SearchForm;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MeetingController extends AbstractController
{

    #[Route('/meeting', name: 'meeting_index',)]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $meetingRepo = $this->getDoctrine()->getRepository(Meeting::class);
        $meetings = $meetingRepo->findActive($data, $user);

        return $this->render('meeting/index.html.twig', [
            'meetings' => $meetings,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/meeting/{id}", name="meeting_add_or_remove",requirements={"id"="\d+"})
     */
    public function addOrRemoveParticipant(EntityManagerInterface $em, $id, Request $request)
    {

        $user = $this->getUser();
        $meetingRepo = $this->getDoctrine()->getRepository(Meeting::class);
        $meeting = $meetingRepo->find($id);
        //la sortie existe ??
        //verifier le max de participants et le status
        if ($meeting->getParticipants()->contains($user)) {
            if ($meeting->getStatus()->getLabel() == 'Ouverte') {
                $meeting->removeParticipant($user);
                $em->persist($meeting);
                $em->flush();
                $this->addFlash('success', 'You have successfully been removed from this meeting!');
                return $this->redirectToRoute('meeting_index');
            } else {
                $this->addFlash('warning', 'You cannot remove yourself from this meeting at this time!');
                return $this->redirectToRoute('meeting_index');
            }
        } else {
            $meeting->addParticipant($user);
            $em->persist($meeting);
            $em->flush();
            $this->addFlash('success', 'You have successfully been added to this meeting!');
            return $this->redirectToRoute('meeting_index');
        }
    }


    #[Route('/meeting/add', name: 'meeting_add')]
    public function add(MailerInterface $mailer, EntityManagerInterface $em, Request $request): Response
    {

        $user = $this->getUser();
        $statusRepo = $em->getRepository(State::class);
        $status = $statusRepo->findOneBy(['label'=>'Créée']);
        $campus = $user->getCampus();
        $meeting = new Meeting();
        $meetingForm = $this->createForm(MeetingType::class, $meeting);
        $meetingForm->handleRequest($request);


        if ($meetingForm->isSubmitted() && $meetingForm->isValid()) {
            $meeting->setOrganisedBy($user);
            $meeting->setCampus($user->getCampus());
            $status->addMeeting($meeting);
            $campus->addMeeting($meeting);
            $em->persist($meeting);
            $em->flush();
            $this->addFlash('success', 'The meeting was sucessfully created !');
            $user->sendEmail(
                $mailer,
                'Confirmation : La sortie '.$meeting->getName() .'a été créée',
                'La sortie '.$meeting->getName(). ' est valide et a bien étée créée');
            return $this->redirectToRoute('meeting_index', []);
        }

        return $this->render('meeting/add.html.twig', [
            'meetingForm' => $meetingForm->createView(),
        ]);
    }

    /**
     * @Route ("/meeting/cancel/{id}", name="meeting_cancel",requirements={"id"="\d+"})
     */
    public function cancelMeeting(MailerInterface $mailer ,EntityManagerInterface $em, $id, Request $request)
    {

        $user = $this->getUser();
        $data = new CancelData();
        $cancelform = $this->createForm(CancelForm::class, $data);
        $cancelform->handleRequest($request);
        if (  $cancelform->isSubmitted() && $cancelform->isValid()) {

            $statusRepo = $this->getDoctrine()->getRepository(State::class);
            $cancelledStatus = $statusRepo->findOneBy(['label' => 'Annulee']);
            $meetingRepo = $this->getDoctrine()->getRepository(Meeting::class);
            $cancelledMeeting = $meetingRepo->find($id);
            $message = $data->explanation;

            if ($cancelledMeeting->getOrganisedBy()->getUsername()== $user->getUsername()) {
                $cancelledMeeting->setStatus($cancelledStatus);
                $cancelledMeeting->setInformation($message);
                $em->persist($cancelledMeeting);
                $em->flush();
                $this->addFlash('success', 'You have successfully cancelled this meeting!');
                foreach ($cancelledMeeting->getParticipants() as $participant){
                    $participant->sendEmail(
                        $mailer,
                        'Annulation : La sortie '.$cancelledMeeting->getName() .'a été annulée',
                        'La sortie '.$cancelledMeeting->getName(). ' a été annulée');
                    return $this->redirectToRoute('meeting_index', []);
                }
                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('warning', 'The cancellation failed!');
                return $this->render('default/index.html.twig');
            }
        }
        return $this->render('meeting/deletepage.html.twig',[
            'form'=>$cancelform->createView()
        ]);

    }


}
