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
                    'Nom' => 'nom_asc',
                    'Nom Décroissant' => 'nom_desc',
                    'Batiment' => 'batiment_asc',
                    'Batiment Décroissant' => 'batiment_desc',
                    'Equipement'=> 'equipement_asc',
                    'Equipement Décroissant'=> 'equipement_desc',
                    'Capacite' => 'capacite_asc',
                    'Capacite Décroissant' => 'capacite_desc',
                ]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}