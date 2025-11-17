<?php

namespace App\Controller\Admin;

use App\Entity\Eleve;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, TextField, AssociationField};
use EasyCorp\Bundle\EasyAdminBundle\Config\{Action, Actions, Filters, Crud};
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;


class EleveCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Eleve::class;
    }

    private PersistenceManagerRegistry $doctrine;
    private AdminUrlGenerator $adminUrlGenerator;
    private EntityManagerInterface $entityManager;
    private RequestStack $requestStack;
    private Security $security;
    private KernelInterface $kernel;

    public function __construct(
        PersistenceManagerRegistry $doctrine,
        AdminUrlGenerator $adminUrlGenerator,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack,
        Security $security,
        KernelInterface $kernel
    ) {
        $this->doctrine = $doctrine;
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->security = $security;
        $this->kernel = $kernel;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom'),
            AssociationField::new('anneeScolaire')->autocomplete(),
            AssociationField::new('classe')->autocomplete(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $clone = Action::new('clone', 'Cloner')
            ->linkToRoute('eleve_clone', function (Eleve $eleve): array {
                return ['eleve' => $eleve->getId()];
            })
            ->setCssClass('btn btn-info')
            ->setIcon('fa fa-copy');

        // Ajouter DETAIL sur INDEX si non présent
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);

        // Mise en forme des boutons sur INDEX
        $actions->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
            return $action->setIcon('fa fa-eye')
                          ->setLabel('')
                          ->addCssClass('btn btn-default');
        });

        $actions->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            return $action->setIcon('fa fa-pencil')
                          ->setLabel('')
                          ->addCssClass('btn btn-default');
        });
        $actions->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
            return $action->setIcon('fa fa-trash')
                          ->setLabel('')
                          ->addCssClass('btn btn-warning');
        });

        // Ajouter CLONE sur INDEX, DETAIL et EDIT
        $actions->add(Crud::PAGE_INDEX, $clone);
        $actions->add(Crud::PAGE_DETAIL, $clone);
        $actions->add(Crud::PAGE_EDIT, $clone);

        // Supprimer DELETE sur INDEX
        // $actions->remove(Crud::PAGE_INDEX, Action::DELETE);

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('nom')
            ->add('prenom')
            ->add('classe')
            ->add('anneeScolaire');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Eleve')
            ->setEntityLabelInPlural('Eleves')
            ->setDefaultSort(['nom' => 'DESC'])
            ->showEntityActionsInlined()
            ->setTimezone('Europe/Paris');
    }
}
