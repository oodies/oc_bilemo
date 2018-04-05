<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "customer"="App\Entity\Customer",
 *     "member"="App\Entity\Member"
 * })
 */
abstract class Person
{
    /**
     * Contains the ID of the person
     *
     * @var int
     *
     * @ORM\Column(
     *     name="id_person",
     *     type="integer",
     *     unique=true,
     *     length=11,
     *     options={"unsigned"=true,
     *              "comment"="Contains the ID of the person"
     *     }
     * )
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"Default"})
     */
    protected $idPerson;


    /**
     * Contains the firstname of the person
     *
     * @var null|string $firstname
     *
     * @ORM\Column(
     *     name="firstname",
     *     type="string",
     *     length=80,
     *     nullable=true,
     *     options={"comment"="Contains the firstname of the person"}
     * )
     *
     * @Assert\Length(
     *     max=80,
     *     maxMessage="user.firstname.max_length"
     * )
     *
     * @Serializer\Groups({"Default"})
     */
    protected $firstname;

    /**
     * Contains the lastname of the person
     *
     * @var null|string $lastname
     *
     * @ORM\Column(
     *     name="lastname",
     *     type="string",
     *     length=80,
     *     nullable=true,
     *     options={"comment"="Contains the lastname of the person"}
     * )
     *
     * @Assert\Length(
     *     max=80,
     *     maxMessage="user.lastname.max_length"
     * )
     *
     * @Serializer\Groups({"Default"})
     */
    protected $lastname;

    /**
     * Contains the cell phone of the person
     *
     * @var null|string
     *
     * @ORM\Column(
     *     name="cell_phone",
     *     type="string",
     *     length=30,
     *     nullable=true,
     *     options={"comment":"Contains the cell phone of the person"}
     * )
     *
     * @Assert\Length(
     *     max="30",
     *     maxMessage="person.cell_phone.max_length"
     * )
     *
     * @Serializer\Groups({"Default"})
     */
    protected $cellPhone;


    /** *******************************
     *  ASSOCIATION MAPPING
     */

    /**
     * One person should have a address
     *
     * @var Address|null
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Address",
     *     cascade={"persist", "remove"}
     * )
     * @ORM\JoinColumn(
     *      name="address_id",
     *      referencedColumnName="id_address",
     *     nullable=true
     * )
     *
     * @Assert\Valid()
     *
     * @Serializer\Groups({"Details"})
     */
    protected $address;

    /** *******************************
     *   GETTER / SETTER
     */

    /**
     * @return int
     */
    public function getIdPerson(): int
    {
        return $this->idPerson;
    }

    /**
     * @return null|string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param null|string $firstname
     *
     * @return Person
     */
    public function setFirstname(?string $firstname): Person
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param null|string $lastname
     *
     * @return Person
     */
    public function setLastname(?string $lastname): Person
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return Address|null
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param Address|null $address
     *
     * @return Person
     */
    public function setAddress(?Address $address): Person
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCellPhone(): ?string
    {
        return $this->cellPhone;
    }

    /**
     * @param null|string $cellPhone
     *
     * @return Person
     */
    public function setCellPhone(?string $cellPhone): Person
    {
        $this->cellPhone = $cellPhone;
        return $this;
    }
}
