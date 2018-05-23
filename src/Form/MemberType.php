<?php

namespace App\Form;

use App\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MemberType
 *
 * @package App\Form
 */
class MemberType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstname', TextType::class, [
                               'documentation' => [
                                   'type'        => 'string',
                                   'description' => 'The firstname of the person'
                               ]
                           ]
            )
            ->add(
                'lastname', TextType::class, [
                              'documentation' => [
                                  'type'        => 'string',
                                  'description' => 'the lastname of the person'
                              ]
                          ]
            )
            ->add(
                'cell_phone', TextType::class, [
                                'documentation' => [
                                    'type'        => 'string',
                                    'description' => 'The cell phone of the person'
                                ]
                            ]
            )
            // https://swagger.io/docs/specification/data-models/data-types/
            ->add(
                'address', AddressType::class, [
                             'documentation' => [
                                 '$ref' => '#/components/schemas/Address'
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
                'data_class'      => Member::class,
                'csrf_protection' => false
            ]
        );
    }
}
