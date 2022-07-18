<?php

namespace App\Core;

class PostgresSqlBuilder extends MySqlBuilder
{
    private $query;

    public function limit(int $from, int $offset): QueryBuilder
    {
        $this->query->limit = " LIMIT " . $from . " OFFSET " . $offset;
        return $this;
    }







}
