<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddressType
 *
 * @package App\Form
 */
class AddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'street_address', TextType::class,
                [
                    'documentation' => [
                        'type'        => 'string',
                        'description' => 'the full street address without city and postal code'
                    ]
                ]
            )
            ->add(
                'city', TextType::class,
                [
                    'documentation' => [
                        'type'        => 'string',
                        'description' => 'The city of the address'
                    ]
                ]
            )
            ->add(
                'postcode', TextType::class,
                [
                    'documentation' => [
                        'type'        => 'string',
                        'description' => 'The postcode of the address'
                    ]
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'      => Address::class,
                'csrf_protection' => false
            ]
        );
    }
}
