<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UEFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Specialite', ChoiceType::class, [
                'label' => 'Spécialité: ',
                'choices' => $options['specialite']
            ])
            ->add('Cursus', ChoiceType::class, [
                'label' => 'Cursus: ',
                'choices' => $options['cursus']
            ])
            ->add('Formation', ChoiceType::class, [
                'label' => 'Formation: ',
                'choices' => $options['formation']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'specialite' => [],
            'cursus' => [],
            'formation' => []
        ]);
    }
}
