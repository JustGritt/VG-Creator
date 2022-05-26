<?php

namespace App\Controller;
session_start();

use App\Core\CleanWords;
use App\Core\Sql; 
//use App\Core\SqlPDO;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Core\Mail;
use App\Core\MySqlBuilder;
use App\Core\QueryBuilder;

class Admin 
{   
    protected $pdo;
    public function dashboard()
    {       
        if(empty($_SESSION['id']) && empty($_SESSION['token'])){
            header("Location: ".DOMAIN."/login");   
        }
        
        echo "Ceci est un beau dashboard";
        $user = new UserModel();
        $user->setFirstname($_SESSION['firstname']);
        
        $user->setIdRole($_SESSION['id_role']);
        $user->getUserById($_SESSION['id']);

        $view = new View("dashboard", "back");
        $view->assign("user", $user);
        
        /*
        //var_dump($_POST);
        if(!empty($_POST))
        {
            //var_dump($_POST);
            $user->logout();
        } 
        */
        $sql = Sql::getInstance()->prepare("SELECT * FROM esgi_user");
        $sql->execute();
        $result = $sql->fetchAll();
        
        $request = new MySqlBuilder();
        $sql2 = $request
            ->select('esgi_user', ['id', 'firstname', 'lastname', 'email', 'status', 'id_role'])
            ->where('id', $_SESSION['id'])
            ->limit(0, 1)
            ->getQuery();
        
        //var_dump(Sql::getInstance()->query($sql)->fetchALL(\PDO::FETCH_CLASS, 'App\Model\User'));
        
        $poo = (new MySqlBuilder())
            ->select('esgi_user', ['*'] )
            ->where('id', $_SESSION['id'])
            ->limit(0, 1)
            ->getQuery();
        $result2 = Sql::getInstance()
            ->query($poo)
            ->fetchALL(\PDO::FETCH_CLASS, 'App\Model\User');

        //var_dump($result2);
        
       
        $class = BUILDER;
        $queryBuilder = new $class();
        
        $lol = $this->test($queryBuilder, $_SESSION['id']);
        $kok = Sql::getInstance()->query($lol)->fetchAll();
        //var_dump($kok);

        //var_dump( $this->sendUploadedFileToDB($queryBuilder, $fileName, $id_user, $id_site));
        
        //var_dump($_POST);
        if(!empty($_POST['submit']))
        {
            if(!empty($_FILES)) {
                $this->uploadFile();
                unset($_POST['submit']);
            }
        }
        
        /*
        $query = $queryBuilder
            ->insert('esgi_file', '',[$fileName, $id_user, $id_site])
            ->getQuery();

        var_dump($query)
        */
        $view->assign("result", $result);
        

        unset($_FILES['file']);
        unset($_FILES['fileToUpload']);
        
        
        $view2 = new View("product", "back");
       
    
        
    }


    public function test(QueryBuilder $queryBuilder , $id){
        $query = $queryBuilder
            ->select('esgi_user', ['*'])
            ->where('id', $id)
            ->limit(0, 1)
            ->getQuery();
       
        return $query;
    }

    public function sendUploadedFileToDB(QueryBuilder $queryBuilder, $fileName, $id_user, $id_site)
    {
        $query = $queryBuilder
            ->insert('esgi_file', ['name', 'id_user', 'id_site'], [$fileName, $id_user, $id_site])
            ->getQuery();
        return Sql::getInstance()->query($query) ? true : false;
    }

    public function uploadFile() {
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
                    echo "File uploaded successfully";
                    $_FILES = [];
                } else {
                    echo "Your file is too big";
                }
            } else {
                echo "There was an error uploading your file";
            }
        } else {
            echo "You cannot upload files of this type";
        }
    }


    public function coucou(){
        echo "coucou";
    }

    public function client() {
        echo "Ceci";
        $view = new View("front_website", "back");
        $view->assign("user", $user);

    }

}