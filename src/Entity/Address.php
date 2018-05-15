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
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 */
class Address
{
    /**
     * Contains the ID of the address
     *
     * @var int|null
     *
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     length=11,
     *     nullable=false,
     *     unique=true,
     *     options={"unsigned"=true,
     *              "comment"="Contains the ID of the address"
     *     }
     * )
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"Default"})
     */
    protected $id;

    /**
     * Contains the full street address without city and postal code
     *
     * @var string|null
     *
     * @ORM\Column(
     *      name="street_address",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *      options={"comment"="Contains the full street address without city and postal code"}
     * )
     *
     * @Assert\Length(
     *          max="255",
     *          maxMessage="address.street_address.max_length"
     * )
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"Default"})
     */
    protected $streetAddress;

    /**
     * Contains the city of the address
     *
     * @var string|null
     *
     * @ORM\Column(
     *      name="city",
     *      type="string",
     *      length=70,
     *      nullable=true,
     *      options={"comment"="Contains the city of the address"}
     * )
     *
     * @Assert\Length(
     *          max="70",
     *          maxMessage="address.city.max_length"
     * )
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"Default"})
     */
    protected $city;

    /**
     * Contains the postcode of the address
     *
     * @var string|null
     *
     * @ORM\Column(
     *      name="postcode",
     *      type="string",
     *      length=10,
     *      nullable=true,
     *      options={"comment"="Contains the postcode of the address"}
     * )
     *
     * @Assert\Length(
     *          max="10",
     *          maxMessage="address.postcode.max_length"
     * )
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"Default"})
     */
    protected $postcode;

    /** *******************************
     * GETTER / SETTER
     */

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    /**
     * @param null|string $name
     *
     * @return Address
     */
    public function setStreetAddress(?string $name): Address
    {
        $this->streetAddress = $name;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param null|string $city
     *
     * @return Address
     */
    public function setCity(?string $city): Address
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    /**
     * @param null|string $postcode
     *
     * @return Address
     */
    public function setPostcode(?string $postcode): Address
    {
        $this->postcode = $postcode;
        return $this;
    }
}
