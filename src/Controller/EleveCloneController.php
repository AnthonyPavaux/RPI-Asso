<?php
namespace App\Controller;

use App\Entity\Eleve;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EleveCloneController extends AbstractController
{
    private EntityManagerInterface $em;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(EntityManagerInterface $em, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->em = $em;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/eleve/clone/{eleve}', name: 'eleve_clone')]
    public function clone(Eleve $eleve): RedirectResponse
    {
        $new = clone $eleve;
        $new->setNullId();
        //$new->setAnneeScolaire(null);

        $this->em->persist($new);
        $this->em->flush();

        $url = $this->adminUrlGenerator
            ->setController(\App\Controller\Admin\EleveCrudController::class)
            ->setAction('detail')
            ->setEntityId($new->getId())
            ->generateUrl();

        return new RedirectResponse($url);
    }
}
