<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Controller\Admin\PostCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractDashboardController {

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(PostCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard {
        return Dashboard::new()
                        ->setTitle('Bobyblog');
    }

    public function configureMenuItems(): iterable {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Blog');
        yield MenuItem::linkToCrud('Posts', 'fas fa-list', Post::class);
    }

}
