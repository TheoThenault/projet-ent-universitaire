<?php

namespace App\Form\etudiant;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EtudiantFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Cursus', ChoiceType::class, [
                'label' => 'Cursus: ',
                'choices' => $options['cursus']
            ])
            ->add('Formation', ChoiceType::class, [
                'label' => 'Niveaux: ',
                'choices' => $options['formation']
            ])
            ->add('Entry', TextType::class, [
                'label' => 'Nom ou Prénom: ',
                'required' => false
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
