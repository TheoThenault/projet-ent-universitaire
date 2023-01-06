<?php

namespace App\Form\salle;

use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalleAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom de la salle'])
            ->add('batiment', TextType::class)
            ->add('equipement', ChoiceType::class, [
                'choices'  => [
                    'aucun' => null,
                    'informatique' => 'informatique',
                    'physique' => 'physique',
                    'langue' => 'langue',
                    'chime' => 'chime'
                ],
            ])
            ->add('capacite', IntegerType::class, ['label' => 'Capacité d\'étudiant'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
