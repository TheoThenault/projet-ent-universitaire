<?php

namespace App\Form\formation;

use App\Entity\Cursus;
use App\Entity\Formation;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom de la formation'])
            ->add('cursus', EntityType::class, [
                'by_reference' => false,
                'class' => Cursus::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC');
                },
                'multiple' => false,
                'expanded' => false,
                'choice_label' => function ($cursus) {
                    return $cursus->getCursusCompleteName();
                }
            ])
            ->add('annee', ChoiceType::class, [
                'choices'  => [
                    'Année 1' => 1,
                    'Année 2'  => 2,
                    'Année 3'  => 3
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
