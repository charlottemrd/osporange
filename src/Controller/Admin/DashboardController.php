<?php

namespace App\Controller\Admin;

use App\Entity\Bilanmensuel;
use App\Entity\Commentaire;
use App\Entity\Cout;
use App\Entity\DataTrois;
use App\Entity\DateLone;
use App\Entity\DateOnePlus;
use App\Entity\DateTwo;
use App\Entity\DateZero;
use App\Entity\Fournisseur;
use App\Entity\Idmonthbm;
use App\Entity\Infobilan;
use App\Entity\Modalites;
use App\Entity\Paiement;
use App\Entity\Phase;
use App\Entity\Priorite;
use App\Entity\Profil;
use App\Entity\Projet;
use App\Entity\Risque;
use App\Entity\TypeBU;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
      //  return parent::index();
        $routeBuilder = $this->get(AdminUrlGenerator::class);
              $url = $routeBuilder->setController(CommentaireCrudController::class)->generateUrl();

                return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Osp Projet');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'projet_index');

        yield MenuItem::linkToCrud('Projet', 'fas fa-map-marker-alt', Projet::class);
        yield MenuItem::linkToCrud('Commentaire', 'fas fa-map-marker-alt', Commentaire::class);
        yield MenuItem::linkToCrud('Cout', 'fas fa-map-marker-alt', Cout::class);
        yield MenuItem::linkToCrud('Modalites', 'fas fa-comments', Modalites::class);

        yield MenuItem::linkToCrud('Bilan mensuel ', 'fas fa-comments', Idmonthbm::class);
        yield MenuItem::linkToCrud('Projets bm', 'fas fa-map-marker-alt', Bilanmensuel::class);
        yield MenuItem::linkToCrud('Informations bm', 'fas fa-map-marker-alt', Infobilan::class);


        yield MenuItem::linkToCrud('Fournisseur', 'fas fa-map-marker-alt', Fournisseur::class);
        yield MenuItem::linkToCrud('Profils', 'fas fa-comments', Profil::class);


        yield MenuItem::linkToCrud('User', 'fas fa-comments', User::class);
        yield MenuItem::linkToCrud('Risque', 'fas fa-comments', Risque::class);
        yield MenuItem::linkToCrud('Type de BU', 'fas fa-map-marker-alt', TypeBU::class);
        yield MenuItem::linkToCrud('Type de paiement', 'fas fa-map-marker-alt', Paiement::class);
        yield MenuItem::linkToCrud('Phase', 'fas fa-comments', Phase::class);
        yield MenuItem::linkToCrud('Priorite', 'fas fa-map-marker-alt', Priorite::class);



    }
}
