<?php

namespace App\Form;

use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filtre', ChoiceType::class,[
                'choices' => [
                    'Nom' => 'nom',
                    'Batiment' => 'batiment',
                    'Equipement'=> 'equipement',
                    'Capacite' => 'capacite',
                ]
            ])
            ->add('ordre', ButtonType::class,[
                'attr' => ['On/Off' => 'change'],])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}