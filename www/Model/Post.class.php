<?php
namespace App\Model;

use App\Core\Sql;
use App\Core\SqlPDO;
use App\Core\MySqlBuilder;
use App\Core\QueryBuilder;
use App\Core\Security;


class Post extends Sql{

    protected $id_post = null;
    protected $title = null;
    protected $body = null;
    protected $created_at = null;
    protected $status = null;
    protected $modified_by = null;
    protected $metatitle  = null;
    protected $metadescription  = null;
    protected $category = null;
    protected $author  = null;
    protected $pdo = null;


    public function __construct(){
        $this->pdo = Sql::getInstance();
        //$this->pdo = SqlPDO::connect();
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));
    }

    /**
     * @param string $id_post
     * @return Post
     */
    public function getOnePost(string $id_post): Post
    {
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $request = $queryBuilder->select('esgi_post', ['*'])->where('id_post', $id_post)->getQuery();
        $result = $this->pdo->query($request)->fetchObject();


        $this->setIdPost($result->id_post);
        $this->setTitle($result->title);
        $this->setBody($result->body);
        $this->setStatus($result->status);
        $this->setMetatitle($result->metatitle);
        $this->setMetadescription($result->metadescription);
        $this->setCreatedAt($result->created_at);
        $this->setCategory($result->category);
        $this->setAuthor($result->author);

        return $this;
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id_post;
    }

    /**
     * @param int $id_post
     */
    public function setIdPost(int $id_post): void
    {
        $this->id_post = $id_post;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title):void
    {
        $this->title = $title;
    }

    /**
     * @return null|string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param null $body
     */
    public function setBody($body): void
    {

       // $body = cleand($body);
        $this->body = $body;
    }

    /**
     * @return null
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param null $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param null $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return null
     */
    public function getModifiedBy()
    {
        return $this->modified_by;
    }

    /**
     * @param null $modified_by
     */
    public function setModifiedBy($modified_by): void
    {
        $this->modified_by = $modified_by;
    }

    /**
     * @return null|string
     */
    public function getMetatitle()
    {
        return $this->metatitle;
    }

    /**
     * @param null $metatitle
     */
    public function setMetatitle($metatitle): void
    {
        $this->metatitle = $metatitle;
    }



    /**
     * @return ?User
     */
    public function getAuthor(): ?User
    {
        $user = new User();
        return $user->getOneUser($this->author);
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return null|string
     */
    public function getMetadescription():string
    {
        return $this->metadescription;
    }

    /**
     * @param null $metadescription
     */
    public function setMetadescription($metadescription): void
    {
        $this->metadescription = $metadescription;
    }


}
