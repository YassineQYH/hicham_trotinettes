<?php

namespace App\Form;

use App\Entity\HomeVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class HomeVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la vidéo',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un titre',
                    ]),
                ],
            ])

            ->add('videoFile', FileType::class, [
                'label' => 'Vidéo (MP4 uniquement)',
                'mapped' => false, // ⛔ pas lié directement à l'entité
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '300M',
                        'mimeTypes' => [
                            'video/mp4',
                            'video/webm',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une vidéo valide (MP4 ou WebM)',
                    ]),
                ],
            ])

            ->add('isActive', CheckboxType::class, [
                'label' => 'Vidéo active sur la page d’accueil',
                'required' => false,
            ])

            ->add('position', IntegerType::class, [
                'label' => 'Position d’affichage',
                'required' => false,
                'attr' => [
                    'min' => 0,
                ],
                'help' => 'Plus le chiffre est petit, plus la vidéo sera affichée en priorité',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HomeVideo::class,
        ]);
    }
}
