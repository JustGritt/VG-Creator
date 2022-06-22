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

class Admin
{

    public function dashboard()
    {
        var_dump($_SESSION);

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
                unset($_SESSION['NOT-SET']);
                header('Refresh: 3; '.DOMAIN.'/dashboard');
                return;
            }
        }

        $user = new UserModel();
        $user->setFirstname($_SESSION['firstname']);

        if(!empty($_POST['submit']))
        {
            if(!empty($_FILES)) {
                Handler::uploadFile();
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
    public function showGallery()
    {
        $user = new UserModel();
        $sql = "SELECT * FROM `esgi_document` WHERE `id_user` = ? ORDER BY `id_document` DESC";
        $request =  Sql::getInstance()->prepare($sql);
        $request->execute(array($_SESSION['id']));
        $images =  $request->fetchAll();

        var_dump($images);
        $view = new View("gallery", "back");
        $view->assign('user', $user);
        $view->assign('images', $images);
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
        if (($_SESSION['VGCREATOR'] == VGCREATORMEMBER) && $_SESSION['id_site'] != 1) {
            $result = $this->getUserOfSite($_SESSION['id_site']);
        }
        $result = 0;
        echo 'Le champ apparait lorsque vous auriez un site enregistré';
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

    public function setClientOfSite()
    {
        $view = new View('settings', 'back');
        $result = $this->getClientsOfSite();
        $view->assign("result", $result);
    }

    public function getClientsOfSite()
    {
        $id_site = $_SESSION['id_site'];

        $sql =
            "SELECT u.firstname, u.lastname , u.email, u.pseudo, rs.name FROM `esgi_user` u
        LEFT JOIN esgi_user_role ur on u.id = ur.id_user
        LEFT JOIN esgi_role_site rs on rs.id_role = ur.id_role_site
        LEFT Join esgi_site s on s.id_site = rs.id_site WHERE s.id_site = ?";

        $request =  Sql::getInstance()->prepare($sql);
        $request->execute(array($id_site));
        return $request->fetchAll();
    }

    public function getUserOfSite($id_site)
    {
        $sql =
            "SELECT * FROM `esgi_user` u
            LEFT JOIN esgi_user_role ur on u.id = ur.id_user
            LEFT JOIN esgi_role_site rs on rs.id_role = ur.id_role_site
            WHERE rs.id_site ='.$id_site.'";

        $result = Sql::getInstance()->query($sql)->fetchAll();
        var_dump($result);
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
