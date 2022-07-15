<?php

namespace App\Core;

use App\Core\Handler;
use App\Core\Sql;

class PaginatedQuery extends Sql
{

    protected $pdo;
    protected $query;
    protected $queryCount;
    protected $mappingClass;
    protected $perPage;
    protected $count;

    public function __construct(String $query, String $queryCount, String $mappingClass, int $perPage = 10)
    {
        $this->pdo = Sql::getInstance();
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->mappingClass = $mappingClass;
        $this->perPage = $perPage;
    }

    /**
     * @throws \Exception
     */
    public function getItems(): array
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if ($currentPage > $pages) {
            throw  new \Exception('Page not found');
        }

        $offset = $this->perPage * ($currentPage - 1) ;
        $request = $this->query . " LIMIT {$this->perPage} OFFSET {$offset}";
        $sql = $this->pdo->prepare($request);
        $sql->execute();

        return $sql->fetchAll(\PDO::FETCH_CLASS, $this->mappingClass);
    }

    /**
     * @throws \Exception
     */
    private function getCurrentPage()
    {
        return Handler::getPostiveInt('page', 1);
    }

    private function getPages(){
        if ($this->count === null) {
            $this->count = $this->pdo->query($this->queryCount)
                ->fetch(\PDO::FETCH_NUM)[0];
        }
        return  ceil($this->count / $this->perPage);
    }

    public function previousLink(String $link): ?String
    {
        $currentPage = $this->getCurrentPage();
        if ($currentPage <= 1) return null;
        if ($currentPage > 2) $link .= "?page=" . ($currentPage - 1);
        return "<a href={$link}>Page precedente</a>";
    }

    public function nextLink(String $link): ?String
    {
        $currentPage = $this->getCurrentPage();
        $page = $this->getPages();
        if ($currentPage >= $page) return null;
        $link .= "?page=" . ($currentPage + 1);
        return "<a href={$link}>Page suivante</a>";
    }

}