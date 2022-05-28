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
    protected $pdo = null;

    public function dashboard()
    {
        if (empty($_SESSION['id']) && empty($_SESSION['token'])) {
            header("Location: " . DOMAIN . "/login");
        }

        $user = new UserModel();
        $user->setFirstname($_SESSION['firstname']);

        if(!empty($_POST['submit']))
        {
            if(!empty($_FILES)) {
                $this->uploadFile();
            }
        }

        $explode_url = explode("/", $_SERVER["REQUEST_URI"]);
        $page = end($explode_url);
        switch ($page) {
            case "settings":
                $view = $this->setSettingsView();
                break;
            case "subscribe":
                $view = new View("dashboard", "back");
                break;
            default:
                $view = new View("back_home", "back");
                break;
        }
        $view->assign("user", $user);

    }

    public function setSettingsView(){
        $view = new View('settings', 'back');
        $result = $this->getUserOfSite();
        $view->assign("result", $result);
        return $view;
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
        return (bool)Sql::getInstance()->query($query);
    }

    public function updateUser($colmuns, $values, $builder = BUILDER) {
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->update('esgi_user', $colmuns, $values)
            ->getQuery();
        $result = Sql::getInstance()
             ->query($query);
        return $result;
    }

    public function deleteUserById($id , $builder = BUILDER) {
    
        $queryBuilder = new $builder();
        $sql = $queryBuilder
            ->delete('esgi_user')
            ->where('id', $id)
            ->getQuery();
        $result = Sql::getInstance()
            ->query($sql);
        return $result;    
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


    public function getUserOfSite(){
        $poo = (new MySqlBuilder())
            ->select('esgi_user', ['*'] )
            //->where('id', $_SESSION['id'])  Uncomment this line to get only the user of the current site
            ->limit(0, 10)
            ->getQuery();
        $result = Sql::getInstance()
            ->query($poo)
            //->fetchALL(\PDO::FETCH_CLASS, 'App\Model\User');
            ->fetchAll();
        return $result;
    }

    public function client() {
        $view = new View('front_template', 'front');  
    }

    public function articles($builder = BUILDER){
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->select('esgi_user', ['*'])
            ->limit(0, 10)
            ->getQuery();
        $result = Sql::getInstance()
                ->query($query)
                ->fetchAll();

        $view = new View('succes', 'back');
        $view->assign('result', $result);
    }

    public function SAUV() {
        $request = new MySqlBuilder();
        $sql2 = $request
            ->select('esgi_user', ['id', 'firstname', 'lastname', 'email', 'status', 'id_role'])
            ->where('id', $_SESSION['id'])
            ->limit(0, 1)
            ->getQuery();
        
        //var_dump(Sql::getInstance()->query($sql)->fetchALL(\PDO::FETCH_CLASS, 'App\Model\User'));
        
      
       
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
    }

    public function coucou() {
       var_dump($_POST);
    }
}
