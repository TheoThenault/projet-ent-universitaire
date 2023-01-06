<?php

namespace App\Form\cursus;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CursusFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Niveau', ChoiceType::class, [
                'label' => 'Niveau d\'études: ',
                'choices' => $options['niveaux']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'niveaux' => []
        ]);

        //$resolver->setAllowedTypes('niveaux', 'array');
    }
}
