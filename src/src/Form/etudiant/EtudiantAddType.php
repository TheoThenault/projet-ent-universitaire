<?php

namespace App\Form\etudiant;

use App\Entity\Etudiant;
use App\Entity\Formation;
use App\Entity\Personne;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;


class EtudiantAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, ['mapped' => false])
            ->add('nom', TextType::class, ['mapped' => false])
            ->add('formation', EntityType::class, [
                'by_reference' => false,
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
            'data_class' => Etudiant::class,
        ]);
    }
}
