<?php

namespace App\Form\enseignant;

use App\Entity\StatutEnseignant;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnseignantFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sort_asc_or_desc', ChoiceType::class,[
                'choices' => [
                    'email' => 'email',
                    'email Décroissant' => 'email_desc',
                    'Nom' => 'nom',
                    'Nom Décroissant' => 'nom_desc',
                    'Prénom' => 'prenom',
                    'Prénom Décroissant' => 'prenom_desc',
                ],
                'label' => 'Trier par:',

            ])
//            ->add('email_asc_or_desc',ChoiceType::class, [
//                'choices'  => [
//                    '' => "null",
//                    'Croissant' => "asc",
//                    'Décroissant' => "desc",
//                ],
//            ])
//            ->add('nom_asc_or_desc',ChoiceType::class, [
//                'choices'  => [
//                    '' => "null",
//                    'Croissant' => "asc",
//                    'Décroissant' => "desc",
//                ],
//            ])
//            ->add('prenom_asc_or_desc',ChoiceType::class, [
//                'choices'  => [
//                    '' => "null",
//                    'Croissant' => "asc",
//                    'Décroissant' => "desc",
//                ],
//            ])



//            ->add('statut_enseignant',EntityType::class, [
//                'class' => StatutEnseignant::class,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('statut')
//                        ->orderBy('statut.nom', 'ASC');
//                },
//                'multiple' => false,
//                'expanded' => false,
//                'choice_label' => 'nom'
//            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
