<?php

namespace App\Form\ue;

use App\Entity\Formation;
use App\Entity\Specialite;
use App\Entity\UE;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UEEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom de l\'ue'])
            ->add('volumeHoraire', IntegerType::class)
            ->add('specialite', EntityType::class, [
                //pas de by ref dans une modification d'entity
                'class' => Specialite::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC');
                },
                'multiple' => false,
                'expanded' => false,
                'choice_label' => 'nom'
            ])
            ->add('formation',EntityType::class, [
                'class' => Formation::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->leftJoin('f.cursus', 'c')
                        ->addSelect('c')
                        ->orderBy('c.nom', 'ASC')
                        ->addOrderBy('f.nom', 'ASC');
                },
                'multiple' => false,
                'expanded' => false,
                'choice_label' => function ($formation) {
                    return $formation->getCursusAndFormationName();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UE::class,
        ]);
    }
}
