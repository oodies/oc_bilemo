<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Services;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class Paginate
 *
 * @package App\Services
 */
class Paginator
{
    /**
     * @param QueryBuilder $qb
     * @param              $maxPerPage
     * @param int          $currentPage
     *
     * @return Pagerfanta
     *
     * @throws \Pagerfanta\Exception\LessThan1CurrentPageException
     * @throws \Pagerfanta\Exception\LessThan1MaxPerPageException
     * @throws \Pagerfanta\Exception\NotIntegerCurrentPageException
     * @throws \Pagerfanta\Exception\NotIntegerMaxPerPageException
     * @throws \Pagerfanta\Exception\OutOfRangeCurrentPageException
     */
    public function paginate(QueryBuilder $qb, $maxPerPage, $currentPage)
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        $pager
            ->setMaxPerPage($maxPerPage)
            ->setCurrentPage($currentPage);

        return $pager;
    }
}
