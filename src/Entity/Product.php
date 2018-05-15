<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route("app_api_product_get",
 *            absolute=true,
 *            parameters={ "idProduct" = "expr(object.getId())" }
 *     ) )
 */
class Product
{
    /**
     * Contains the ID of the product
     *
     * @var null|int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     length=11,
     *     nullable=false,
     *     unique=true,
     *     options={"unsigned"=true,
     *              "comment"="Contains the ID of the product"
     *     }
     * )
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"Default"})
     */
    protected $id;

    /**
     * Contains the name of the product
     *
     * @var null|string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     *     nullable=false,
     *     options={"comment"="Contains the name of the product"}
     * )
     *
     * @Assert\NotBlank(
     *         message="product.name.not_blank"
     * )
     * @Assert\Length(
     *     max="255",
     *     maxMessage="product.name.max_length"
     * )
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"Default"})
     */
    protected $name;

    /**
     * Contains the code of the product
     *
     * @var null|string
     *
     * @ORM\Column(
     *     type="string",
     *     length=64,
     *     nullable=true,
     *     options={"comment"="Contains the code of the product"}
     * )
     *
     * @Assert\Length(
     *     max="64",
     *     maxMessage="product.code.max_length"
     * )
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"Default"})
     */
    protected $code;

    /**
     * Contains the description of the product
     *
     * @var null|string
     *
     * @ORM\Column(
     *     type="text",
     *     nullable=true,
     *     options={"comment"="Contains the description of the product"}
     * )
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"Details"})
     */
    protected $description;

    /**
     * Contains the date of creation of the product
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="create_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="Contains the date of creation of the product"}
     * )
     *
     * @Assert\DateTime( message="product.create_at.date_time")
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"Details"})
     */
    protected $createAt;

    /**
     * Contains the update date of the product
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name="update_at",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="Contains the update date of the product"}
     * )
     *
     * @Assert\DateTime( message="product.update_at.date_time")
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"Details"})
     */
    protected $updateAt;

    /** *******************************
     *  ASSOCIATION MAPPING
     */

    /**
     * The brand of the product
     *
     * @var null|Brand
     *
     * @ORM\ManyToOne(
     *      targetEntity="App\Entity\Brand",
     *      cascade={"persist"}
     * )
     * @ORM\JoinColumn(
     *      name="brand_id",
     *      referencedColumnName="id",
     *      nullable=false
     * )
     *
     * @Assert\Valid()
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"Details"})
     */
    protected $brand;


    public function __construct()
    {
        $this->createAt = $this->updateAt = new \DateTime();
    }
    /** *******************************
     *  GETTER / SETTER
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     *
     * @return Product
     */
    public function setName(?string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param null|string $code
     *
     * @return Product
     */
    public function setCode(?string $code): Product
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     *
     * @return Product
     */
    public function setDescription(?string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreateAt(): \DateTime
    {
        return $this->createAt;
    }

    /**
     * @param \DateTime $createAt
     *
     * @return Product
     */
    public function setCreateAt(\DateTime $createAt): Product
    {
        $this->createAt = $createAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateAt(): \DateTime
    {
        return $this->updateAt;
    }

    /**
     * @param \DateTime $updateAt
     *
     * @return Product
     */
    public function setUpdateAt(\DateTime $updateAt): Product
    {
        $this->updateAt = $updateAt;
        return $this;
    }

    /**
     * @return Brand|null
     */
    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand|null $brand
     *
     * @return Product
     */
    public function setBrand(?Brand $brand): Product
    {
        $this->brand = $brand;
        return $this;
    }
}
