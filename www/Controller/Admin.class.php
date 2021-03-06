<?php

namespace App\Controller;

use App\Core\CleanWords;
use App\Core\FlashMessage;
use App\Core\PaginatedQuery;
use App\Core\Route;
use App\Core\Routing\Router;
use App\Core\Security;
use App\Core\Sql;
use App\Core\Uploader;
use App\Core\Verificator;
use App\Core\View;
use App\Core\Mail;
use App\Core\QueryBuilder;
use App\Core\Handler;
use App\Model\Site;
use App\Model\User as UserModel;
use App\Model\Document;
use App\Model\Backlist;
use App\Model\User_role;
use App\Model\Comment as CommentModel;
use App\Model\Post as PostModel;


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
            var_dump($_SESSION);
            var_dump($user);
            $view2 = new View('register-step-2', 'blank');
            $view2->assign('user', $user);
            if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
                unset($_SESSION['csrf_token']);
                $pseudotocheck = Verificator::checkPseudo($_POST['pseudo']);

                if (!$pseudotocheck) {
                    FlashMessage::setFlash('errors', 'Pseudo invalide');
                    header("Refresh: 3; " . DOMAIN . "/register ");
                    return;
                } else {

                    $user->setPseudo(htmlspecialchars($_POST['pseudo']));
                }

                if (!$user->is_unique_pseudo($_POST['pseudo'])) {
                    echo "Ce pseudo est déjà utilisé";
                    header('Refresh: 3; ' . DOMAIN . '/dashboard');
                    return;
                }
                var_dump($user->save());
                Handler::setMemberRole($user->getIdFromEmail($_SESSION['email']));

                $user_info = $user->getUserByPseudo($_POST['pseudo']);
                $_SESSION['id'] =  $user_info->getId();//$user->getId();
                $_SESSION['pseudo'] = $_POST['pseudo'];
                unset($_SESSION['NOT-SET']);
                header("Location: " . DOMAIN . "/dashboard");
                // header('Refresh: 3; ' . DOMAIN . '/dashboard');
                return;
            }
            return;
        }

        $site = new Site();
        $site = $site->getAllSiteByIdUser2($_SESSION['id']);
        if (isset($_SESSION['choice'])) {

            $view2 = new View('login-step-2', 'blank');
            $view2->assign('site', $site);

            if (!empty($_POST)) {

                if ($_POST['site'] == 'vg-creator') {
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
        unset($_SESSION['csrf_token']);
        

    }

    public function getSites()
    {
        var_dump($_SESSION);
    }

    public function banSites()
    {
        var_dump($_SESSION);
    }

    public function chooseMySite()
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

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

        if (!empty($_POST)) {

            $_SESSION['id_site'] = $_POST['id_site'];
            $_SESSION['role'] = $_POST['role'];
            $_SESSION[strtoupper($_POST['site'])] = $_POST['role'];

            header('Location: ' . DOMAIN . '/dashboard');
        }

    }

    public function setClientsView()
    {
        
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

        if (Security::isMember() && !Security::isAdmin()) {
            header("Location: " . DOMAIN . "/dashboard");
        }

        $view = new View('clients', 'back');

        if (isset($_SESSION['VGCREATOR']) && (($_SESSION['VGCREATOR'] == VGCREATORMEMBER && $_SESSION['id_site'] == '1'))) {
            FlashMessage::setFlash("errors", "Le champ apparait lorsque vous auriez un site enregistré");
            exit();
        }
        $user = new UserModel();
        $backlist = new Backlist();
        $backlist = $backlist->getBackListForSite($_SESSION['id_site']);

        $count = "SELECT COUNT(1)
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

        if (!empty($_POST)) {
            unset($_SESSION['csrf_token']);
            if($_POST['roles'] == 'Admin'){
                FlashMessage::setFlash("errors", "Vous ne pouvez pas assigner un rôle admin");
                header('Refresh: 3; ' . DOMAIN . '/dashboard/clients');
                return;
            }

            $this->updateUser();
        }

        return $view;
    }

    public function updateUser()
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

        if (Security::isMember() && !Security::isAdmin()) {
            header("Location: " . DOMAIN . "/dashboard");
        }

        if (!Security::isVGdmin() && !Security::isAdmin()) {
            FlashMessage::setFlash("errors", "Vous n'avez pas les droits pour effectuer cette action");
            exit();
        }

        if (Security::isVGdmin() || Security::isAdmin()) {
            $backlist = new Backlist();
            $user = new UserModel();

            $user = $user->getUserById($_POST['id']);

            //Check if the user is already in the backlist and Update the user if he is
            if ($backlist->isUserBacklisted($user->getId()) && $_POST['ban'] == 'Active') {
                FlashMessage::setFlash("errors", "Utilisateur déjà banni");
                exit();
            }
            if (!$backlist->isUserBacklisted($user->getId()) && $_POST['ban'] == 'Active') {
                if ($user->getEmail() == $_SESSION['email']) {
                    FlashMessage::setFlash("errors", "Vous ne pouvez pas bannir votre propre compte");
                    header('Refresh: 3; ' . DOMAIN . '/dashboard/clients');
                    exit();
                }
                $backlist->setIdUser($_POST['id']);
                $backlist->setIdSite($_SESSION['id_site']);
                //$backlist->setReason($_POST['reason']);
                $backlist->save();
            } elseif ($backlist->isUserBacklisted($user->getId()) && $_POST['ban'] == 'Inactive') {
                $backlist->deleteUserFromBackList($_SESSION['id_site'], $user->getId());
            }

            $user_role = new User_role();
            $role_post = ucfirst(htmlspecialchars($_POST['roles']));
            //Change the role for the user of the site
            $roles_available = $user_role->getAvailableRolesForSite($_SESSION['id_site']);

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
        return $result;
    }

    public function inviteClient()
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

        if (Security::isMember() && !Security::isAdmin()) {
            header("Location: " . DOMAIN . "/dashboard");
        }

        $view = new View("invite_clients", "back");
        $user = new UserModel();
        $view->assign("user", $user);

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
            unset($_SESSION['csrf_token']);

            if (!empty($_POST['email']) && !empty($_POST['pseudo'])) {
                FlashMessage::setFlash("errors", "Veuillez remplir seulement un champ");
                header('Refresh: 3; ' . DOMAIN . '/dashboard/clients');
                return;
            }

            $user_role = new User_role();
            $role_post = ucfirst(htmlspecialchars($_POST['roles']));
            $roles_available = $user_role->getAvailableRolesForSite($_SESSION['id_site']);

            foreach ($roles_available as $role) {
                if ($role['name'] == $role_post) {
                    $role_id = $role['id'];
                }
            }

            if (!isset($role_id)) {
                FlashMessage::setFlash("errors", "Ce rôle n'existe pas.");
                header("Refresh: 3; " . DOMAIN . "/dashboard/clients");
                return;
            }

            $user_info = null;
            if (!empty($_POST['email'])) {
                $user_info = $user->getUserByEmail($_POST['email']);
                if (!$user_info) {
                    FlashMessage::setFlash("errors", "Cet utilisateur n'existe pas.");
                    header("Refresh: 3; " . DOMAIN . "/dashboard/clients");
                    return;
                }
            } else if (!empty($_POST['pseudo'])) {
                if ($user->is_unique_pseudo($_POST['pseudo'])) {
                    FlashMessage::setFlash("errors", "Ce pseudo n'existe pas.");
                    header("Refresh: 3; " . DOMAIN . "/dashboard/clients");
                    return;
                }
                $to_check = $user->getUserByPseudo($_POST['pseudo']);
                $user_info = $user->getUserByEmail($to_check->getEmail());
            }

            if ($user_info['email'] == $_SESSION['email']) {
                FlashMessage::setFlash("errors", "Vous ne pourrez pas vous inviter vous-même.");
                header("Refresh: 3; " . DOMAIN . "/dashboard/clients");
                return;
            }

            $id = $user_info['id'];
            $user_verify = $user->getUserById($id) ?? null;

            if (!empty($_POST['email']) || !empty($_POST['pseudo'])) {
                $id = $user_info['id'];

                $user_role->setIdUser($id);
                $user_role->setIdRoleSite($role_id);
                $user_role->setStatus(0);
                $user_role->generateToken();
                $token = $user_role->getToken();
                $user_role->save();
            }

            $toanchor = DOMAIN . '/invitation?id=' . $user_verify->getId() . '&token=' . $token;

            $site = new Site();
            $site = $site->getSiteById($_SESSION['id_site']);

            $template_var = array(
                "{{name}}" => $user_verify->getFirstname(),
                "{{sender_name}}" => $_SESSION['firstname'],
                "{{sender_site}}" => $site->getName(),
                "{{action_url}}" => $toanchor,
                "{{sender_email}}" => $_SESSION['email'],
                "{{password}}" => $password ?? null,
            );

            $template_file = "/var/www/html/Templates/invitation_email.php";
            if (file_exists($template_file)) {
                $body = file_get_contents($template_file);
            } else {
                FlashMessage::setFlash("errors", "Impossible de trouver le template");
                return;
            }

            //swapping the variable into the templates
            foreach (array_keys($template_var) as $key) {
                if (strlen($key) > 2 && trim($key) != "") {
                    $body = str_replace($key, $template_var[$key], $body);
                }
            }

            $mail = new Mail();
            $subject = "Veuillez valideez votre compte sur " . $site->getName();
            $mail->sendMail($user_verify->getEmail(), $body, $subject);

            FlashMessage::setFlash("success", "Votre client a été ajouté avec succès.");
            header("Refresh: 3; " . DOMAIN . "/dashboard/clients");
        }
    }

    public function deleteMedia($id)
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

        if (Security::isMember() && !Security::isAdmin()) {
            header("Location: " . DOMAIN . "/dashboard");
        }

        parse_str(file_get_contents('php://input'), $_DELETE);

        $document = new Document();
        $document = $document->getDocumentById($id);

        $path = $document->getPath();
        $delete = $_DELETE;

        $src_url = explode("/", $path);
        $src = end($src_url);

        $path_url = explode("/", $path);
        $path = end($path_url);

        if (!($src == $path)) {
            FlashMessage::setFlash("errors", "Impossible de supprimer ce document.");
            return;
        }

        $document->delete();
        if (file_exists("uploads/" . $path)) {
            unlink("uploads/" . $path);
        }

        FlashMessage::setFlash("success", "Le média a été supprimé avec succès.");
        header("Refresh: 3; " . DOMAIN . "/dashboard/media");
        return;
    }

    public function setUploadMediaView()
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

        if (Security::isMember() && !Security::isAdmin()) {
            header("Location: " . DOMAIN . "/dashboard");
        }

        $user = new UserModel();
        $user->setFirstname($_SESSION['firstname']);

        $query = "SELECT * FROM esgi_document WHERE id_site = {$_SESSION['id_site']}";
        $count = "SELECT COUNT(1) FROM esgi_document WHERE id_site = {$_SESSION['id_site']}";
        $pagination = new PaginatedQuery($query, $count, 2, Document::class);

        $pagination->getItems();
        //$document = new Document();
        $view = new View("media", "back");
        $view->assign('user', $user);
        //$view->assign('document', $document);
        $view->assign('documents', $pagination->getItems());
        $view->assign('previous', $pagination->previousLink('media'));
        $view->assign('next', $pagination->nextLink('media'));

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
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
                    header('Location:' . DOMAIN . $_SERVER['REQUEST_URI']);
                    return;
                }
                $_FILES = [];
                unset($_SESSION['csrf_token']);
                header('Location:' . DOMAIN . '/dashboard/media');
                return;
            }
            unset($_SESSION['csrf_token']);
        }
        unset($_SESSION['csrf_token']);
    }

    public function setSettingsView()
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

        $view = new View('settings', 'back');
        $user = new UserModel();
        $user = $user->getUserById($_SESSION['id']);
        $view->assign("user", $user);

        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
            unset($_SESSION['csrf_token']);

            $user->setFirstname(htmlspecialchars($_POST['firstname']));
            $user->setLastname(htmlspecialchars($_POST['lastname']));

            $pseudotocheck = Verificator::checkPseudo($_POST['pseudo']);
            if (!$pseudotocheck) {
                FlashMessage::setFlash('errors', 'Votre pseudo doit commencer par @ et contenir au moins trois caractères alphanumerique.');
                header("Refresh: 3; " . DOMAIN . "/dashboard/settings");
                return;
            }

            if (isset($_POST['pseudo']) && $user->getPseudo() != $_POST['pseudo'] && (!$user->is_unique_pseudo($_POST['pseudo']))) {
                FlashMessage::setFlash("errors", "Ce pseudo est déjà utilisé");
                header("Refresh: 3; " . DOMAIN . "/dashboard/settings");
                return;
            }


            $user->setPseudo(htmlspecialchars($_POST['pseudo']));
            $newpw = htmlspecialchars($_POST['newpwd']);

            if (!isset($_POST['oldpwd']) && !empty($_POST['newpwd']) && !empty($_POST['newpwdconfirm'])) {
                if (Verificator::checkPassword($_POST['newpwd']) && Verificator::checkPassword($_POST['newpwdconfirm'])) {
                    $user->setPassword($newpw);
                    if (!password_verify($_POST['newpwdconfirm'], $user->getPassword())) {
                        FlashMessage::setFlash("errors", "Votre mot de passe ne corresponds pas.");
                        header("Refresh: 3; " . DOMAIN . "/dashboard/settings");
                        return;
                    }
                } elseif (!Verificator::checkPassword($_POST['newpwd']) && !Verificator::checkPassword($_POST['newpwdconfirm'])) {
                    FlashMessage::setFlash("errors", "Votre mot de passe doit contenir au moins 6 caractères, une majuscule, une minuscule et un chiffre.");
                    header("Refresh: 3; " . DOMAIN . "/dashboard/settings");
                    return;
                } else {
                    FlashMessage::setFlash("errors", "Votre mot de passe ne corresponds pas.");
                    header("Refresh: 3; " . DOMAIN . "/dashboard/settings");
                    return;
                }
            }

            if (isset($_POST['oldpwd']) && !empty($_POST['oldpwd']) && !empty($_POST['newpwd']) && !empty($_POST['newpwdconfirm'])) {

                if (Verificator::checkPassword($_POST['newpwd']) && Verificator::checkPassword($_POST['newpwdconfirm'])
                    && password_verify($_POST['oldpwd'], $user->getPassword())) {
                    $user->setPassword($newpw);
                    if (!password_verify($_POST['newpwdconfirm'], $user->getPassword())) {
                        FlashMessage::setFlash("errors", "Votre mot de passe ne corresponds pas");
                        header("Refresh: 3; " . DOMAIN . "/dashboard/settings");
                        return;
                    }
                } elseif (!Verificator::checkPassword($_POST['newpwd']) && !Verificator::checkPassword($_POST['newpwdconfirm'])) {
                    FlashMessage::setFlash("errors", "Votre mot de passe doit contenir au moins 6 caractères, une majuscule, une minuscule et un chiffre.");
                    header("Refresh: 3; " . DOMAIN . "/dashboard/settings");
                    return;
                } elseif (!password_verify($_POST['oldpwd'], $user->getPassword())) {
                    FlashMessage::setFlash("errors", "Mot de passe incorrect.");
                    header("Refresh: 3; " . DOMAIN . "/dashboard/settings");
                    return;
                }
            }

            $user->setEmail($_POST['email']);

            $user->save();
            FlashMessage::setFlash("success", "Votre modifications ont bien été enregistrées.");
            header("Refresh: 3; " . DOMAIN . "/dashboard/settings");

        }
    }

    public function deleteAccount($id)
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

        parse_str(file_get_contents('php://input'), $_DELETE);
        if (!empty($_DELETE) && Security::checkCsrfToken($_DELETE['csrf_token'])) {
            unset($_SESSION['csrf_token']);
            $user = new UserModel();
            $user = $user->getUserById($id);

            $user_role = new User_role();
            $user_roles = $user_role->getAllRolesForUserById($id);
            foreach ($user_roles as $user_role) {
                $user_role->delete();
            }

            $media = new Document();
            $medias = $media->getAllMediaByUserId($id);
            foreach ($medias as $media) {
                $media->delete();
            }

            $comment = new CommentModel();
            $comments = $comment->getAllCommentsByUserId($id);
            foreach ($comments as $comment) {
                $comment->delete();
            }

            $post = new PostModel();
            $posts = $post->getAllPostsByUserId($id);
            foreach ($posts as $post) {
                $post->delete();
            }

            $user->delete();
        }
    }

    public function addClient()
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

        if (Security::isMember() && !Security::isAdmin()) {
            header("Location: " . DOMAIN . "/dashboard");
        }

        $view = new View('add_clients', 'Templates/back');
        $user = new UserModel();
        $view->assign("user", $user);

        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
            unset($_SESSION['csrf_token']);

            $user_role = new User_role();
            $role_post = ucfirst(htmlspecialchars($_POST['roles']));
            $roles_available = $user_role->getAvailableRolesForSite($_SESSION['id_site']);

            foreach ($roles_available as $role) {
                if ($role['name'] == $role_post) {
                    $role_id = $role['id'];
                }
            }

            if (!isset($role_id)) {
                FlashMessage::setFlash("errors", "Ce rôle n'existe pas.");
                header("Refresh: 3; " . DOMAIN . "/dashboard/clients");
                return;
            }

            if($role_post == 'Admin') {
                FlashMessage::setFlash("errors", "Ce rôle n'est pas disponible.");
                header("Refresh: 3; " . DOMAIN . "/dashboard/clients");
                return;
            }

            // check if user already exists
            $user_exists2 = $user->getUserByEmail($_POST['email']);
            if (isset($user_exists2) && !empty($user_exists2)) {
                FlashMessage::setFlash("errors", "Cet utilisateur existe déjà.");
                header("Refresh: 2; " . DOMAIN . "/dashboard/clients");
                return;
            }

            $pseudotocheck = Verificator::checkPseudo($_POST['pseudo']);
            if (!$pseudotocheck) {
                FlashMessage::setFlash('errors', 'Votre pseudo doit commencer par @ et contenir au moins trois caractères alphanumerique.');
                return;
            }
            if (isset($_POST['pseudo']) && (!$user->is_unique_pseudo($_POST['pseudo']))) {
                FlashMessage::setFlash("errors", "Ce pseudo est déjà utilisé.");
                header("Refresh: 3; " . DOMAIN . "/dashboard/clients");
                return;
            }

            $password = $_POST['password'];
            // Add client data
            $user->setFirstname(htmlspecialchars($_POST['firstname']));
            $user->setLastname(htmlspecialchars($_POST['lastname']));
            $user->setEmail(htmlspecialchars($_POST['email']));
            $user->setStatus(0);
            $user->setPseudo(htmlspecialchars($_POST['pseudo']));
            $user->setPassword(htmlspecialchars($_POST['password']));
            $user->generateToken();

            if (!$user->save()) {
                FlashMessage::setFlash("errors", "Une erreur est survenue lors de l'ajout du client.");
                header("Refresh: 3; " . DOMAIN . "/dashboard/clients");
                return;
            }

            $user_info = $user->getUserByPseudo($_POST['pseudo']);
            $user_role->setIdUser($user_info->getId());
            $user_role->setIdRoleSite($role_id);
            $user_role->setStatus(1);
            $user_role->save();
            Handler::setMemberRole($user_info->getId());

            $toanchor = DOMAIN . '/confirmation?id=' . $user_info->getId() . '&token=' . $user->getToken();

            $site = new Site();
            $site = $site->getSiteById($_SESSION['id_site']);

            $template_var = array(
                "{{name}}" => $user->getFirstname(),
                "{{sender_name}}" => $_SESSION['firstname'],
                "{{sender_site}}" => $site->getName(),
                "{{action_url}}" => $toanchor,
                "{{sender_email}}" => $_SESSION['email'],
                "{{password}}" => $password,
            );

            $template_file = "/var/www/html/Templates/invitation_email.php";
            if (file_exists($template_file)) {
                $body = file_get_contents($template_file);
            } else {
                FlashMessage::setFlash("errors", "Impossible de trouver le template");
                return;
            }

            //swapping the variable into the templates
            foreach (array_keys($template_var) as $key) {
                if (strlen($key) > 2 && trim($key) != "") {
                    $body = str_replace($key, $template_var[$key], $body);
                }
            }

            $mail = new Mail();
            $subject = "Veuillez valideez votre compte sur " . $site->getName();
            $mail->sendMail($user->getEmail(), $body, $subject);

            FlashMessage::setFlash("success", "Votre client a été ajouté avec succès.");
            header("Refresh: 3; " . DOMAIN . "/dashboard/clients");

        }
    }

    public function setClientOfSite()
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

        $view = new View('settings', 'back');
        $result = $this->getClientsOfSite();
        $view->assign("result", $result);
    }

    public function getClientsOfSite()
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

        if (Security::isMember() && !Security::isAdmin()) {
            header("Location: " . DOMAIN . "/dashboard");
        }
        
        $id_site = $_SESSION['id_site'];

        $sql = "SELECT u.firstname, u.lastname , u.email, u.pseudo, rs.name 
            FROM `esgi_user` u
            LEFT JOIN esgi_user_role ur on u.id = ur.id_user
            LEFT JOIN esgi_role_site rs on rs.id = ur.id_role_site
            LEFT Join esgi_site s on s.id = rs.id_site 
            WHERE s.id = ?";

        $request = Sql::getInstance()->prepare($sql);
        $request->execute(array($id_site));
        return $request->fetchAll();
    }
}