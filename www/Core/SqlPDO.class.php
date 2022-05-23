<?php

namespace App\Core;

class SqlPDO extends Sql
{
    protected $link;

    protected function __getPDO()
    {
 
      try{
           return new \PDO( DBDRIVER.":host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME
              ,DBUSER, DBPWD , [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING]);
      }catch (\Exception $e){
          die("Erreur SQL : ".$e->getMessage());
      }

    }


    public function connect()
    {
        try {
            $this->link = $this->__getPDO();
        } catch (PDOException $e) {
            die("Erreur SQL : ".$e->getMessage());
        }

        $this->link->exec('SET SESSION sql_mode = \'\'');

        return $this->link;
    }
   

}  