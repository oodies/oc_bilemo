<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserFixtures
 *
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @inheritDoc
     */
    function getDependencies()
    {
        return [
            CustomerFixtures::class
        ];
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $dateTime = new \DateTime();


        $user = new User();
        $user->setRegisteredAt($dateTime)
             ->setUpdateAt($dateTime)
             ->setUsername('customer_1')
             ->setEmail('customer1@mail.com')
             ->setFirstname('firstname')
             ->setLastname('lastname')
        ;

        /** @var \App\Entity\Customer $customer */
        $customer = $this->getReference('customer_1');
        $user->setCustomer($customer);

        $manager->persist($user);
        $manager->flush();
    }
}
