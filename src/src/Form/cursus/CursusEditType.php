<?php

namespace App\Form\cursus;

use App\Entity\Cursus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CursusEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'help' => 'Droit, Informatique, Médecine ...',
            ])
            ->add('niveau', ChoiceType::class, [
                'choices'  => [
                    'Licence' => 'Licence',
                    'Master' => 'Master'
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cursus::class,
        ]);
    }
}
