<?php
namespace App\Core;


interface QueryBuilder
{
    public function select($table, $columns) : QueryBuilder;
    public function limit($from , $offset) : QueryBuilder;
    public function where($column, $value, $operator = '=') : QueryBuilder;
    public function rightJoin(string $table, string $fk, string $pk): QueryBuilder;
    public function leftJoin(string $table, string $fk, string $pk): QueryBuilder;
    public function getQuery();
    public function insert(string $table, array $columns): QueryBuilder;
    public function delete($table) : QueryBuilder;
    //public function leftJoin($table, $condition);
}
