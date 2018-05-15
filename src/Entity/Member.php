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
 * Entity Member extends Entity Person
 *
 * @ORM\Table(name="member")
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route("app_api_member_get",
 *            absolute=true,
 *            parameters={ "id" = "expr(object.getId())" }
 *     ) )
 *
 * @Hateoas\Relation(
 *     "modify",
 *     href = @Hateoas\Route("app_api_member_patch",
 *            parameters={ "id" = "expr(object.getId())" },
 *            absolute=true,
 *           )
 * )
 *
 * @Hateoas\Relation(
 *     "delete",
 *     href = @Hateoas\Route("app_api_member_delete",
 *            parameters={ "id" = "expr(object.getId())" },
 *            absolute=true
 *          )
 * )
 */
class Member extends Person
{
    /**
     * @var customer
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Customer"
     * )
     * @ORM\JoinColumn(
     *      name="customer_id",
     *      referencedColumnName="id",
     *      nullable=false
     * )
     *
     * @Assert\Valid()
     *
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"Customer"})
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
