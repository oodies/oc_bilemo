<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator as FakerGenerator;
use Faker\Provider\fr_FR\Person as FakerPerson;
use Faker\Provider\fr_FR\Address as FakerAddress;
use Faker\Provider\fr_FR\PhoneNumber as FakerPhoneNumber;
use Faker\Provider\fr_FR\Company as FakerCompany;

/**
 * Class CustomerFixtures
 *
 * @package App\DataFixtures
 */
class CustomerFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [
            AddressFixtures::class
        ];
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = new FakerGenerator();
        $faker->addProvider(new FakerPerson($faker));
        $faker->addProvider(new FakerAddress($faker));
        $faker->addProvider(new FakerPhoneNumber($faker));
        $faker->addProvider(new FakerCompany($faker));

        for ($i = 1; $i <= 5; $i++) {
            $customer = new Customer();
            $customer->setCellPhone($faker->phoneNumber)
                     ->setFirstname($faker->firstName)
                     ->setLastname($faker->lastName)
                     ->setSiret((int)str_replace(' ','',$faker->siret))
                     ;
            /** @var \App\Entity\Address $address */
            $address = $this->getReference('address_' . (string)$i);
            $customer->setAddress($address);

            $manager->persist($customer);
            $this->addReference('customer_'.(string)$i, $customer);
        }
        $manager->flush();
    }
}
