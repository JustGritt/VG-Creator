<?php

namespace App\Model;

class Comment
{

    protected $id_comment = null;
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
        return $this->id_comment;
    }

    public function setId($id): int
    {
        return $this->id_comment = $id;
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




}