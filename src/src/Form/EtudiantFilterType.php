<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Cursus', ChoiceType::class, [
                'label' => 'Nom: ',
                'choices' => $options['cursus']
            ])
            ->add('Formation', ChoiceType::class, [
                'label' => 'Niveaux: ',
                'choices' => $options['formation']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'cursus' => [],
            'formation' => []
        ]);
    }
}
