<?php

namespace App\Model;

use App\Core\Sql;

class Role_site extends Sql
{

    protected $id;
    protected $name;
    protected $id_site;
    protected $pdo;
    protected $table;

    public function __construct()
    {
        $this->pdo = Sql::getInstance();
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getIdSite()
    {
        return $this->id_site;
    }

    public function setIdSite($id_site)
    {
        $this->id_site = $id_site;
    }

    public function createRoleForSite($id_site) 
    {
        $roles = ['Admin', 'Moderator', 'Editor', 'Subscriber'];
        foreach($roles as $role)
        {
            // $sql = "INSERT INTO esgi_role_site(name, id_site) VALUES(".$role.", ". $id_site . ")" ;  
            // var_dump($sql);
            // $this->pdo->query($sql);

            $role_site = new Role_site();
            $role_site->setName($role);
            $role_site->setIdSite($id_site);
            $role_site->save();
            
        }

    } 

}