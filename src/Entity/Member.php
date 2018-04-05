<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity Member extends Entity Person
 *
 * @ORM\Table(name="member")
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 *
 */
class Member extends Person
{
    /**
     * @var customer
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Customer"
     * )
     * @ORM\JoinColumn(
     *      name="customer_id",
     *      referencedColumnName="id_person",
     *      nullable=false
     * )
     *
     * @Assert\Valid()
     */
    protected $customer;

    /** *******************************
     *      GETTER / SETTER
     */

    /**
     * @return customer
     */
    public function getCustomer(): customer
    {
        return $this->customer;
    }

    /**
     * @param customer $customer
     *
     * @return Member
     */
    public function setCustomer(customer $customer): Member
    {
        $this->customer = $customer;
        return $this;
    }
}
