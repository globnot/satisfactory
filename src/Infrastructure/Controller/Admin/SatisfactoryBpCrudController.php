<?php

namespace App\Infrastructure\Controller\Admin;

use App\Domain\Entity\Site\SatisfactoryBp;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SatisfactoryBpCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SatisfactoryBp::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
}
