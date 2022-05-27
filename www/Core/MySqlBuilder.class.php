<?php

namespace App\Core;

class MysqlBuilder implements QueryBuilder
{
    private $query;
    private $base;

    public function select($table, $columns) : QueryBuilder
    {
        $this->reset();
        $this->query->base = "SELECT " . implode(', ', $columns) . " FROM " . $table;
        return $this;
    }

    public function reset()
    {
        $this->query = new \stdClass();
    }

    public function where($column, $value , $operator = '=') : QueryBuilder
    {
        $this->query->where[] = "  " . $column . " " . $operator . " '" . $value . "'";
        return $this;
    }

    public function limit($from, $offset) : QueryBuilder{
        $this->query->limit = " LIMIT " . $from . ", " . $offset;
        return $this;
    }

    public function getQuery()
    {
        $sql = $this->query->base;
        if (!empty($this->query->where)) { 
            $sql .= " WHERE " . implode(" AND ", $this->query->where);
         
        }
        
        if (isset($this->query->limit)) {
            $sql .=  " " .$this->query->limit;
        }
        $sql .= ';';

        return $sql;
    }

    public function insert($table, $columns = ' ', $values) : QueryBuilder
    {
        $this->reset();
        $this->query->base = "INSERT INTO " . $table;
        if (!empty($columns)) {
            $this->query->base .= " (" . implode(', ', $columns) . ")";
        }
        $this->query->base .= " VALUES (" . implode(', ', $values) . ")";
        return $this;
        
    }
    
    public function delete($table) : QueryBuilder
    {
        $this->reset();
        $this->query->base = "DELETE FROM " . $table;
        return $this;
    }
    /*
    $this->reset();
        $this->query->base = "INSERT INTO " . $table . " (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";
        return $this;
     */  

}
