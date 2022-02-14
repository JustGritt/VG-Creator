<?php
namespace App\Controller;

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Core\Mail;


class User {

    public function login()
    {
        $view = new View("Login", "back");

        $view->assign("pseudo", "Prof");
        $view->assign("firstname", "Yves");
        $view->assign("lastname", "Skrzypczyk");

    }


    public function register()
    {
        session_start();
        $user = new UserModel();
        $mail = new Mail();

        if( !empty($_POST)){
            
            $result = Verificator::checkForm($user->getRegisterForm(), $_POST);
            $user->setRegisterForm();
            $user->save();
            $id = $user->getIdFromEmail($user->getEmail());
            $_SESSION['id'] = $id;
            $body = 'http://localhost/Confirmation.class.php?id='.$id.'&token='.$user->getToken();
            $subject = "Veuillez confirmee votre email";
            $mail->sendMail('vgcreator1@gmail.com' , $body, $subject);

            print_r($result);

        }

        $view = new View("register");
        $view->assign("user", $user);
    }

    public function logout()
    {
        echo "Se déco";
    }


    public function pwdforget()
    {
        echo "Mot de passe oublié";
    }



}



