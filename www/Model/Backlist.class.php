<?php

namespace App\Model;

use App\Core\Sql;

class Backlist extends Sql
{
    protected $id = null;
    protected $id_site = null;
    protected $id_user = null;
    protected $pdo = null;
    protected $table;

    public function __construct()
    {

        $this->pdo = Sql::getInstance();
        //$this->pdo = SqlPDO::connect();
        $calledClassExploded = explode("\\", get_called_class());
        $this->table = strtolower(DBPREFIXE . end($calledClassExploded));
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdSite()
    {
        return $this->id_site;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setIdSite($id_site)
    {
        $this->id_site = $id_site;
    }

    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getBackListForSite($id_site)
    {
        $request = "SELECT eu.firstname , eu.pseudo, rs.name
        FROM `esgi_backlist` eb
        LEFT JOIN esgi_site es on eb.id_site = es.id
        LEFT JOIN esgi_user_role ur on eb.id_user = ur.id_user
        LEFT JOIN esgi_role_site rs on  ur.id_role_site = rs.id
        LEFT JOIN esgi_user eu on eb.id_user = eu.id
        WHERE eb.id_site  = '$id_site'";

        $sql = $this->pdo->prepare($request);
        $sql->execute(array($id_site));
        return $sql->fetchAll();
    }

    /**
     * Get all backlist for a site
     *
     * @param int $id_site
     * @return array
     */
    /*

    public function getBackListForSite($id)
    {
        $request = "SELECT eu.firstname , eu.pseudo, rs.name
        FROM `esgi_backlist` eb
        LEFT JOIN esgi_site es on eb.id_site = es.id
        LEFT JOIN esgi_user_role ur on eb.id_user = ur.id_user
        LEFT JOIN esgi_role_site rs on  ur.id_role_site = rs.id
        LEFT JOIN esgi_user eu on eb.id_user = eu.id
        WHERE eb.id  = '$id'";

        $sql = $this->pdo->prepare($request);
        $sql->execute(array($id));
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }*/

    public function isUserBacklisted($id_user)
    {
        $request = "SELECT * FROM `esgi_backlist` WHERE id_user = '$id_user'";
        $sql = $this->pdo->prepare($request);
        $sql->execute(array($this->id_user));
        return $sql->rowCount() == 1;
    }

    public function deleteUserFromBackList($id_site, $id_user)
    {
        $request = "DELETE FROM `esgi_backlist` WHERE id_site = '$id_site' AND id_user = '$id_user'";
        $sql = $this->pdo->prepare($request);
        return $sql->execute(array($id_site, $id_user));
    }

}