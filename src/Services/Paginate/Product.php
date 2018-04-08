<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Services\Paginate;

use JMS\Serializer\Annotation\Type;
use Pagerfanta\Pagerfanta;

/**
 * Class Product
 *
 * @package App\Services\Paginate
 */
class Product extends PaginateAbstract
{
    /**
     * @Type("array<App\Entity\Product>")
     */
    public $data;

    /**
     * @param Pagerfanta $pagerfanta
     *
     * @throws \LogicException
     */
    public function __construct(Pagerfanta $pagerfanta)
    {
        $this->data = $pagerfanta->getCurrentPageResults();

       parent::__construct($pagerfanta);
    }
}
