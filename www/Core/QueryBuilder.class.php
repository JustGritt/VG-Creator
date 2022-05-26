<?php
namespace App\Core;


interface QueryBuilder
{
    public function select($table, $columns) : QueryBuilder;
    //public function from($table);
    public function limit($from , $offset) : QueryBuilder;
    public function where($column, $value, $operator = '=') : QueryBuilder;
    /*
    public function orderBy($field, $order);
    public function groupBy($field);
    public function having($condition);
    public function get();
    public function getOne();
    public function getCount();
    public function getSum($field);
    public function getAvg($field);
    public function getMin($field);
    public function getMax($field);
    */
    public function getQuery();
    public function insert($table, $columns = ' ',  $values) : QueryBuilder;
    //public function leftJoin($table, $condition);
}
