<?php

namespace App\Controller;

use App\Core\CleanWords;
use App\Core\Security;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Core\Mail;
use App\Core\MySqlBuilder;
use App\Core\QueryBuilder;
use App\Core\Handler;
use Cassandra\Cluster\Builder;


//session_start();
class Admin
{

    public function dashboard()
    {

        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }
        if (isset($_SESSION['NOT-SET'])) {
            $user = new UserModel();
            $user->setFirstname($_SESSION['firstname']);
            $user->setLastname($_SESSION['lastname']);
            $user->setEmail($_SESSION['email']);
            $user->generateToken();
            $user->setStatus(1);
            $user->setOauthId($_SESSION['oauth_id']);
            $user->setOauthProvider($_SESSION['oauth_provider']);
            $_SESSION['VGCREATOR'] = VGCREATORMEMBER;
            $view2 = new View('register-step-2', 'back');
            $view2->assign('user', $user);
            if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
                if(!$user->is_unique_pseudo($_POST['pseudo'])){
                    echo "Ce pseudo est déjà utilisé";
                    header('Refresh: 3; '.DOMAIN.'/dashboard');
                    return;
                }
                $user->setPseudo($_POST['pseudo']);
                $user->save();
                Handler::setMemberRole($user->getIdFromEmail($_SESSION['email']));
                Handler::setDirectoryForUser($_POST['pseudo']);

                $_SESSION['pseudo'] = $_POST['pseudo'];
                $_SESSION['NOT-SET'] = [];
                header("Location: " . DOMAIN . "/dashboard");
                return;
            }
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
                $view->assign('user', $user);
                break;
            default:
                $view = new View("back_home", "back");
                $view->assign('user', $user);
                break;
        }

    }

    public function setOauthUser($user){
        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
            if(!$user->is_unique_pseudo($_POST['pseudo'])){
                echo "Ce pseudo est déjà utilisé";
                header('Refresh: 3; '.DOMAIN.'/dashboard');
                return;
            }
            $user->save();
            Handler::setMemberRole($user->getId());
            header("Location: " . DOMAIN . "/dashboard");
            return;
        }
    }
    public function setSettingsView(){
        $view = new View('settings', 'back');
        $result = $this->getUserOfSite();
        $view->assign("result", $result);
        return $view;
    }

    public function setEditorView()
    {
        $view = new View('editor', 'back');
        $result = $this->getUserOfSite();
        $view->assign("result", $result);
    }

    public function getAllArticles()
    {
        $view = new View('articles', 'back');
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->select('esgi_articles', ['*'])
            ->limit(0, 10)
            ->getQuery();
        $result =Sql::getInstance()
            ->query($query)
            ->fetchAll();
        $view->assign("result", $result);
    }

    public function selectAllUserOfBlog(QueryBuilder $queryBuilder , $id){
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

    public function getClientsOfSite()
    {
        $id_site = $_SESSION['id_site'];
        $view = new View('dashboard', 'back');
        $sql =
            "SELECT u.firstname, u.lastname , u.email, u.pseudo, rs.name FROM `esgi_user` u
        LEFT JOIN esgi_user_role ur on ur.id_user = u.id
        LEFT JOIN esgi_role_site rs on rs.id_role = ur.id_role
        LEFT Join esgi_site s on s.id_site = rs.id_site WHERE s.id_site = '.$id_site.'";

        $result = Sql::getInstance()
            ->query($sql)
            ->fetchAll();
        return $result;
    }

    public function getUserOfSite(){
        $poo = (new MySqlBuilder())
            ->select('esgi_user', ['*'] )
            ->where('id', $_SESSION['id_site'])
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

    public function test(){
        $user = new User();
        $view = new View('test', 'back');
        //$view->assign('user', $user);

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

}
