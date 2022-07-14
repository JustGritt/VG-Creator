<?php

namespace App\Model;

use App\Core\Sql;

class Page extends Sql
{

    protected $id;
    protected $slug;
    protected $is_active;
    protected $containts;
    protected $id_site;

    public function __construct(){

        $this->pdo = Sql::getInstance();
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setSlug($slug){
        $this->slug = $slug;
    }

    public function setIsActive($is_active){
        $this->is_active = $is_active;
    }

    public function setContaints($containts){
        $this->containts = $containts;
    }

    public function setIdSite($id_site){
        $this->id_site = $id_site;
    }

    public function getId(){
        return $this->id;
    }

    public function getSlug(){
        return $this->slug;
    }

    public function getIsActive(){
        return $this->is_active;
    }

    public function getContaints(){
        return $this->containts;
    }

    public function getIdSite(){
        return $this->id_site;
    }


}