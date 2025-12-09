<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        // ✅ On ajoute le champ uniquement si l'utilisateur a au moins une adresse
        if ($user && count($user->getAddresses()) > 0) {
            $builder->add('addresses', EntityType::class, [
                'label' => false,
                'required' => true,
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true,
                'choice_label' => function (Address $address) {
                            return $address->getFirstname().' '.$address->getLastname()."\n"
                                .$address->getPhone()."\n"
                                .$address->getAddress()."\n"
                                .$address->getPostal().' '.$address->getCity()."\n"
                                .$address->getCountry();
                                        },
                                        'choice_value' => 'id', // <-- transforme l'objet en son ID pour le formulaire
                                        'data' => count($user->getAddresses()) > 0 ? $user->getAddresses()[0] : null, // <-- adresse par défaut
            ]);
        }

        $builder->add('submit', SubmitType::class, [
            'label' => 'Valider ma commande',
            'attr' => [
                'class' => 'btn btn-success btn-block'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => array(),
            'csrf_protection' => true,  // <-- assure-toi que CSRF est activé
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'order_item', // n'importe quel identifiant unique
        ]);
    }
}
