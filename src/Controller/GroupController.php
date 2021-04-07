<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    #[Route('/group', name: 'group_index')]
    public function index(EntityManagerInterface $em): Response
    {

        $user = $this->getUser();
        $groupsRepository = $em->getRepository(Group::class);
        $groups = $groupsRepository->findByUserId($user->getId());
        return $this->render('group/index.html.twig', [
            'groups'=>$groups
        ]);
    }
    #[Route('/group/add', name: 'group_add')]
    public function addGroup(EntityManagerInterface $em, Request $request){
        $user = $this->getUser();
        $group = new Group();
        $groupForm = $this->createForm(GroupType::class,$group);
        $groupForm->handleRequest($request);

        if($groupForm->isSubmitted() && $groupForm->isValid()) {
            $group->addMember($user);
            $em->persist($group);
            $em->flush();
            $this->addFlash('success','Le groupe a bien été crée');
            return $this->redirectToRoute('group_index');
        }
        return $this->render('group/add.html.twig',[
            'groupForm' => $groupForm->createView(),
        ]);
    }
    /**
     * @Route ("/group/{id}", name="group_add_members",requirements={"id"="\d+"})
     */
    public function addMember($id) {

        return $this->render('group/add_members.html.twig');

    }
}
