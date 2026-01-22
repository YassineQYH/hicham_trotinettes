<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class AccountProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['data'];

        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 30,
                    ]),
                ],
            ])

            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 30,
                    ]),
                ],
            ])

            // ✅ EMAIL VISIBLE MAIS NON MODIFIABLE
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'mapped' => false,
                'disabled' => true,
                'data' => $user?->getEmail(),
                'attr' => [
                    'class' => 'form-control-plaintext',
                ],
            ])

            ->add('tel', TelType::class, [
                'label' => 'N° de téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le numéro de téléphone est obligatoire.',
                    ]),
                    new Regex([
                        'pattern' => '/^(?:\+33[67]\d{8}|\+32[4-7]\d{8})$/',
                        'message' => 'Le numéro doit commencer par +33 (France) ou +32 (Belgique) et être correct.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => '+33612345678',
                    'pattern' => '^(\\+33[67]\\d{8}|\\+32[4-7]\\d{8})$',
                    'title' => 'France : +33 6 ou 7, Belgique : +32 4 à 7',
                    'required' => true,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
