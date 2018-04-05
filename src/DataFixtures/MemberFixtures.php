<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\DataFixtures;

use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator as FakerGenerator;
use Faker\Provider\fr_FR\Person as FakerPerson;
use Faker\Provider\fr_FR\Address as FakerAddress;
use Faker\Provider\fr_FR\PhoneNumber as FakerPhoneNumber;

/**
 * Class MemberFixtures
 *
 * @package App\DataFixtures
 */
class MemberFixtures extends Fixture implements DependentFixtureInterface
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
            AddressFixtures::class,
            CustomerFixtures::class
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

        for ($i = 6; $i <= 50; $i++) {
            $member = new Member();
            $member->setCellPhone($faker->phoneNumber)
                     ->setFirstname($faker->firstName)
                     ->setLastname($faker->lastName);
            /** @var \App\Entity\Address $address */
            $address = $this->getReference('address_' . (string)$i);
            $member->setAddress($address);

            /** @var \App\Entity\Customer $customer */
            $customer = $this->getReference('customer_'. (string)(rand(1,5)));
            $member->setCustomer($customer);

            $manager->persist($member);
        }
        $manager->flush();
    }
}
