<?php

namespace Code\Framework\Utility;

class Paginator
{
    const DEFAULT_PAGE = 1;

    const DEFAULT_LIMIT = 20;

    protected $pageSize;

    protected $total;

    protected $page;

    protected $totalPage;

    /**
     * @param int $total
     * @param int $page
     * @param int $pageSize
     * @return $this
     */
    public function init(int $total, int $page = self::DEFAULT_PAGE, int $pageSize = self::DEFAULT_LIMIT)
    {
        $this->setTotal($total);
        $this->setPageSize($pageSize);

        $totalPage = ceil($total / $pageSize) ?: 1;

        $this->setTotalPage($totalPage);
        $this->setPage($page <= 0 ? 1 : ($page > $totalPage ? $totalPage : $page));

        return $this;
    }

    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    public function setTotalPage($totalPage)
    {
        $this->totalPage = $totalPage;

        return $this;
    }

    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getTotalPage()
    {
        return $this->totalPage;
    }

    public function getFirstPage()
    {
        return 1;
    }

    public function getLastPage()
    {
        return ceil($this->total / $this->pageSize);
    }

    public function getPreviousPage()
    {
        $diff = $this->page - $this->getFirstPage();

        return $diff > 0 ? $this->page - 1 : $this->getFirstPage();
    }

    public function getNextPage()
    {
        $diff = $this->getLastPage() - $this->page;

        return $diff > 0 ? $this->page + 1 : $this->page;
    }

    //获取当前页的起始数
    public function getOffsetCount()
    {
        return ($this->page - 1) * $this->pageSize;
    }

    //获取当前页的结束数
    public function getEndCount()
    {
        return $this->getOffsetCount() + $this->pageSize - 1;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}
