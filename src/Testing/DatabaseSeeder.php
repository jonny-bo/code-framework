<?php

namespace Code\Framework\Testing;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;

abstract class DatabaseSeeder
{
    /**
     * @var Connection
     */
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param bool $isRun
     * @return mixed
     */
    abstract public function run($isRun = true);

    /**
     * 初始化插入数据
     * @param $table
     * @param array $rows
     * @param $isRun
     * @return ArrayCollection
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function insertRows($table, array $rows, $isRun)
    {
        if ($isRun) {
            foreach ($rows as $row) {
                $this->db->insert($table, $row);
            }
        }

        return new ArrayCollection($rows);
    }
}
