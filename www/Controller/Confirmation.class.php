<?php

namespace App\Controller;
//session_start();

use App\Core\FlashMessage;
use App\Core\CleanWords;
use App\Core\Handler;
use App\Core\Security;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Model\PasswordRecovery;
use App\Model\User_role;


class Confirmation {

    public function confirmation() {
        $view = new View("confirmation");
        $user = new UserModel();
        $view->assign("user", $user);

        if (empty($_GET['id']) && empty($_GET['token'])) {
            FlashMessage::setFlash('errors', "Une erreur sait produite...");
            header("Refresh: 3; ".DOMAIN."/" );
        }

        $getId = $_GET['id'];
        $getToken = $_GET['token'];
        $user->confirmUser($getId, $getToken);

        FlashMessage::setFlash('success', "Your account has been validated! You will be redirect to the login page in few secondes..");
        header("Refresh: 3; ".DOMAIN."/login" );

    }

    public function confirmationPwd(){
        $user = new UserModel();
        $user_recovery = new PasswordRecovery();
        $view = new View("reset-new-password");
        $view->assign("user_recovery", $user_recovery);

        if ((isset($_GET['selector']) || isset($_GET['token'])) && (empty($_GET['selector']) || empty($_GET['token']))) {
            FlashMessage::setFlash('errors', "Une erreur sait produite...");
            return;
        }

        $selector = $_GET['selector'];
        $token = $_GET['token'];
        $user_recovery->setToken($token);
        $user_recovery->setSelector($selector);
        $currentDate = date("U");
        $is_expiry_token = $user_recovery->isExpiryResetToken($selector, $currentDate);

        $userinfo = $user_recovery->getUserBySelector($selector);
        $email = $userinfo['email'];
        $id = $userinfo['id'];

        if (!$is_expiry_token) {
            FlashMessage::setFlash('errors', "Sorry. The link is no longer valid , you will be redirected soon...");
            header("Refresh: 3; ".DOMAIN."/forget" );
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {

            //Check if the to password are the same
            $status_valid = 1;
            $user_update = $user->getUserByEmail($email);
            $user->setId($user_update['id']);
            $user->setFirstname($user_update['firstname']);
            $user->setLastname($user_update['lastname']);
            $user->setPseudo($user_update['pseudo']);
            $user->setEmail($email);
            $user->setStatus($status_valid);
            $user->setPassword($_POST['password']);
            
            $verifyPassword = password_verify($_POST['passwordConfirm'], $user->getPassword());
            if(!$verifyPassword) {
                FlashMessage::setFlash('errors', "Les mots de passe ne correspondent pas");
            }
            
            $user->generateToken();

            if (!$user->save()) {
                FlashMessage::setFlash('errors', "Oops something went wrong during the update of your password");
                header("Refresh: 3; ".DOMAIN."/forget" );
            }

            $user_recovery->setId($id);
            $user_recovery->setEmail($email);
            $user_recovery->setTokenExpiry(0);
            $user_recovery->save();

            FlashMessage::setFlash('success', "You password has been reset! You will be redirect to the login page in few secondes..");
            header("Refresh: 3; ".DOMAIN."/login" );

        }
    }

    public function invitation(){
        $user = new UserModel();
        $view = new View("confirmation");
        $view->assign("user", $user);

        
        if (empty($_GET['id']) && empty($_GET['token'])) {
            FlashMessage::setFlash('errors', "Une erreur sait produite...");
            header("Refresh: 3; ".DOMAIN."/" );
            return;
        }
        
        $getId = $_GET['id'];
        $getToken = $_GET['token'];
        $user = $user->getUserById($getId);
        
        if(empty($user)){
            FlashMessage::setFlash('errors', "Une erreur sait produite...");
            header("Refresh: 3; ".DOMAIN."/" );
            return;
        }
        
        $user_role = new User_role();
        //$user_role->confirmInvitation($getId, $getToken);
        if(!$user_role->updateStatus($getId, $getToken)){
            FlashMessage::setFlash('errors', "Une erreur sait produite...");
            header("Refresh: 3; ".DOMAIN."/" );
            return;
        }
        
        FlashMessage::setFlash('success', "Your account has been validated! You will be redirect to the login page in few secondes..");
        header("Refresh: 3; ".DOMAIN."/login" );
    }
}
