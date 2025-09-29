<?php

namespace App\Form;

use App\Entity\TrottinetteCaracteristique;
use App\Entity\Caracteristique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrottinetteCaracteristiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('caracteristique', EntityType::class, [
                'class' => Caracteristique::class,
                'choice_label' => 'name',
                'label' => 'Caractéristique',
            ])
            ->add('value', TextType::class, [
                'label' => 'Valeur',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrottinetteCaracteristique::class,
        ]);
    }
}
