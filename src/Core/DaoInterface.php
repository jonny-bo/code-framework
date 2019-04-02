<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-03-29
 * Time: 14:52
 */

namespace Code\Framework\Core;

interface DaoInterface extends DbInterface
{
    public function get($id, $select, $isLock);

    public function create($fields);

    public function update($id, array $fields);

    public function delete($id);

    public function search($conditions, $orderBys, $start, $limit, $select);

    public function count($conditions);
}
