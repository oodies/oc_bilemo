<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class BrandFixtures
 *
 * @package App\DataFixtures
 */
class BrandFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->brands() as $nameBrand) {
            $brand = new Brand();
            $brand->setName($nameBrand);

            $manager->persist($brand);

            $this->addReference($nameBrand, $brand);
        }
        $manager->flush();
    }

    /**
     * @return array
     */
    public function brands()
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
