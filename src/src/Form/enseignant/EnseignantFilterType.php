<?php

namespace App\Form\enseignant;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnseignantFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email_asc_or_desc',ChoiceType::class, [
                'choices'  => [
                    '' => "null",
                    'Croissant' => "asc",
                    'Décroissant' => "desc",
                ],
            ])
            ->add('nom_asc_or_desc',ChoiceType::class, [
                'choices'  => [
                    '' => "null",
                    'Croissant' => "asc",
                    'Décroissant' => "desc",
                ],
            ])
            ->add('prenom_asc_or_desc',ChoiceType::class, [
                'choices'  => [
                    '' => "null",
                    'Croissant' => "asc",
                    'Décroissant' => "desc",
                ],
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
