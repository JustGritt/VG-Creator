<?php

namespace App\Model;

use App\Core\FlashMessage;
use App\Core\PaginatedQuery;
use App\Core\Security;
use App\Core\Sql;
use App\Core\QueryBuilder;

class Document extends Sql
{

    protected $id = null;
    protected $type = null;
    protected $path = null;
    protected $id_site = null;
    protected $id_user = null;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType($type): string
    {
        return $this->type = $type;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath($path): string
    {
        return $this->path = $path;
    }

    public function getIdSite(): ?int
    {
        return $this->id_site;
    }

    public function setIdSite($id_site): int
    {
        return $this->id_site = $id_site;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser($id_user): int
    {
        return $this->id_user = $id_user;
    }


    public function getUploadForm(){

        return [
            "config"=>[
                "method"=>"POST",
                'id' => 'uploadForm',
                "submit"=>"submit",
                "value"=>"Upload Image",
                "name"=>"submit",
                "enctype"=>"multipart/form-data"
            ],
            'inputs'=>[
                "fileToUpload"=>[
                    "type"=>"file",
                    "id"=>"fileToUpload",
                    "name"=>"fileToUpload",
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

    /*
    public function uploadFile()
    {
        $file = $_FILES['fileToUpload'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg', 'jpeg', 'png');
        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 500000) {
                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    $fileDestination = 'uploads/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    $this->sendUploadedFileToDB(
                        $fileDestination,
                        $fileActualExt,
                        intval($_SESSION['id']),
                        intval($_SESSION['id_site']));
                    FlashMessage::setFlash("success", "File uploaded successfully");
                    $_FILES = [];
                } else {
                    FlashMessage::setFlash("errors", "File is too big");
                }
            } else {
                FlashMessage::setFlash("errors", "There was an error uploading your file");
            }
        }else {
            FlashMessage::setFlash("errors", "You cannot upload files of this type {$fileActualExt}");
        }
    }
    public function sendUploadedFileToDB($filePath, $type, $id_user, $id_site)
    {
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->insert('esgi_document', ['path', 'type', 'id_user', 'id_site'])
            ->getQuery();
        $result = Sql::getInstance()->prepare($query);
        return $result->execute([
            $filePath,
            $type,
            $id_user,
            $id_site,
        ]);
    }
    */


    public function sendUploadedFileToDB($filePath, $type, $id_user, $id_site)
    {
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->insert('esgi_document', ['path', 'type', 'id_user', 'id_site'])
            ->getQuery();
        $result = Sql::getInstance()->prepare($query);

        return $result->execute([
            $filePath,
            $type,
            $id_user,
            $id_site,
        ]);
    }

    


    public function getAllDocumentsForSite($id_site)
    {
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->select('esgi_document', ['*'])
            ->where('id_site', ':id_site')
            ->getQuery();
        $result = Sql::getInstance()->prepare($query);
        $result->execute(["id_site" => $id_site]);
        // return $result->fetchObject(Document::class);
        return $result->fetchAll(\PDO::FETCH_CLASS, Document::class);
    }

    public function getDocumentById($id) 
    {
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->select('esgi_document', ['*'])
            ->where('id', ':id')
            ->getQuery();
        $result = Sql::getInstance()->prepare($query);
        $result->execute(["id" => $id]);
        // return $result->fetchObject(Document::class);
        return $result->fetchObject(Document::class);
    }

    public function getAllMediaByUserId($id_user)
    {
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->select('esgi_document', ['*'])
            ->where('id_user', ':id_user')
            ->getQuery();
        $result = Sql::getInstance()->prepare($query);
        $result->execute(["id_user" => $id_user]);
        return $result->fetchAll(\PDO::FETCH_CLASS, Document::class);
    }

}