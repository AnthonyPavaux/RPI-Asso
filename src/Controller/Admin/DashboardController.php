<?php
namespace App\Controller\Admin;

use App\Entity\{User,Eleve, Classe, Etablisement, AnneeScolaire };
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public function index(): Response
    {
        // Redirige directement vers le CRUD User
        $url = $this->adminUrlGenerator
            ->setController(UserCrudController::class)
            ->generateUrl();

        return new RedirectResponse($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('RPI Asso');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Etablisement', 'fa fa-city', Etablisement::class);
        yield MenuItem::linkToCrud('Classe', 'fa fa-school', Classe::class);
        yield MenuItem::linkToCrud('Eleves', 'fa fa-user-graduate', Eleve::class);
        yield MenuItem::linkToCrud('Année Scolaire', 'fa fa-calendar', AnneeScolaire::class);
        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);
    }
}
