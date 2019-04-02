<?php
/**
 * 数据库操作基础服务，扩展DBAL的Connection实现基本操作
 * User: lijiangbo
 * Date: 2019-03-29
 * Time: 13:41
 */
namespace Code\Framework\Core;

use Code\Framework\App;
use Code\Framework\Core\Builder\QueryBuilder;
use Code\Framework\Exception\DaoException;
use Doctrine\DBAL\Connection;

abstract class BaseDb implements DaoInterface
{
    protected $app;

    protected $table = null;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @return Connection
     */
    protected function Db()
    {
        return $this->app['db'];
    }

    /**
     * 抛出数据库异常
     * @param string $message
     * @param int $code
     * @return DaoException
     */
    private function createDaoException($message = '', $code = 0)
    {
        return new DaoException($message, $code);
    }

    public function table()
    {
        return $this->table;
    }

    /**
     * 查询数据
     * @param $id
     * @param string $select 查询字段
     * @param bool $isLock 是否锁表查询
     * @return null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function get($id, $select = '*', $isLock = false)
    {
        $sql = "SELECT {$select} FROM {$this->table()} WHERE id = ?".($isLock ? ' FOR UPDATE' : '');

        return $this->Db()->fetchAssoc($sql, [$id]) ?: null;
    }

    /**
     * 新增数据
     * @param $fields
     * @return null
     * @throws DaoException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function create($fields)
    {
        $affected = $this->Db()->insert($this->table(), $fields);
        if ($affected <= 0) {
            throw $this->createDaoException('Insert error.');
        }

        $lastInsertId = isset($fields['id']) ? $fields['id'] : $this->db()->lastInsertId();

        return $this->get($lastInsertId);
    }

    /**
     * 更新数据记录
     * @param $id
     * @param array $fields
     * @return int 返回受影响的行数（0，代表没有更新）
     * @throws \Doctrine\DBAL\DBALException
     */
    public function update($id, array $fields)
    {
        return $this->db()->update($this->table, $fields, ['id' => $id]);
    }

    /**
     * 删除数据记录
     * @param $id
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function delete($id)
    {
        return $this->db()->delete($this->table, ['id' => $id]);
    }

    /**
     * 条件查询
     * @param $conditions
     * @param $orderBys
     * @param $start
     * @param $limit
     * @param string $select
     * @return mixed[]
     * @throws DaoException
     */
    public function search($conditions, $orderBys, $start, $limit, $select = '*')
    {
        $queryBuilder = $this->createQueryBuilder($conditions)
            ->setFirstResult($start)
            ->setMaxResults($limit)
            ->select($select);

        foreach ($orderBys ?: array() as $order => $sort) {
            $queryBuilder->addOrderBy($order, $sort);
        }

        return $queryBuilder->execute()->fetchAll();
    }

    /**
     * 根据条件查询当前的数量
     * @param $conditions
     * @return int
     * @throws DaoException
     */
    public function count($conditions)
    {
        $builder = $this->createQueryBuilder($conditions)
            ->select('COUNT(*)');


        return (int) $builder->execute()->fetchColumn(0);
    }

    /**
     * @param $conditions
     * @return QueryBuilder
     * @throws DaoException
     */
    protected function createQueryBuilder($conditions)
    {
        $conditions = array_filter(
            $conditions,
            function ($value) {
                if ('' === $value || null === $value) {
                    return false;
                }

                if (is_array($value) && empty($value)) {
                    return false;
                }

                return true;
            }
        );

        $builder = $this->getQueryBuilder($conditions);
        $builder->from($this->table(), $this->table());

        $declares = $this->declares();
        $declares['conditions'] = isset($declares['conditions']) ? $declares['conditions'] : [];

        foreach ($declares['conditions'] as $conditionName => $condition) {
            $builder->andWhere($condition);
        }

        return $builder;
    }

    /**
     * @param $fields
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function getByFields($fields)
    {
        $placeholders = array_map(
            function ($name) {
                return "{$name} = ?";
            },
            array_keys($fields)
        );

        $sql = "SELECT * FROM {$this->table()} WHERE ".implode(' AND ', $placeholders).' LIMIT 1 ';

        return $this->db()->fetchAssoc($sql, array_values($fields)) ?: null;
    }

    /**
     * @param $field
     * @param $values
     * @return array|mixed[]
     */
    protected function findInField($field, $values)
    {
        if (empty($values)) {
            return array();
        }

        $marks = str_repeat('?,', count($values) - 1).'?';
        $sql = "SELECT * FROM {$this->table} WHERE {$field} IN ({$marks});";

        return $this->db()->fetchAll($sql, $values);
    }

    /**
     * @param $fields
     * @return mixed[]
     */
    protected function findByFields($fields)
    {
        $placeholders = array_map(
            function ($name) {
                return "{$name} = ?";
            },
            array_keys($fields)
        );

        $sql = "SELECT * FROM {$this->table()} WHERE ".implode(' AND ', $placeholders);

        return $this->db()->fetchAll($sql, array_values($fields));
    }

    /**
     * @param $conditions
     * @return QueryBuilder
     */
    private function getQueryBuilder($conditions)
    {
        return new QueryBuilder($this->db(), $conditions);
    }
}