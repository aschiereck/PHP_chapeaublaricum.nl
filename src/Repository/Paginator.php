<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Paginator
{
    /**
     * @var QueryBuilder
     */
    private $_queryBuilder;
    /**
     * @var int
     */
    private $_pageSize;
    /**
     * @var int
     */
    private $_pageNr;

    /**
     * @var DoctrinePaginator
     */
    private $_paginator;

    /**
     * @var int
     */
    private $_count = null;

    /**
     * Paginator constructor.
     * @param QueryBuilder $queryBuilder
     * @param int $pageSize
     * @param int $pageNr
     */
    public function __construct(QueryBuilder $queryBuilder, $pageNr = 1, $pageSize = 30)
    {
        $this->_queryBuilder = $queryBuilder;
        $this->_pageSize = $pageSize;
        $this->_pageNr = $pageNr;

        $this->_queryBuilder
            ->setFirstResult(($pageNr - 1) * $pageSize)
            ->setMaxResults($pageSize);

        $this->_paginator = new DoctrinePaginator($this->_queryBuilder);
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return iterator_to_array($this->_paginator->getIterator());
    }

    /**
     * @return int
     */
    public function getCount()
    {
        if(is_null($this->_count))
        {
            $this->_count = $this->_paginator->count();
        }
        return $this->_paginator->count();
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->_pageSize;
    }

    /**
     * @return int
     */
    public function getPageNr()
    {
        return $this->_pageNr;
    }

    /**
     * @return bool
     */
    public function isFirstPage()
    {
        return $this->_pageNr == 1;
    }

    /**
     * @return bool
     */
    public function isLastPage()
    {
        return $this->_pageNr == $this->getLastPageNr();
    }

    /**
     * @return int
     */
    public function getLastPageNr()
    {
        return ceil($this->getCount() / $this->_pageSize);
    }

}