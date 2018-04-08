<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Services\Paginate;

use Pagerfanta\Pagerfanta;

/**
 * Class PaginateAbstract
 *
 * @package App\Services\Paginate
 */
class PaginateAbstract
{
    /**
     * @var array
     */
    public $paginator;

    /**
     * PaginateAbstract constructor.
     *
     * @param Pagerfanta $pagerfanta
     *
     * @throws \LogicException
     * @throws \Pagerfanta\Exception\LogicException
     */
    public function __construct(Pagerfanta $pagerfanta)
    {
        // Set paginator property
        $this->addMetaPaginator('current_page', $pagerfanta->getCurrentPage());
        $this->addMetaPaginator('max_per_page', $pagerfanta->getMaxPerPage());
        $this->addMetaPaginator('get_previous_page', $pagerfanta->hasPreviousPage() ? $pagerfanta->getPreviousPage() : false);
        $this->addMetaPaginator('get_next_page', $pagerfanta->hasNextPage() ? $pagerfanta->getNextPage() : false);
        $this->addMetaPaginator('current_page_offset_start', $pagerfanta->getCurrentPageOffsetStart());
        $this->addMetaPaginator('current_page_offset_end', $pagerfanta->getCurrentPageOffsetEnd());
        $this->addMetaPaginator('current_page_results', count($pagerfanta->getCurrentPageResults()));
        $this->addMetaPaginator('nb_results', $pagerfanta->getNbResults());
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @throws \LogicException
     */
    private function addMetaPaginator(string $name, $value)
    {
        if (isset($this->paginator[$name])) {
            throw new \LogicException(
                sprintf(
                    'This meta already exists. You are trying to override this meta, use the setMeta method instead for the %s meta.',
                    $name
                )
            );
        }
        $this->setMetaPaginator($name, $value);
    }

    /**
     * @param string $name
     * @param        $value
     */
    private function setMetaPaginator(string $name, $value)
    {
        $this->paginator[$name] = $value;
    }
}
