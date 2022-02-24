<?php
namespace App\Controller;
session_start();

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Core\Mail;
use App\Model\PasswordRecovery as Recovery; 


class PasswordRecovery {

    public function pwdforget()
    {
        var_dump($_SESSION);
        echo "Mot de passe oublié"."<br>";
        $user = new UserModel();
        $password_recovery = new Recovery();
        $mail = new Mail();
        $view = new View("password_recovery");
        $view->assign("user", $user);
        
        //Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        if(!empty($_POST)){
            $usertoverify = $user->getUserByEmail($_POST['email']);
            if(empty($usertoverify)){
                echo 'Utilisateur inexistant'."<br>";
            }elseif(empty($usertoverify['status'])){
                echo 'Vous devez d\'abord confirmé votre email'."<br>";
            }else{
                var_dump($user->getUserByEmail($_POST['email']));
                //$user = $user->getUserByEmail($_POST['email']);
                $recovery_token = substr(bin2hex(random_bytes(128)), 0, 255);
                $recovery_token_expiry = date("U") + 1800 ;
                $selector = substr(bin2hex(random_bytes(32)), 0, 64);
                $email = $_POST['email']; 
                
                $password_recovery->setEmail($_POST['email']);
                $password_recovery->setToken($recovery_token);
                $password_recovery->setTokenExpiry($recovery_token_expiry);
                $password_recovery->setSelector($selector);
                
                //Insert into db the information 
                $password_recovery->recovery_password($selector, $email, $recovery_token, $recovery_token_expiry);

                //Send a special link with a expiry 
                $toanchor = 'http://localhost/reset-new-password?selector='.$selector.'&token='.$recovery_token;       
                $body =  "<a href=".$toanchor.">Click here</a>";
    
                $subject = "Mot de passe oublié ?";
                $mail->sendMail($email , $body, $subject);
               
     
            }
        }
    }
}