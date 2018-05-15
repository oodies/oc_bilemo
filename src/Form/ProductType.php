<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProductType
 *
 * @package App\Form
 */
class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name', TextType::class,
                [
                    'documentation' => [
                        'type'        => 'string',
                        'description' => 'the name of the product'
                    ]
                ]
            )
            ->add(
                'code', TextType::class,
                [
                    'documentation' => [
                        'type'        => 'string',
                        'description' => 'the code of the product'
                    ]
                ]
            )
            ->add(
                'description', TextType::class,
                [
                    'required'      => false,
                    'documentation' => [
                        'type'        => 'string',
                        'description' => 'the description of the product'
                    ]
                ]
            )
            ->add(
                'Brand', EntityType::class,
                [
                    'class'         => Brand::class,
                    'choice_value'  => 'idBrand',
                    'choice_label'  => 'name',
                    'documentation' => [
                        'type'        => 'string',
                        'description' => 'The brand of the product'
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
                'data_class'      => Product::class,
                'csrf_protection' => false
            ]
        );
    }
}
