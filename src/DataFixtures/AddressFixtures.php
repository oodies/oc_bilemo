<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator as FakerGenerator;
use Faker\Provider\fr_FR\Person as FakerPerson;
use Faker\Provider\fr_FR\Address as FakerAddress;

/**
 *
 */
class AddressFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = new FakerGenerator();
        $faker->addProvider(new FakerPerson($faker));
        $faker->addProvider(new FakerAddress($faker));

        for ($i = 1; $i <= 50; $i++) {
            $address = new Address();
            $address->setStreetAddress($faker->streetName);
            $address->setPostcode($faker->postcode);
            $address->setCity($faker->city);

            $manager->persist($address);
            $this->addReference('address_' . (string)$i, $address);
        }
        $manager->flush();
    }
}
