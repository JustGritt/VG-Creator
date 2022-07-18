<?php

namespace App\Model;

use App\Core\MysqlBuilder;
use App\Core\Sql;
use App\Helpers\Utils;

class Page extends Sql
{

    protected $id;
    public $slug;
    protected $is_active;
    protected $html;
    protected $css;
    protected $styles;
    protected $assets;
    protected $id_site;
    protected $pdo = null;
    protected $table;



    public function __construct(){
        $this->pdo = Sql::getInstance();
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param mixed $is_active
     */
    public function setIsActive($is_active): void
    {
        $this->is_active = $is_active;
    }

    /**
     * @return null
     */
    public function getHtml()
    {
        return urldecode($this->html);
    }

    /**
     * @param null $html
     */
    public function setHtml($html): void
    {
        $this->html = urlencode($html);
    }

    /**
     * @return mixed
     */
    public function getIdSite()
    {
        return $this->id_site;
    }

    /**
     * @param mixed $id_site
     */
    public function setIdSite($id_site): void
    {
        $this->id_site = $id_site;
    }

    /**
     * @return mixed
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * @param mixed $css
     */
    public function setCss($css): void
    {
        $this->css = $css;
    }

    /**
     * @return mixed
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * @param mixed $styles
     */
    public function setStyles($styles): void
    {
        $this->styles = $styles;
    }

    /**
     * @return mixed
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @param mixed $assets
     */
    public function setAssets($assets): void
    {
        $this->assets = $assets;
    }



    /**
     * @param mixed $id_site
     */
    public function getPageBySiteAndSlug(int $id_site,string $slug): self
    {
        $class_name = Utils::getDBNameFromClass($this);
        $query_builder = new MysqlBuilder();
        $request= $query_builder->select($class_name,["*"])->where("id_site", $id_site)->getQuery();
        $sql = $this->pdo->prepare($request);
        $sql->execute();
        return $sql->fetchObject(Page::class, []);
    }


}