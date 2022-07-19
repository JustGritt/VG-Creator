<?php
namespace App\Model;

use App\Core\Sql;
use App\Core\MySqlBuilder;
use App\Core\SqlPDO;
use App\Core\QueryBuilder;
use App\Core\Security;
use App\Helpers\Utils;


class Post extends Sql{

    protected $id = null;
    protected $title = null;
    protected $body = null;
    protected $created_at = null;
    protected $status = null;
    protected $modified_by = null;
    protected $metatitle  = null;
    protected $metadescription  = null;
    protected $category = null;
    protected $author  = null;
    protected $id_site = null;
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
        $queryBuilder = new MySqlBuilder();
        $request = $queryBuilder->select(Utils::getDBNameFromClass($this), ['*'])->where('id', $id_post)->getQuery();
        return $this->pdo->query($request)->fetchObject(Post::class);
    }

    /**
     * @param string $id_post
     * @return bool
     
    public function delete(string $id_post): bool
    {
        $queryBuilder = new MySqlBuilder();
        $request = $queryBuilder->delete(Utils::getDBNameFromClass($this))->where('id', $id_post)->getQuery();
        return $this->pdo->query($request)->execute();
    }*/

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id_post
     */
    public function setIdPost(int $id_post): void
    {
        $this->id = $id_post;
    }


    /**
     * @return null|int
     */
    public function getIdSite(): ?int
    {
        return $this->id_site;
    }

    /**
     * @param int $id_site
     */
    public function setIdSite(int $id_site): void
    {
        $this->id_site = $id_site;
    }

    /**
     * @return string|null
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
        return htmlspecialchars_decode(addslashes($this->body));
    }

    /**
     * @param null $body
     */
    public function setBody($body): void
    {

       // $body = cleand($body);
        $this->body = htmlspecialchars($body);
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
        return $user->getUserById($this->author);
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

    public function getAllPostsByUserId($id_user)
    {
        $queryBuilder = new MySqlBuilder();
        $request = $queryBuilder->select(Utils::getDBNameFromClass($this), ['*'])->where('author', $id_user)->getQuery();
        return $this->pdo->query($request)->fetchAll(\PDO::FETCH_CLASS, Post::class);
    }

}
