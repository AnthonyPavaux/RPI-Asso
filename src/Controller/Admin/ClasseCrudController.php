<?php

namespace App\Controller\Admin;

use App\Entity\Classe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\{FormField, TextEditorField, TextField, IdField, DateTimeField, ChoiceField, AssociationField, DateField, CollectionField, Field, TextareaField, ArrayField, ColorField, BooleanField};

class ClasseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Classe::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            AssociationField::new('eleves')->autocomplete(),
            AssociationField::new('etablisement')->autocomplete(),

        ];
    }
    
}
