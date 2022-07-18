<?php

namespace App\Core;

class MysqlBuilder implements QueryBuilder
{
    private $query;

    public function select($table, $columns) : QueryBuilder
    {
        $this->reset();
        $this->query->base = "SELECT " . implode(', ', $columns) . " FROM " . $table;
        return $this;
    }

    /**
     * @param $table
     * @return QueryBuilder
     */
    public function delete($table) : QueryBuilder
    {
        $this->reset();
        $this->query->base = "DELETE FROM " . $table;
        return $this;
    }

    public function reset()
    {
        $this->query = new \stdClass();
    }

    /*
    public function where($column, $value , $operator = '=') : QueryBuilder
    {
        $this->query->where[] = "  " . $column . " " . $operator . " '" . $value . "'";
        return $this;
    }
    */
    public function where($column, $value, $operator = "="): QueryBuilder
    {
        $this->query->where[] = " " . $column . " " . $operator .  " " . $value ;
        return $this;
    }

    public function limit($from, $offset) : QueryBuilder{
        $this->query->limit = " LIMIT " . $from . ", " . $offset;
        return $this;
    }

    public function getQuery(): string
    {
        $query = $this->query;
        $sql = $query->base;

        if (!empty($query->join)) {
            $sql .= implode(' ', $query->join);
        }

        if (!empty($query->where)) {
            $sql .= " WHERE " . implode(' AND ', $query->where);
        }

        if (isset($query->limit)) {
            $sql .= $query->limit;
        }

        $sql .= ";";
        return $sql;
    }

    public function rightJoin(string $table, string $fk, string $pk): QueryBuilder
    {
        $this->query->join[] = " RIGHT JOIN " . $table . " ON " . $pk . " = " . $fk;
        return $this;
    }

    public function leftJoin(string $table, string $fk, string $pk): QueryBuilder
    {
        $this->query->join[] = " LEFT JOIN " . $table . " ON " . $pk . " = " . $fk;
        return $this;
    }

    /*
    public function insert($table, $columns = ' ', $values) : QueryBuilder
    {
        $this->reset();
        $this->query->base = "INSERT INTO " . $table;
        if (!empty($columns)) {
            $this->query->base .= " (" . implode(', ', $columns) . ")";
        }
        $this->query->base .= " VALUES (" . implode(', ', $values) . ")";
        return $this;
        
    }*/
    public function insert(string $table, array $columns): QueryBuilder
    {
        $this->reset();
        $this->query->base = "INSERT INTO " . $table . " (" . implode(", ", $columns) . ") VALUES (";
        for ($i = 0; $i < count($columns) ; $i++) {
            if($i == 0) {
                $this->query->base .= '?';
            } else {
                $this->query->base .= ', ?';
            }
        }
        $this->query->base .= ')';
        return $this;
    }

}



