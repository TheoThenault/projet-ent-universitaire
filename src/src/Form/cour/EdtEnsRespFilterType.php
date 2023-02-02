<?php

namespace App\Form\cour;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EdtEnsRespFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Ue', ChoiceType::class, [
                'label' => 'Ue: ',
                'choices' => $options['ue']
            ])
            ->add('Enseignant', ChoiceType::class, [
                'label' => 'Enseignant: ',
                'choices' => $options['enseignant']
            ])
            ->add('Semaine', DateType::class, [
                'label' => 'Semaine du: ',
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'ue' => [],
            'enseignant' => []
        ]);
    }
}
