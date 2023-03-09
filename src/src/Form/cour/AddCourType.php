<?php

namespace App\Form\cour;

use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('UE', ChoiceType::class, [
                'label' => 'UE: ',
                'choices' => $options['ues']
            ])
            ->add('CRENEAU', DateType::class, [
                'label' => 'Date: ',
                'widget' => 'single_text'
            ])
            ->add('HEURE', ChoiceType::class, [
                'label' => 'Creneau: ',
                'choices' => [
                    '8h-10h' => 0, '10h-12h' => 1, '14h-16h' => 2, '16h-18h' => 3
                ]
            ])
            ->add('ENSEIGNANT', ChoiceType::class, [
                'label' => 'Professeur: ',
                'choices' => $options['profs']
            ])
            ->add('GROUPE', ChoiceType::class, [
                'label' => 'Groupe: ',
                'choices' => $options['grps']
            ])
            ->add('SALLE', ChoiceType::class, [
                'label' => 'Salle: ',
                'choices' => $options['salles']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'ues' => [],
            'profs' => [],
            'salles' => [],
            'grps' => []
        ]);
    }
}
