<?php

namespace App\Controller;

use App\Core\CleanWords;
use App\Core\FlashMessage;
use App\Core\Handler;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Core\Mail;
use App\Model\PasswordRecovery;
use App\Model\OauthUser;
use App\Core\Facebook;
use App\Core\Security;
use App\Model\Backlist;
use App\Model\Site;
use App\Model\User_role;

class User
{
    public function loginwithfb()
    {
        $user = new UserModel();
        $view = new View("login");
        $view->assign("user", $user);
        $facebooklogin = new Facebook();
        $token =  (string)$_GET['code'];
        $user_info = $facebooklogin->login($token);

        if (!$user_info) {
            echo "OOps sorry something went wrong with facebook";
            unset($_SESSION['id']);
            unset($_SESSION['code']);
            unset($_SESSION['email']);
            header("Refresh: 5; " . DOMAIN . "/login ");
        }

        $oauth_user = new OauthUser();
        $user_name =  explode(" ", $user_info['name']);

        $_SESSION['id'] = $user_info['id'];
        $_SESSION['email'] = $user_info['email'];
        $_SESSION['code'] = $token;
        $_SESSION['lastname'] = $user_name[1];
        $_SESSION['firstname'] = $user_name[0];
        //$_SESSION['username'] = $user_info['name'];
        $_SESSION['role'] = ($user_info['id_role'] == 1) ? 'admin' : 'user';

        if (!$oauth_user->isUserExist($user_info['email'])) {
            $oauth_user->setFirstname($user_name[0]);
            $oauth_user->setLastname($user_name[1]);
            $oauth_user->setEmail($user_info['email']);
            $oauth_user->setOauth_id($user_info['id']);
            $oauth_user->setOauth_provider('facebook_api');
            $oauth_user->setRole(1);
            $oauth_user->save();
        }
        echo "Bienvenue";
        header("Location: " . DOMAIN . "/dashboard");
    }

    public function login()
    {
        $user = new UserModel();
        $backlist = new Backlist();

        $view = new View("login");
        $view->assign("user", $user);

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
            unset($_SESSION['csrf_token']);


            $getPwd = $_POST['password'];
            $user->setEmail($_POST['email']);
            $user->setPassword($getPwd);
            $userverify = $user->connexion($user->getEmail(), $getPwd);

            //Check if user is backlisted
            $this->checkIfUserIsBaned($userverify['id']);

            if (is_null($userverify)) {
                //echo 'Utilisateur non retouvé dans la bdd';
                FlashMessage::setFlash('errors', "Utilisateur non retouvé dans la bdd");
                //header("Location: ".DOMAIN."/login" );
                return;

            }elseif (empty($userverify['status'])) {
                //echo "Veuillez confirmé votre email";
                FlashMessage::setFlash('errors', "Veuillez confirmer votre email");
                //header("Location: ".DOMAIN."/login" );
                return;
            }

            if (!password_verify($getPwd, $userverify['password'])) {
                //echo "<strong class='alert'>mot de passe incorrect</strong>";
                FlashMessage::setFlash('errors', "Mot de passe incorrect");
                //header("Location: ".DOMAIN."/login" );
                return;
            }
            //Check if user role for URI
            if ($_GET['url'] == 'login') {
                $userRoleForVG = $user->getRoleOfUser($userverify['id'], VGCREATORID);
                $_SESSION['VGCREATOR'] = ($userRoleForVG['role'] == 'Admin') ? IS_ADMIN : IS_MEMBER;
                $_SESSION['id_site'] = $userRoleForVG['id'];

                $site = new Site();
                $site = $site->getAllSiteByIdUser($userverify['id']);
                
                if(count($site) >= 2) {
                    $_SESSION['choice'] = 'choice';
                }
            }

            $_SESSION['email'] = $user->getEmail();
            $_SESSION['token'] = substr(bin2hex(random_bytes(64)), 0, 128);
            $_SESSION['firstname']  = $userverify['firstname'];
            $_SESSION['id'] = $userverify['id'];
            $_SESSION['pseudo'] = $userverify['pseudo'];

            FlashMessage::setFlash('success', "Bienvenue ".$_SESSION['firstname']);
            header("Location: ".DOMAIN."/dashboard" );
        }
        

        //Logins with Oauth
        if (!empty($_GET) && !empty($_GET['state'])) {
            switch ($_GET['state']) {
                case 'VG-CREATOR-FACEBOOK':
                    $this->loginFacebook();
                    break;
                case 'VG-CREATOR-GOOGLE':
                    $this->loginwithGoogle();
                    unset($_SESSION['csrf_token']);
                    break;
                default:
                    header("Location: ".DOMAIN."/login" );
                    break;
                }
        }
        unset($_SESSION['csrf_token']);
    }

    public function loginwithGoogle()
    {

        $user = new UserModel();

        //$view = new View("login");
        //$view->assign("user", $user);

        $oauth_user = new OauthUser();
        $redirect_uri = DOMAIN . "/login";
        $data = $this->GetAccessToken(GOOGLE_ID, $redirect_uri, GOOGLE_SECRET, $_GET['code']);
        //var_dump('client_id=' . GOOGLE_ID . '&redirect_uri=' . $redirect_uri . '&client_secret=' . GOOGLE_SECRET . '&code='. $_GET['code'] . '&grant_type=authorization_code');
        $access_token = $data['access_token'];
        $user_info = $this->GetUserProfileInfo($access_token);;

        if (!$user_info['verified_email']) {
            echo "OOps sorry something went wrong with google";
            unset($_SESSION['id']);
            unset($_SESSION['code']);
            unset($_SESSION['email']);
            //var_dump(isset($_SESSION['id']));
            var_dump($_SESSION);
            header("Refresh: 5; " . DOMAIN . "/login ");
        }
        $id = $user->getIdFromEmail($user_info['email']);

        //Check if user is backlisted
        $this->checkIfUserIsBaned($id);

        $_SESSION['id'] =  $id;
        $_SESSION['email'] = $user_info['email'];
        $_SESSION['firstname'] = $user_info['given_name'];
        $_SESSION['lastname'] = $user_info['family_name'];

        //Check if user role for URI
        if ($_GET['url'] == 'login') {
            $userRoleForVG = $user->getRoleOfUser( $id, VGCREATORID);
            $_SESSION['VGCREATOR'] = ($userRoleForVG['role'] == 'Admin') ? IS_ADMIN : IS_MEMBER;
            $_SESSION['id_site'] = $userRoleForVG['id'];

            $site = new Site();
            $site = $site->getAllSiteByIdUser($id);

            if(count($site) >= 2) {
                $_SESSION['choice'] = 'choice';
            }
        }

        if (!$user->getUserByEmail($user_info['email'])) {
            $_SESSION['NOT-SET'] = 'NOT-SET';
            $_SESSION['email'] = $user_info['email'];
            $_SESSION['firstname'] = $user_info['given_name'];
            $_SESSION['lastname'] = $user_info['family_name'];
            $_SESSION['token'] = $user->generateToken();
            $_SESSION['oauth_provider'] = 'google_api';
            $_SESSION['oauth_id'] = $user_info['id'];
            $_SESSION['id'] =  hash('sha256', $user_info['id']);
            $_SESSION['VGCREATOR'] = VGCREATORMEMBER;
        }

        header("Location: " . DOMAIN . "/dashboard");
    }

    public function register()
    {
        $user = new UserModel();
        $view = new View("register");
        $view->assign("user", $user);
        $errors = [];

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {

            $result = Verificator::checkForm($user->getRegisterForm(), $_POST);

            if ($user->isUserExist($_POST['email'])) {
                FlashMessage::setFlash('errors', 'Vous avez deja un compte');
                header("Refresh: 5; ".DOMAIN."/login ");
                return;
            }

            $user->setFirstname(htmlspecialchars($_POST['firstname']));
            $user->setLastname(htmlspecialchars($_POST['lastname']));
            $user->setEmail(htmlspecialchars($_POST['email']));
            $user->setPassword(htmlspecialchars($_POST['password']));
            $user->setPseudo(htmlspecialchars($_POST['pseudo']));
            $user->generateToken();
            //$user->setIdRole(1); // 1 = Admin, 2 = User
            $user->setStatus(0);

            $verifyPassword = password_verify($_POST['passwordConfirm'], $user->getPassword());

            if (!$verifyPassword) {
                echo 'Mot de passe different..';
                header("Refresh: 5; " . DOMAIN . "/register ");
                return;
            }

            if (!$user->is_unique_pseudo($_POST['pseudo'])) {
                echo 'Pseudo deja utilisé';
                header("Refresh: 5; " . DOMAIN . "/register ");
                return;
            }

            $user->save();
            $id = $user->getIdFromEmail($user->getEmail());
            Handler::setMemberRole($id);
            $_SESSION['id'] = $id;
            $_SESSION['pseudo'] = $_POST['pseudo'];
            $_SESSION['email'] = $_POST['email'];

            $toanchor = DOMAIN . '/confirmation?id=' . $id . '&token=' . $user->getToken();

            $template_var = array(
                "{{product_url}}" => "" . DOMAIN . "/",
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
            if (file_exists($template_file)) {
                $body = file_get_contents($template_file);
            } else {
                die('ennable to load the templates');
            }

            //swapping the variable into the templates
            foreach (array_keys($template_var) as $key) {
                if (strlen($key) > 2 && trim($key) != "") {
                    $body = str_replace($key, $template_var[$key], $body);
                }
            }

            $mail = new Mail();
            $subject = "Veuillez confirmée votre email";
            $mail->sendMail($_POST['email'], $body, $subject);
            echo 'Merci pour votre inscription, confirmez votre email';

            header("Refresh: 5; " . DOMAIN . "/");
        }

        $view->assign("errors", $errors);
    }

    public function logout(): void
    {
        if (!empty($_SESSION['code'])) {
            $this->revokeToken($_SESSION['code']);
        }

        unset($_SESSION['session_token']);
        unset($_SESSION['id']);
        unset($_SESSION['email']);
        unset($_SESSION['firstname']);
        session_destroy();
        header("Location: " . DOMAIN . "/login");
    }

    public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code)
    {
        $url = 'https://www.googleapis.com/oauth2/v4/token';

        $curl = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code=' . $code . '&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curl);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200) {
            //echo 'Error : Failed to receieve access token';
            return false;
        }

        return $data;
    }

    public function GetUserProfileInfo($access_token)
    {
        $url = 'https://www.googleapis.com/oauth2/v2/userinfo?fields=id,given_name,family_name,email,verified_email';
        //$url2 = 'https://www.googleapis.com/userinfo/v2/me?';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200) {
            //echo 'Error : Failed to get user information';
            return false;
        }

        return $data;
    }

    public function revokeToken($token)
    {
        $url = 'https://oauth2.googleapis.com/revoke?token=' . $token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curl);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            echo 'Error : Failed to revoke access token';

        return $data;
    }

    public function checkIfUserIsBaned($id_user)
    {
        //Check if user is backlisted
        $backlist = new Backlist();
        $site = new Site();
        if ($_GET['url'] == 'login') {
            $site->getSiteByName('vg-creator');
        }
        //TODO check the site name via url
        $site->getSiteByName($_GET['url']);
        $is_banned = $backlist->isUserBacklisted($id_user);
        if ($is_banned) {
            //send 404 header
            //new View("404", 'error', 'Errors');
            header("HTTP/1.0 403 Forbidden");
            FlashMessage::setFlash('errors', "Vous êtes banni de ce site");
            header("Refresh: 3; " . DOMAIN . "/");
            die();
        }
    }
}
