<?php

namespace App\Model;

use App\Core\Sql;

class User_role extends Sql
{

    protected $id;
    protected $id_user;
    protected $id_role_site;
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

    public function getIdUuser()
    {
        return $this->id_user;
    }

    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getIdRoleSite()
    {
        return $this->id_role_site;
    }

    public function setIdRoleSite($id_role_site)
    {
        $this->id_role_site = $id_role_site;
    }

    public function getAllUserRoleForSite($id_user)
    {
        $request = "SELECT * FROM esgi_user_role WHERE id_user = ?";
        $sql = Sql::getInstance()->prepare($request);
        $sql->execute(array($id_user));
        return $sql->fetchObject(User_role::class);
    }

    public function getUserRoleForAllSite($id_user)
    {
        $request = "SELECT * FROM esgi_user_role WHERE id_user = ?";
        $sql = Sql::getInstance()->prepare($request);
        $sql->execute(array($id_user));
        return $sql->fetchAll(\PDO::FETCH_CLASS, User_role::class);
    }


}