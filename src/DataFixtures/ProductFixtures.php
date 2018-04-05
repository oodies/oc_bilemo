<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator as FakerGenerator;
use Faker\Provider\Barcode as FakerBarcode;


/**
 * Class ProductFixtures
 *
 * @package App\DataFixtures
 */
class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @inheritDoc
     */
    function getDependencies()
    {
        return [
            BrandFixtures::class
        ];
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = new FakerGenerator();
        $faker->addProvider(new FakerBarcode($faker));

        $brands = $this->brands();

        $dateTime = new \DateTime();

        for ($i = 1; $i <= 100; $i++) {
            $product = new Product();
            $product->setName('mobile_' . $i)
                    ->setDescription('mobile description_' . $i)
                    ->setCode($faker->ean13)
                    ->setCreateAt($dateTime)
                    ->setUpdateAt($dateTime);

            /** @var \App\Entity\Brand $brand */
            $brand = $this->getReference($brands[array_rand($brands, 1)]);
            $product->setBrand($brand);

            $manager->persist($product);
        }
        $manager->flush();
    }

    /**
     * return array
     */
    protected function Products()
    {

        return [

        ];
    }


    /**
     * @return array
     */
    protected function brands()
    {
        return [
            'Apple',
            'Asus',
            'Motorola',
            'Nokia',
            'Htc',
            'Huawei',
            'Samsung',
            'Sony',
            'Wiko',
        ];
    }
}
