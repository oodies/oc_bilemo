<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity Customer extend Person
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 *
 * @UniqueEntity(
 *     fields={
 *          "siret"
 *     },
 *     message="customer.siret.unique_entity"
 * )
 */
class Customer extends Person
{
    /**
     * Contains the SIRET identifier of the customer
     *
     * @var null|int
     *
     * @ORM\Column(
     *     name="siret",
     *     nullable=false,
     *     length=14,
     *     options={"comment"="Contains the SIRET identifier of the customer"}
     * )
     *
     * @Assert\NotBlank(
     *     message="customer.siret.not_blank"
     * )
     * @Assert\Length(
     *      max="14",
     *      maxMessage="customer.siret.max_length"
     * )
     */
    protected $siret;

    /** *******************************
     *      GETTER / SETTER
     */

    /**
     * @return int|null
     */
    public function getSiret(): ?int
    {
        return $this->siret;
    }

    /**
     * @param int|null $siret
     *
     * @return Customer
     */
    public function setSiret(?int $siret): Customer
    {
        $this->siret = $siret;
        return $this;
    }
}
