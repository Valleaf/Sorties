<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Meeting;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Meetup');
    }

    public function configureMenuItems(): iterable
    {
        return [MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('User','fa fa-users',User::class ),
            MenuItem::linkToCrud('Meeting','fa fa-handshake',Meeting::class) ,
            MenuItem::linkToCrud('Place','fa fa-map-marker-alt',Place::class ),
            MenuItem::linkToCrud('City','fa fa-city',City::class ),
            MenuItem::linkToCrud('State','fa fa-info-circle',State::class ),
            MenuItem::linkToCrud('Campus','fa fa-school',Campus::class ),
            ];

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
