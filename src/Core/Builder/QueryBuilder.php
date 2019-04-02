<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-03-29
 * Time: 15:59
 */

namespace Code\Framework\Core\Builder;

use Code\Framework\Exception\DaoException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder as BaseQueryBuilder;

/**
 * @todo 优化条件查询的表达式，完善功能
 * Class QueryBuilder
 * @package Code\Framework\Core\Builder
 */
class QueryBuilder extends BaseQueryBuilder
{
    protected $conditions;

    public function __construct(Connection $connection, $conditions)
    {
        parent::__construct($connection);
        $this->conditions = $conditions;
    }

    /**
     * @param mixed $where
     * @return $this|BaseQueryBuilder
     */
    public function where($where)
    {
        if (!$this->isWhereInConditions($where)) {
            return $this;
        }

        return parent::where($where);
    }

    /**
     * @param mixed $where
     * @return $this|BaseQueryBuilder
     * @throws DaoException
     */
    public function andWhere($where)
    {
        if (!$this->isWhereInConditions($where)) {
            return $this;
        }

        if ($this->matchInCondition($where)) {
            return $this->addWhereIn($where);
        }

        if ($likeType = $this->matchLikeCondition($where)) {
            return $this->addWhereLike($where, $likeType);
        }

        return parent::andWhere($where);
    }

    /**
     * @param $where
     * @return BaseQueryBuilder
     */
    public function andStaticWhere($where)
    {
        return parent::andWhere($where);
    }

    /**
     * @param $where
     * @return BaseQueryBuilder
     * @throws DaoException
     */
    private function addWhereIn($where)
    {
        $conditionName = $this->getConditionName($where);

        if (!is_array($this->conditions[$conditionName])) {
            throw new DaoException('IN search parameter must be an Array type');
        }

        $marks = array();
        foreach (array_values($this->conditions[$conditionName]) as $index => $value) {
            $marks[] = ":{$conditionName}_{$index}";
            $this->conditions["{$conditionName}_{$index}"] = $value;
        }

        $where = str_replace(":{$conditionName}", implode(',', $marks), $where);

        return parent::andWhere($where);
    }

    /**
     * @param $where
     * @param $likeType
     * @return BaseQueryBuilder
     */
    private function addWhereLike($where, $likeType)
    {
        $conditionName = $this->getConditionName($where);

        //PRE_LIKE`
        if ('pre_like' == $likeType) {
            $where = preg_replace('/pre_like/i', 'LIKE', $where, 1);
            $this->conditions[$conditionName] = "{$this->conditions[$conditionName]}%";
        } elseif ('suf_like' == $likeType) {
            $where = preg_replace('/suf_like/i', 'LIKE', $where, 1);
            $this->conditions[$conditionName] = "%{$this->conditions[$conditionName]}";
        } else {
            $this->conditions[$conditionName] = "%{$this->conditions[$conditionName]}%";
        }

        return parent::andWhere($where);
    }

    /**
     * @return \Doctrine\DBAL\Driver\Statement|int
     */
    public function execute()
    {
        foreach ($this->conditions as $field => $value) {
            $this->setParameter(":{$field}", $value);
        }

        return parent::execute();
    }

    /**
     * @param $where
     * @return bool|string
     */
    private function matchLikeCondition($where)
    {
        $matched = preg_match('/\s+((PRE_|SUF_)?LIKE)\s+/i', $where, $matches);
        if (!$matched) {
            return false;
        }

        return strtolower($matches[1]);
    }

    /**
     * @param $where
     * @return false|int
     */
    private function matchInCondition($where)
    {
        return preg_match('/\s+(IN)\s+/i', $where);
    }

    /**
     * @param $where
     * @return bool
     */
    private function getConditionName($where)
    {
        $matched = preg_match('/:([a-zA-z0-9_]+)/', $where, $matches);
        if (!$matched) {
            return false;
        }

        return $matches[1];
    }

    /**
     * @param $where
     * @return bool
     */
    private function isWhereInConditions($where)
    {
        $conditionName = $this->getConditionName($where);
        if (!$conditionName) {
            return false;
        }

        return array_key_exists($conditionName, $this->conditions) && !$this->isEmpty($this->conditions[$conditionName]);
    }

    /**
     * @param $value
     * @return bool
     */
    protected function isEmpty($value)
    {
        return $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';
    }
}
