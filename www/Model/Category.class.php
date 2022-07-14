<?php
namespace App\Model;

use App\Core\Security;
use App\Core\Sql;


class Category extends Sql{

    public $id = null;
    public $name = null;
    public $id_site = null;
    private $builder = BUILDER;

    public function __construct($name= '',$id_site='', $id_category='' ){
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));
        $this->setName($name);
        $this->setIdSite($id_site);
        $this->setIdCategory($id_category);
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
    public function getIdCategory(): string
    {
       return $this->id ;
    }


    public function getAddCategorieFrom(){
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"./categorie/create",
                "submit"=>"Create",

            ],
            'inputs'=>[
                "Name"=>[
                    "type"=>"text",
                    "placeholder"=>"Creer une categorie",
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

   

}
