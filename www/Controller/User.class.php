<?php

namespace App\Controller;

use App\Core\FlashMessage;
use App\Core\Handler;
use App\Core\Oauth\ProviderFactory;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Core\Mail;
use App\Core\Security;
use App\Model\Backlist;
use App\Model\Site;

class User
{
    public function loginFacebook($providers)
    {
        $user = new UserModel();
        $backlist = new Backlist();
        foreach ($providers as $provider) {
            if ($provider->getName() == 'Facebook') {
                try {
                    $token = $provider->getAccessToken();
                    $user_info = $provider->validateToken($token);
                } catch (\Exception $e) {
                    FlashMessage::setFlash('errors', "OOps sorry something went wrong with ".$provider->getName()." Please try again.");
                    unset($_SESSION['id']);
                    unset($_SESSION['code']);
                    unset($_SESSION['email']);
                    header("Refresh: 2; " . DOMAIN . "/login ");
                    exit;
                }
            }
        }
        if (!$user_info['id'] || !isset($user_info['email'])) {
            FlashMessage::setFlash('errors', "OOps sorry something went wrong with ".$provider->getName()." Please try again.");
            unset($_SESSION['id']);
            unset($_SESSION['code']);
            unset($_SESSION['email']);
            header("Refresh: 2; " . DOMAIN . "/login ");
            return;
        }

        $id = $user->getIdFromEmail($user_info['email']);

        //Check if user is backlisted
        $this->checkIfUserIsBaned($id);
        $_SESSION['email'] = $user_info['email'];
        $_SESSION['id'] =  $id;
        $_SESSION['email'] = $user_info['email'];
        $_SESSION['firstname'] = $user_info['username'];

        //Check if user role for URI
        $this->checkIfUserRoleForURI($user, $id);

        if (!$user->getUserByEmail($user_info['email'])) {
            $_SESSION['NOT-SET'] = 'NOT-SET';
            $_SESSION['email'] = $user_info['email'];
            $_SESSION['token'] = $user->generateToken();
            $_SESSION['firstname'] = $user_info['username'];
            $_SESSION['lastname'] = $user_info['lastname'] ?? '';
            $_SESSION['id'] =  hash('sha256', $user_info['id']);
            $_SESSION['oauth_provider'] = 'discord_api';
            $_SESSION['oauth_id'] = $user_info['id'];
            $_SESSION['VGCREATOR'] = VGCREATORMEMBER;
        }

        unset($_SESSION['csrf_token']);
        header("Location: " . DOMAIN . "/dashboard");

    }

    public function loginwithGoogle($providers)
    {

        $user = new UserModel();

        foreach ($providers as $provider) {
            if ($provider->getName() == 'Google') {
                $user_info = $this->getUser_info($provider);
            }
        }

        if (!$user_info['verified_email']) {
            FlashMessage::setFlash('errors', "OOps sorry something went wrong with google. Please try again.");
            unset($_SESSION['id']);
            unset($_SESSION['code']);
            unset($_SESSION['email']);
            header("Refresh: 2; " . DOMAIN . "/login ");
            return;
        }
        $id = $user->getIdFromEmail($user_info['email']);

        //Check if user is backlisted
        $this->checkIfUserIsBaned($id);

        $_SESSION['id'] =  $id;
        $_SESSION['email'] = $user_info['email'];
        $_SESSION['firstname'] = $user_info['given_name'];
        $_SESSION['lastname'] = $user_info['family_name'];

        //Check if user role for URI
        $this->checkIfUserRoleForURI($user, $id);

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

        unset($_SESSION['csrf_token']);
        header("Location: " . DOMAIN . "/dashboard");
    }

    public function loginwithDiscord($providers)
    {
        $user = new UserModel();
        $backlist = new Backlist();
        foreach ($providers as $provider) {
            if ($provider->getName() == 'Discord') {
                $user_info = $this->getUser_info($provider);
            }

        }

        if (!$user_info['verified']) {
            FlashMessage::setFlash('errors', "OOps sorry something went wrong with google. Please try again.");
            unset($_SESSION['id']);
            unset($_SESSION['code']);
            unset($_SESSION['email']);
            header("Refresh: 2; " . DOMAIN . "/login ");
            return;
        }


        $id = $user->getIdFromEmail($user_info['email']);

        //Check if user is backlisted
        $this->checkIfUserIsBaned($id);
        $_SESSION['email'] = $user_info['email'];
        $_SESSION['id'] =  $id;
        $_SESSION['email'] = $user_info['email'];
        $_SESSION['firstname'] = $user_info['username'];


        //Check if user role for URI
        $this->checkIfUserRoleForURI($user, $id);

        if (!$user->getUserByEmail($user_info['email'])) {
            $_SESSION['NOT-SET'] = 'NOT-SET';
            $_SESSION['email'] = $user_info['email'];
            $_SESSION['token'] = $user->generateToken();
            $_SESSION['firstname'] = $user_info['username'];
            $_SESSION['lastname'] = $user_info['lastname'] ?? '';
            $_SESSION['id'] =  hash('sha256', $user_info['id']);
            $_SESSION['oauth_provider'] = 'discord_api';
            $_SESSION['oauth_id'] = $user_info['id'];
            $_SESSION['VGCREATOR'] = VGCREATORMEMBER;
        }

        unset($_SESSION['csrf_token']);
        header("Location: " . DOMAIN . "/dashboard");

    }


    public function login()
    {
        $user = new UserModel();
        $backlist = new Backlist();

        $providers = $this->initialiseProvider();
        $view = new View("login");
        $view->assign("user", $user);
        $view->assign("providers", $providers);


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
                FlashMessage::setFlash('errors', "Utilisateur non retouvé dans la bdd");
                return;

            }elseif (empty($userverify['status'])) {
                FlashMessage::setFlash('errors', "Veuillez confirmer votre email");
                return;
            }

            if (!password_verify($getPwd, $userverify['password'])) {
                FlashMessage::setFlash('errors', "Mot de passe incorrect");
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
                case 'Facebook':
                    $this->loginFacebook($providers);
                    break;
                case 'Google':
                    $this->loginwithGoogle($providers);
                    unset($_SESSION['csrf_token']);
                    break;
                case 'Discord':
                    $this->loginwithDiscord($providers);
                    unset($_SESSION['csrf_token']);
                    break;
                default:
                    header("Location: ".DOMAIN."/login" );
                    break;
                }
        }
        unset($_SESSION['csrf_token']);
    }

    public function register()
    {
        $user = new UserModel();
        $view = new View("register");
        $view->assign("user", $user);

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {

            $result = Verificator::checkForm($user->getRegisterForm(), $_POST);

            if ($user->isUserExist($_POST['email'])) {
                FlashMessage::setFlash('errors', 'Vous avez dejà un compte');
                header("Refresh: 5; ".DOMAIN."/login ");
                return;
            }

            $user->setFirstname(htmlspecialchars($_POST['firstname']));
            $user->setLastname(htmlspecialchars($_POST['lastname']));
            $user->setEmail(htmlspecialchars($_POST['email']));
            $user->setPassword(htmlspecialchars($_POST['password']));
            $pseudotocheck = Verificator::checkPseudo($_POST['pseudo']);
            
            if(!$pseudotocheck) {
                FlashMessage::setFlash('errors', 'Votre pseudo doit commencer par @ et contenir au moins trois caractères alphanumerique.');
                header("Refresh: 3; ".DOMAIN."/register ");
                return;
            } else {
                $user->setPseudo(htmlspecialchars($_POST['pseudo']));
            }
            
            $user->generateToken();
            //$user->setIdRole(1); // 1 = Admin, 2 = User
            $user->setStatus(0);

            $verifyPassword = password_verify($_POST['passwordConfirm'], $user->getPassword());

            if (!$verifyPassword) {
                FlashMessage::setFlash('errors', 'Mot de passe different..');
                header("Refresh: 3; " . DOMAIN . "/register ");
                return;
            }

            if (!$user->is_unique_pseudo($_POST['pseudo'])) {
                FlashMessage::setFlash('errors', 'Pseudo deja utilisé');
                header("Refresh: 3; " . DOMAIN . "/register ");
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
            FlashMessage::setFlash('success', 'Merci pour votre inscription, confirmez votre email');
            header("Refresh: 3; " . DOMAIN . "/");
        }
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
            header("HTTP/1.0 403 Forbidden");
            FlashMessage::setFlash('errors', "Vous êtes banni de ce site");
            header("Refresh: 1; " . DOMAIN . "/");
            die();
        }
    }

    public function initialiseProvider(){
        $config_file = "config.json";
        if (!file_exists($config_file)) {
            throw new \RuntimeException("Config file '$config_file' not found");
        }

        $configs = json_decode(file_get_contents($config_file), true);

        $factory = new ProviderFactory();
        $providers = [];
        // Initilisation of providers
        foreach ($configs as $config => $value) {

            $provider = $config;
            $client_id = $value["client_id"];
            $client_secret = $value["client_secret"];
            $redirect_uri = $value["redirect_uri"];
            $providers[] = $factory->create($provider, $client_id, $client_secret, $redirect_uri);

        }

        return $providers;
    }

    public function checkIfUserRoleForURI($user, $id)
    {
        if ($_GET['url'] == 'login') {
            $userRoleForVG = $user->getRoleOfUser($id, VGCREATORID);
            $_SESSION['VGCREATOR'] = ($userRoleForVG['role'] == 'Admin') ? IS_ADMIN : IS_MEMBER;
            $_SESSION['id_site'] = $userRoleForVG['id'];

            $site = new Site();
            $site = $site->getAllSiteByIdUser($id);

            if (count($site) >= 2) {
                $_SESSION['choice'] = 'choice';
            }
        }
    }

    public function getUser_info($provider)
    {
        try {
            $token = $provider->getAccessToken();
            $user_info = $provider->validateToken($token);
        } catch (\Exception $e) {
            FlashMessage::setFlash('errors', "OOps sorry something went wrong with google. Please try again.");
            unset($_SESSION['id']);
            unset($_SESSION['code']);
            unset($_SESSION['email']);
            header("Refresh: 2; " . DOMAIN . "/login ");
            exit;
        }
        return $user_info;
    }

}
