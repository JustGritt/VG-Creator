<?php
namespace App\Model;

use App\Core\Security;
use App\Core\Sql;


class Category extends Sql{

    public $id = null;
    public $name = null;
    public $id_site = null;
    private $builder = BUILDER;

    public function __construct(){
        $this->pdo = Sql::getInstance();
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));
    }

    /**
     * @return array
     */
    public function getCategories():array
    {

        $queryBuilder = new $this->builder();
        $request = $queryBuilder->select('esgi_category', ['*'])->getQuery();
        $result = Sql::getInstance()->query($request)->fetchAll();
        return array_map(function ($v){
            return new Category($v['name'], $v['id_site'], $v['id']);
        }, $result);
    }

    /**
     * @return array
     */
    public function getCategoriesFromSite($id_site):array
    {
        $queryBuilder =  new $this->builder();
        $request = $queryBuilder->select('esgi_category', ['*'])
            ->where("id_site",$id_site )
            ->getQuery();
        $result = Sql::getInstance()->query($request)->fetchAll();

        return array_map(function ($v){
            return new Category($v['name'], $v['id_site'], $v['id']);
        }, $result);
    }

    public function getCategoriesBySite($id_site)
    {
        $sql = "SELECT * FROM esgi_category WHERE id_site = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_site]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Category::class);
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return null
     */
    public function getIdSite()
    {
        return $this->id_site;
    }

    /**
     * @param null $id_site
     */
    public function setIdSite($id_site): void
    {
        $this->id_site = $id_site;
    }

    /**
     * @param null $id_category
     */
    public function setIdCategory($id_category): void
    {
        $this->id = $id_category;
    }

    /**
     * @param string
     */
    public function getId()
    {
       return $this->id ;
    }


    public function getAddCategorieFrom(){
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "submit"=>"Ajouter",

            ],
            'inputs'=>[
                "name"=>[
                    "type"=>"text",
                    "placeholder"=>"Nouvelle categorie",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"categoryForm",
                    "error"=>"Category incorrect"
                ],
                'csrf_token'=>[
                    "type"=>"hidden",
                    "class"=>"inputForm",
                    "value"=> Security::generateCsfrToken(),
                    "id"=>"csrf_token"
                ]
            ]
        ];

    }

    public function isUniqueCategory($name, $id_site)
    {
        $sql = "SELECT * FROM esgi_category WHERE name = ? and id_site = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($name, $id_site));
        return $stmt->rowCount() == 1;
    }

    public function getCategoryById($id) 
    {
        $sql = "SELECT * FROM esgi_category WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchObject(Category::class);
    }

}
