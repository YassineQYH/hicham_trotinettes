<?php

namespace App\Form;

use App\Entity\Trottinette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\TrottinetteCaracteristiqueType;
use App\Form\TrottinetteDescriptionSectionType;
use App\Entity\Accessory;

class TrottinetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('nameShort', TextType::class)
            ->add('slug', TextType::class)
            ->add('description', TextareaType::class)
            ->add('descriptionShort', TextareaType::class)
            ->add('image', FileType::class, ['required' => false, 'mapped' => false])
            ->add('isBest', CheckboxType::class, ['required' => false])
            ->add('isHeader', CheckboxType::class, ['required' => false])
            ->add('headerImage', FileType::class, ['required' => false, 'mapped' => false])
            ->add('headerBtnTitle', TextType::class)
            ->add('headerBtnUrl', TextType::class)
            ->add('accessories', EntityType::class, [
                'class' => Accessory::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('trottinetteCaracteristiques', CollectionType::class, [
                'entry_type' => TrottinetteCaracteristiqueType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('descriptionSections', CollectionType::class, [
                'entry_type' => TrottinetteDescriptionSectionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trottinette::class,
        ]);
    }
}
