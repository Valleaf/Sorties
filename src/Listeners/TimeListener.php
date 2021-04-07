<?php


namespace App\Listeners;


use App\Entity\Meeting;
use App\Entity\State;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TimeListener
{

    public function postLoad(Meeting $meeting, LifecycleEventArgs $eventArgs)
    {

        $em = $eventArgs->getEntityManager();
       $statusRepo = $eventArgs->getEntityManager()->getRepository(State::class);
       $ouverte = $statusRepo->findOneBy(['label' => 'Ouverte']);
       $cloturee = $statusRepo->findOneBy(['label' => 'Cloturee']);
       $enCours = $statusRepo->findOneBy(['label' => ' Activite en cours ']);
       $Passee = $statusRepo->findOneBy(['label' => 'Passee']);
       $cancelled = $statusRepo->findOneBy(['label'=>'Annulee']);


        $meetingRepo = $eventArgs->getEntityManager()->getRepository(Meeting::class);
       $meetings = $meetingRepo->findAll();
       // $meetings = $meetingRepo->findAllAndStatuses();

        foreach ($meetings as $m) {
            $start = $m->getTimeStarting();
            $limit = $m->getRegisterUntil();
            $max = $m->getMaxParticipants();
            $nbParticipants = $m->getParticipants()->count();
            $duration = $m->getLength();
            $timeFinished = $start;
            $timeFinished = $timeFinished->modify('+' . $duration . ' minutes');
            $currentTime = new \DateTime();
            if($m->getStatus() != $cancelled){
                if ($limit >= $currentTime) {
                    $m->setStatus($ouverte);
                }
                if ($limit >= $currentTime && $max==$nbParticipants) {
                    $m->setStatus($cloturee);
                }

                if ($limit <= $currentTime) {
                    $m->setStatus($cloturee);
                }

                if ($start <= $currentTime) {
                    $m->setStatus($enCours);
                }

                if ($timeFinished <= $currentTime) {
                    $m->setStatus($Passee);
                }
            }



            $em->persist($m);
            $em->flush();
        }


    }

}