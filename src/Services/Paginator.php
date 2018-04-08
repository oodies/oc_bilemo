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
     * @param int          $maxPerPAge
     * @param int          $currentPage
     *
     * @return Pagerfanta
     * @throws \LogicException
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
