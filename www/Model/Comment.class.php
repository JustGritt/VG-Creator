<?php

namespace App\Model;

use App\Core\Sql;
use App\Core\SqlPDO;
use App\Core\MySqlBuilder;
use App\Core\QueryBuilder;
use App\Core\Security;
use App\Core\View;

class Comment extends Sql
{

    protected $id = null;
    protected $body = null;
    protected $created_at = null;
    protected $status = null;
    protected $title = null;
    protected $id_post = null;
    protected $id_user = null;
    protected $id_comment_response = null;
    protected $table = null;
    protected $pdo = null;

    public function __construct(){
        $this->pdo = Sql::getInstance();
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): int
    {
        return $this->id = $id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody($body): string
    {
        return $this->body = $body;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus($status): int
    {
        return $this->status = $status;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle($title): string
    {
        return $this->title = $title;
    }

    public function getIdPost(): ?int
    {
        return $this->id_post;
    }

    public function setIdPost($id_post): int
    {
        return $this->id_post = $id_post;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser($id_user): int
    {
        return $this->id_user = $id_user;
    }

    public function getIdCommentResponse(): ?int
    {
        return $this->id_comment_response;
    }

    public function setIdCommentResponse($id_comment_response): int
    {
        return $this->id_comment_response = $id_comment_response;
    }

    public function getAllCommentsPublished($id_site)
    {
        $sql = "SELECT ec.* 
        FROM esgi_comment ec
        LEFT JOIN esgi_post ep on ec.id_post = ep.id
        LEFT JOIN esgi_user_role ur on ep.author = ur.id_user
        LEFT JOIN esgi_role_site rs on ur.id_role_site = rs.id
        WHERE rs.id_site = {$id_site} AND ec.status = 1;";
        $query = $this->pdo->prepare($sql);
        $query->execute(array($id_site));
        $result_draft = $query->fetchAll(\PDO::FETCH_CLASS, Comment::class);

        return $result_draft;
    }

    public function getAllCommentsDraft($id_site)
    {
        $sql = "SELECT ec.* 
        FROM esgi_comment ec
        LEFT JOIN esgi_post ep on ec.id_post = ep.id
        LEFT JOIN esgi_user_role ur on ep.author = ur.id_user
        LEFT JOIN esgi_role_site rs on ur.id_role_site = rs.id
        WHERE rs.id_site = {$id_site} AND ec.status = 0;";
        $query = $this->pdo->prepare($sql);
        $query->execute(array($id_site));
        $result_draft = $query->fetchAll(\PDO::FETCH_CLASS, Comment::class);

        return $result_draft;
    }

    public function getAllCommentsBanned($id_site)
    {
        $sql = "SELECT ec.* 
        FROM esgi_comment ec
        LEFT JOIN esgi_post ep on ec.id_post = ep.id
        LEFT JOIN esgi_user_role ur on ep.author = ur.id_user
        LEFT JOIN esgi_role_site rs on ur.id_role_site = rs.id
        WHERE rs.id_site = {$id_site} AND ec.status = 2;";
        $query = $this->pdo->prepare($sql);
        $query->execute(array($id_site));
        $result_draft = $query->fetchAll(\PDO::FETCH_CLASS, Comment::class);

        return $result_draft;
    }

    public function getComment($id_comment) 
    {
        $sql = "SELECT * FROM esgi_comment WHERE id = {$id_comment};";
        $query = $this->pdo->prepare($sql);
        $query->execute(array($id_comment));
        
        return $query->fetchObject(Comment::class);
    }

    
}