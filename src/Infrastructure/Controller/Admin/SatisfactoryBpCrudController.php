<?php

namespace App\Infrastructure\Controller\Admin;

use App\Domain\Entity\Site\SatisfactoryBp;
use App\Infrastructure\Form\Admin\SatisfactoryImageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
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
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextField::new('description'),
            TextField::new('author'),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
            TextField::new('downloadUrlSbp'),
            TextField::new('downloadUrlSbpcfg'),
            IntegerField::new('downladCount')->hideOnForm(),
            CollectionField::new('image')
                ->setEntryType(SatisfactoryImageType::class)
                ->setFormTypeOption('by_reference', false)
                ->allowAdd(true)
                ->allowDelete(true),
        ];
    }
}
