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
     *     name="id_address",
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
     */
    protected $idAddress;

    /**
     * Contains the full address without city and postal code
     *
     * @var string|null
     *
     * @ORM\Column(
     *      name="name",
     *      type="string",
     *      length=255,
     *      nullable=true,
     *      options={"comment"="Contains the full address without city and postal code"}
     * )
     *
     * @Assert\Length(
     *          max="255",
     *          maxMessage="address.name.max_length"
     * )
     */
    protected $name;

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
     */
    protected $city;

    /**
     * Contains the postal code of the address
     *
     * @var string|null
     *
     * @ORM\Column(
     *      name="postal_code",
     *      type="string",
     *      length=10,
     *      nullable=true,
     *      options={"comment"="Contains the postal code of the address"}
     * )
     *
     * @Assert\Length(
     *          max="10",
     *          maxMessage="address.postal_code.max_length"
     * )
     */
    protected $postalCode;

    /** *******************************
     * GETTER / SETTER
     */

    /**
     * @return int|null
     */
    public function getIdAddress(): ?int
    {
        return $this->idAddress;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     *
     * @return Address
     */
    public function setName(?string $name): Address
    {
        $this->name = $name;
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
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @param null|string $postalCode
     *
     * @return Address
     */
    public function setPostalCode(?string $postalCode): Address
    {
        $this->postalCode = $postalCode;
        return $this;
    }
}
