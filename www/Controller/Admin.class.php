<?php

namespace App\Controller;

use App\Core\CleanWords;
use App\Core\FlashMessage;
use App\Core\Security;
use App\Core\Sql;
use App\Core\Uploader;
use App\Core\Verificator;
use App\Core\View;
use App\Core\PaginatedQuery;
use App\Model\Site;
use App\Model\User as UserModel;
use App\Core\Mail;
use App\Core\QueryBuilder;
use App\Core\Handler;
use App\Model\Document;
use App\Model\Backlist;
use App\Model\User_role;

class Admin
{

    public function dashboard()
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

        var_dump($_SESSION);
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
            $view2 = new View('register-step-2', 'blank');
            $view2->assign('user', $user);
            if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
                unset($_SESSION['csrf_token']);
                $pseudotocheck = Verificator::checkPseudo($_POST['pseudo']);
            
                if(!$pseudotocheck) {
                    FlashMessage::setFlash('errors', 'Pseudo invalide');
                    header("Refresh: 3; ".DOMAIN."/register ");
                    return;
                } else {
                    $user->setPseudo(htmlspecialchars($_POST['pseudo']));
                }

                if (!$user->is_unique_pseudo($_POST['pseudo'])) {
                    echo "Ce pseudo est déjà utilisé";
                    header('Refresh: 3; ' . DOMAIN . '/dashboard');
                    return;
                }
                $user->save();
                Handler::setMemberRole($user->getIdFromEmail($_SESSION['email']));

                $_SESSION['id'] = $user->getId();
                $_SESSION['pseudo'] = $_POST['pseudo'];
                unset($_SESSION['NOT-SET']);
                header('Refresh: 3; ' . DOMAIN . '/dashboard');
                return;
            }
            return;
        }

        $site = new Site();
        $site = $site->getAllSiteByIdUser($_SESSION['id']);
        if(isset($_SESSION['choice'])){

            $view2 = new View('login-step-2', 'back');
            $view2->assign('site', $site);
            
            if(!empty($_POST)) {

                var_dump($_POST['site']);
                if($_POST['site'] == 'vg-creator')  {
                    unset($_SESSION['choice']);
                    header('Location: ' . DOMAIN . '/dashboard');
                    return;
                }

                $_SESSION['id_site'] = $_POST['id_site'];
                $_SESSION['role'] = $_POST['role'];
                $_SESSION[strtoupper($_POST['site'])] = $_POST['role'];

                unset($_SESSION['csrf_token']);
                unset($_SESSION['choice']);
                header('Location: ' . DOMAIN . '/dashboard');
                return;
            }
            return;
        }
    
        $user = new UserModel();
        $user->setFirstname($_SESSION['firstname']);
        $view = new View("back_home", "back");
        $view->assign('user', $user);

    }

    public function chooseMySite(){
        var_dump($_SESSION);
        $site = new Site();
        $pagination = new PaginatedQuery(
            $site->getQueryAllsiteByIdUser($_SESSION['id']),
            $site->getCountAllSiteByIdUser($_SESSION['id']),
            10);

        $result = $pagination->getItems();

        $view = new View('login-step-2', 'back');

        $view->assign('site', $result);
        $view->assign('previous', $pagination->previousLink('sites'));
        $view->assign('next', $pagination->nextLink('sites'));

        if(!empty($_POST )) {

            $_SESSION['id_site'] = $_POST['id_site'];
            $_SESSION['role'] = $_POST['role'];
            $_SESSION[strtoupper($_POST['site'])] = $_POST['role'];

            header('Location: ' . DOMAIN . '/dashboard');
            return;
        }
    }

    public function setClientsView(){
        var_dump($_SESSION);

        $view = new View('clients', 'back');

        if (isset($_SESSION['VGCREATOR']) && (($_SESSION['VGCREATOR'] == VGCREATORMEMBER && $_SESSION['id_site'] == '1'))) {
            FlashMessage::setFlash("errors", "Le champ apparait lorsque vous auriez un site enregistré");
            exit();
        }
        $user = new UserModel();
        $backlist = new Backlist();
        $backlist = $backlist->getBackListForSite($_SESSION['id_site']);

        $count = "SELECT COUNT(*)
            FROM `esgi_user` u
            LEFT JOIN esgi_user_role ur on u.id = ur.id_user
            LEFT JOIN esgi_role_site rs on rs.id = ur.id_role_site
            WHERE rs.id_site ={$_SESSION['id_site']}";
        $sql =
            "SELECT u.id, u.firstname, u.lastname, u.email, u.status, u.pseudo, rs.name 
            FROM `esgi_user` u
            LEFT JOIN esgi_user_role ur on u.id = ur.id_user
            LEFT JOIN esgi_role_site rs on rs.id = ur.id_role_site
            WHERE rs.id_site ={$_SESSION['id_site']}";

        $paginatedQuery = new PaginatedQuery($sql, $count, 4);
        $result = $paginatedQuery->getItems();
        $view->assign("result", $result);
        $view->assign('user', $user);
        $view->assign('backlist', $backlist);
        $view->assign('previous', $paginatedQuery->previousLink('clients'));
        $view->assign('next', $paginatedQuery->nextLink('clients'));

        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token_update'])) {
            unset($_SESSION['csrf_token']);
            $this->updateUser();
        }

        return $view;
    }

    public function updateUser(){
        var_dump($_SESSION);
        if (!Security::isVGdmin() && !Security::isAdmin()) {
            FlashMessage::setFlash("errors", "Vous n'avez pas les droits pour effectuer cette action");
            exit();
        }

        if (Security::isVGdmin() || Security::isAdmin()) {
            unset($_SESSION['csrf_token']);
            $backlist = new Backlist();
            $user = new UserModel();

            $user = $user->getUserById($_POST['id']);

            //Check if the user is already in the backlist and Update the user if he is
            if ($backlist->isUserBacklisted($user->getId()) && $_POST['ban'] == 'Active') {
                FlashMessage::setFlash("errors", "Utilisateur déjà banni");
                exit();
            }
            if (!$backlist->isUserBacklisted($user->getId()) && $_POST['ban'] == 'Active') {
                $backlist->setIdUser($_POST['id']);
                $backlist->setIdSite($_SESSION['id_site']);
                //$backlist->setReason($_POST['reason']);
                $backlist->save();
            }elseif ($backlist->isUserBacklisted($user->getId()) && $_POST['ban'] == 'Inactive') {
                $backlist->deleteUserFromBackList($_SESSION['id_site'], $user->getId());
            }

            $user_role = new User_role();
            $role_post = ucfirst(htmlspecialchars($_POST['roles']));
            //Change the role for the user of the site
            $roles_available = $user_role->getAvailableRolesForSite($_SESSION['id_site']);
            var_dump($roles_available);

            $selected_role = 0;
            foreach ($roles_available as $role) {
                if ($role['name'] == $role_post) {
                    $selected_role = $role['id'];
                }
            }

            $user_role = $user_role->getAllUserRoleForSite($user->getId());
            $user_role->setIdUser($user->getId());
            $user_role->setIdRoleSite($selected_role);
            $user_role->save();

            //$user->setUserRoleForSite($user->getId(), $role['id']);
        }
        //header('Refresh: 3; '.DOMAIN.'/dashboard/clients');
    }

    public function getUserOfSite($id_site)
    {
        $count = "SELECT COUNT(*)
            FROM `esgi_user` u
            LEFT JOIN esgi_user_role ur on u.id = ur.id_user
            LEFT JOIN esgi_role_site rs on rs.id = ur.id_role_site
            WHERE rs.id_site ='.$id_site.'";
        $sql =
            "SELECT u.id, u.firstname, u.lastname, u.email, u.status, u.pseudo, rs.name 
            FROM `esgi_user` u
            LEFT JOIN esgi_user_role ur on u.id = ur.id_user
            LEFT JOIN esgi_role_site rs on rs.id = ur.id_role_site
            WHERE rs.id_site ='.$id_site.'";

        $paginatedQuery = new PaginatedQuery($sql, $count, 4);
        $result = $paginatedQuery->getItems();
        //$request = Sql::getInstance()->prepare($sql);
        //$request->execute(array($id_site));
        //$request->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    private function addUser(){
        var_dump($_SESSION);
        $user = new UserModel();
        $user->setFirstname($_POST['firstname']);
        $user->setLastname($_POST['lastname']);
        $user->setEmail($_POST['email']);
        $user->setPseudo($_POST['pseudo']);
        $user->setPassword($_POST['password']);
        $user->generateToken();
        if (!$user->is_unique_pseudo($_POST['pseudo'])) {
            FlashMessage::setFlash("errors", "Pseudo déjà utilisé");
            return;
        }
        $user->save();

        $id = $user->getIdFromEmail($user->getEmail());
        $toanchor = DOMAIN.'/invitation?id='.$id.'&token='.$user->getToken();

        $template_var = array(
            "{{product_url}}" => "".DOMAIN."/",
            "{{product_name}}" => "VG-CREATOR",
            "{{name}}" => $user->getFirstname(),
            "{{action_url}}" => $toanchor,
            "{{login_url}}" => $toanchor,
            "{{username}}" =>  $user->getEmail(),
            "{{support_email}}" => "contact@vgcreator.fr",
            "{{sender_name}}" => "VG-CREATOR",
            "{{help_url}}" => "https://github.com/popokola/VG-CREATOR-SERVER.git",
            "{{company_name}}" => "VG-CREATOR",
        );

        $template_file = "/var/www/html/Templates/confirmation_email.php";
        if(file_exists($template_file)){
            $body = file_get_contents($template_file);
        }else{
            die('ennable to load the templates');
        }

        //swapping the variable into the templates
        foreach(array_keys($template_var) as $key){
            if (strlen($key) > 2 && trim($key) != "") {
                $body = str_replace($key, $template_var[$key], $body);
            }
        }

        $mail = new Mail();
        $subject = "Veuillez confirmée votre email";
        $mail->sendMail($user->getEmail() , $body, $subject);

        //Handler::setRoleForUser($user->getId(), $_SESSION['id_site'],  $_POST['role']);
        FlashMessage::setFlash("success", "L'utilisateur a bien été ajouté");
        //header('Refresh: 3; '.DOMAIN.'/dashboard');
    }

    public function setUploadMediaView()
    {
        $user = new UserModel();
        $user->setFirstname($_SESSION['firstname']);

        $query = "SELECT * FROM esgi_document WHERE id_site = {$_SESSION['id_site']}";
        $count = "SELECT COUNT(1) FROM esgi_document WHERE id_site = {$_SESSION['id_site']}";
        $pagination = new PaginatedQuery($query, $count,2, Document::class);

        $pagination->getItems();
        //$document = new Document();
        $view = new View("media", "back");
        $view->assign('user', $user);
        //$view->assign('document', $document);
        $view->assign('documents',  $pagination->getItems());
        $view->assign('previous', $pagination->previousLink('media'));
        $view->assign('next', $pagination->nextLink('media'));

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if(!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
            unset($_SESSION['csrf_token']);
            if (!empty($_FILES)) {
                $media = new Document();
                $upload = new Uploader($_FILES);
                if ($upload->upload()) {
                    unset($_SESSION['csrf_token']);
                    $media->setPath($upload->getFilePath());
                    $media->setType($upload->getFileType());
                    $media->setIdSite($_SESSION['id_site']);
                    $media->setIdUser($_SESSION['id']);
                    $media->save();
                    echo 'OKkkkkk';
                    var_dump($_SESSION);
                    header('Location:' . DOMAIN . $_SERVER['REQUEST_URI']);
                    return;
                }
                $_FILES = [];
                unset($_SESSION['csrf_token']);
                var_dump($_SESSION);
                echo 'LOL';
                header('Location:' . DOMAIN . '/dashboard/media');
                return;
            }
            unset($_SESSION['csrf_token']);
        }
        unset($_SESSION['csrf_token']);
        var_dump($_SESSION);
    }

    public function setSettingsView()
    {
        $view = new View('settings', 'back');
        $user = new UserModel();
        $user = $user->getUserById($_SESSION['id']);
        $view->assign("user", $user);

        if(!empty($_POST)) {
            
            $user->setFirstname(htmlspecialchars($_POST['firstname']));
            $user->setLastname(htmlspecialchars($_POST['lastname']));
            
            $pseudotocheck = Verificator::checkPseudo($_POST['pseudo']);
            if(!$pseudotocheck) return FlashMessage::setFlash('errors', 'Votre pseudo doit commencer par @ et contenir au moins trois caractères alphanumerique.');
            $user->setPseudo(htmlspecialchars($_POST['pseudo']));
            
            if(isset($_POST['pseudo']) && $user->getPseudo() == $_POST['pseudo'] && ($user->is_unique_pseudo($_POST['pseudo']))){
                FlashMessage::setFlash("errors", "Ce pseudo est déjà utilisé");
                header("Refresh: 3; " . DOMAIN . "/dashboard/settings");
                return;
            } 

            $newpw = password_hash(htmlspecialchars($_POST['newpwd']), PASSWORD_DEFAULT);
            $newpwconfirm = $_POST['newpwdconfirm'];

            if(!isset($_POST['oldpwd']) 
            && Verificator::checkPassword($_POST['newpwd']) 
            && Verificator::checkPassword($_POST['newpwdconfirm'])
            && password_verify($newpwconfirm, $newpw)) 
            {
                $user->setPassword($newpw);
            } 
            elseif(isset($_POST['oldpwd'])
            && Verificator::checkPassword($_POST['newpwd']) 
            && Verificator::checkPassword($_POST['newpwdconfirm'])
            && password_verify($_POST['oldpwd'], $user->getPassword()) 
            && password_verify($_POST['newpwdconfirm'], $newpw)) 
            {
                $user->setPassword($_POST['newpwd']);
            } 
            else {
                FlashMessage::setFlash("errors", "Votre mot de passe n'est pas valide");
                header("Refresh: 3; " . DOMAIN . "/dashboard/settings");
                return;
            }

            $user->setEmail($_POST['email']);
            var_dump($user->save());

            $user->save();
            FlashMessage::setFlash("success", "Le mot de passe a bien été modifié");
            
        }

        return $view;
    }

    // public function getAllArticles()
    // {
    //     $view = new View('articles', 'back');
    //     $builder = BUILDER;
    //     $queryBuilder = new $builder();
    //     $query = $queryBuilder
    //         ->select('esgi_post', ['*'])
    //         ->limit(0, 10)
    //         ->getQuery();

    //     $query_drafts = $queryBuilder
    //         ->select('esgi_post', ['*'])
    //         ->where('status', 0)
    //         ->getQuery();

    //     $query_published = $queryBuilder
    //         ->select('esgi_post', ['*'])
    //         ->where('status', 1)
    //         ->getQuery();

    //     $result = Sql::getInstance()
    //         ->query($query)
    //         ->fetchAll();

    //     $result_draft = Sql::getInstance()->query($query_drafts)->fetchAll();
    //     $result_published = Sql::getInstance()->query($query_published)->fetchAll();

    //     if (isset($_GET['published'])) {
    //         $view->assign("result", $result_published);
    //     } else if (isset($_GET['drafts'])) {
    //         $view->assign("result", $result_draft);
    //     } else {
    //         $view->assign("result", $result);
    //     }
    // }

    public function addClient() 
    {
        $user = new UserModel();
        $view = new View('add_clients', 'Templates/back');
        $view->assign("user", $user);


        return $view;
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
        LEFT JOIN esgi_role_site rs on rs.id = ur.id_role_site
        LEFT Join esgi_site s on s.id = rs.id_site WHERE s.id = ?";

        $request =  Sql::getInstance()->prepare($sql);
        $request->execute(array($id_site));
        return $request->fetchAll();
    }

    public function client()
    {
        $view = new View('front_template', 'front');
    }

    public function test()
    {
        $user = new User();
        $view = new View('test', 'back');
        //$view->assign('user', $user);


    }

    public function comment() {
        $view = new View('front_template', 'front');  
    }


//TODO TEST
/*
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
 public function updateUser($colmuns, $values, $builder = BUILDER)
    {
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->update('esgi_user', $colmuns, $values)
            ->getQuery();
        $result = Sql::getInstance()
            ->query($query);
        return $result;
    }

    public function deleteUserById($id, $builder = BUILDER)
    {

        $queryBuilder = new $builder();
        $sql = $queryBuilder
            ->delete('esgi_user')
            ->where('id', $id)
            ->getQuery();
        $result = Sql::getInstance()
            ->query($sql);
        return $result;
    }


    public function selectAllUserOfBlog(QueryBuilder $queryBuilder, $id)
    {
        $query = $queryBuilder
            ->select('esgi_user', ['*'])
            ->where('id', $id)
            ->limit(0, 1)
            ->getQuery();

        return $query;
    }

    public function setOauthUser($user){
        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
            if(!$user->is_unique_pseudo($_POST['pseudo'])){
                FlashMessage::setFlash("errors", "Ce pseudo est déjà utilisé");
                header('Refresh: 3; '.DOMAIN.'/dashboard');
                return;
            }
            $user->save();
            Handler::setMemberRole($user->getId());
            header("Location: " . DOMAIN . "/dashboard");
        }
    }


    public function getAllComments($id_site){
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->select('esgi_comment', ['title', 'body', 'id_user', 'created_at', 'status'])
            ->where('id_site', ':id_site')
            ->limit(0, 10)
            ->getQuery();
        $result = Sql::getInstance()->prepare($query);
        $result->execute(["id_site" => $id_site]);
        return $result->fetch() ?? null;
    }

 public function getAvailableRolesForSite($id_site)
    {
        $sql =
            "SELECT id, name
            FROM esgi_role_site
            WHERE id_site = '".$id_site."'";

        $request = Sql::getInstance()->prepare($sql);
        $request->execute(array($id_site));
        return $request->fetchAll(\PDO::FETCH_ASSOC);
    }

  public function setEditorView()
    {
        $view = new View('editor', 'back');
        // $result = $this->getUserOfSite();
        //  $view->assign("result", $result);
    }

 */
}
