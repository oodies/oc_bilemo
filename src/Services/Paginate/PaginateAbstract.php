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
    protected $metas;

    /**
     * PagerfantaMeta constructor.
     *
     * @param Pagerfanta $pagerfanta
     *
     * @throws \LogicException
     * @throws \Pagerfanta\Exception\LogicException
     */
    public function __construct(Pagerfanta $pagerfanta)
    {
        // Set metas property
        $remainingResults = $pagerfanta->getNbResults() - ($pagerfanta->getCurrentPage() * $pagerfanta->getMaxPerPage());
        $nextPageResults = ($remainingResults > $pagerfanta->getMaxPerPage()) ? $pagerfanta->getMaxPerPage() : $remainingResults;
        $this->addMeta('current_page', $pagerfanta->getCurrentPage());
        $this->addMeta('max_per_page', $pagerfanta->getMaxPerPage());
        $this->addMeta('get_previous_page', $pagerfanta->hasPreviousPage() ? $pagerfanta->getPreviousPage() : false);
        $this->addMeta('get_next_page', $pagerfanta->hasNextPage() ? $pagerfanta->getNextPage() : false);
        $this->addMeta('current_page_offset_start', $pagerfanta->getCurrentPageOffsetStart());
        $this->addMeta('current_page_offset_end', $pagerfanta->getCurrentPageOffsetEnd());
        $this->addMeta('current_page_results', count($pagerfanta->getCurrentPageResults()));
        $this->addMeta('next_page_results', $nextPageResults);
        $this->addMeta('nb_results', $pagerfanta->getNbResults());
        $this->addMeta('remaining_results', $remainingResults);
    }

    /**
     * @return array
     */
    public function getMetas(): array
    {
        return $this->metas;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @throws \LogicException
     */
    private function addMeta(string $name, $value): void
    {
        if (isset($this->metas[$name])) {
            throw new \LogicException(
                sprintf(
                    'This meta already exists. You are trying to override this meta, use the setMeta method instead for the %s meta.',
                    $name
                )
            );
        }
        $this->setMeta($name, $value);
    }

    /**
     * @param string $name
     * @param        $value
     */
    private function setMeta(string $name, $value): void
    {
        $this->metas[$name] = $value;
    }
}
