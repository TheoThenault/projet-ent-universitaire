<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('CursusNiveau', ChoiceType::class, [
                'label' => 'Niveaux: ',
                'choices' => $options['cursus_niveau']
            ])
            ->add('CursusName', ChoiceType::class, [
                'label' => 'Nom: ',
                'choices' => $options['cursus_name']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'cursus_name' => [],
            'cursus_niveau' => []
        ]);
    }
}
