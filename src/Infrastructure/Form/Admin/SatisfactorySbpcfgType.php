<?php

namespace App\Infrastructure\Form\Admin;

use App\Domain\Entity\Site\SatisfactorySbpcfg;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SatisfactorySbpcfgType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sbpcfgFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SatisfactorySbpcfg::class,
        ]);
    }
}
