<?php
namespace App\Controller;
session_start();

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Core\Mail;
use App\Model\PasswordRecovery; 

class User {

    public function login()
    {
        $user = new UserModel();
        $view = new View("login");
        $view->assign("user", $user);

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if( !empty($_POST)){
            $result = Verificator::checkForm($user->getLoginForm(), $_POST);
            $getPwd = $_POST['password'];
            $user->setLoginForm();
            $userverify = $user->connexion($user->getEmail(), $getPwd);
            
            //var_dump($_SESSION);
            
            if($userverify == null){
                echo '<p>Utilisateur non retouvé dans la bdd</p>';
                return false;
            }
            if($userverify['status'] == 0){
                echo "Veuillez confirmé votre email";
                return false;
            }elseif(password_verify($getPwd, $userverify['password'])){
                $_SESSION['email'] = $user->getEmail();
                $_SESSION['session_token'] = substr(bin2hex(random_bytes(64)), 0, 128);
                $_SESSION['firstname']  = $userverify['firstname'];
                $_SESSION['id'] = $userverify['id'];
                echo "Bienvenue"; 
                header("Location: http://localhost/dashboard" );   
            }else{
                echo "<strong class='alert'>mot de passe incorrect</strong>";
                return false; 
            }
        }

    }

    public function register()
    {
        $user = new UserModel();
        $mail = new Mail();
        $view = new View("register");
        $view->assign("user", $user);
        //require "Templates/email.php";
        $template_file = "/var/www/html/Templates/confirmation_email.php";
        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if( !empty($_POST)){

            $result = Verificator::checkForm($user->getRegisterForm(), $_POST);
            if($user->setRegisterForm())
            {
                $user->save();
                $id = $user->getIdFromEmail($user->getEmail());
                $_SESSION['id'] = $id;
                //var_dump($_SESSION);
                //var_dump($user->setRegisterForm());
                $toanchor = 'http://localhost/confirmation?id='.$id.'&token='.$user->getToken();
                //$body =  "<a href=".$toanchor.">Click here</a>";
                
                $template_var = array(
                    "{{product_url}}" => "http://localhost/",
                    "{{product_name}}" => "VG-CREATOR",
                    "{{name}}" => $user->getFirstname(),
                    "{{action_url}}" => $toanchor,
                    "{{login_url}}" => "http://localhost/login",
                    "{{username}}" =>  $user->getEmail(),
                    "{{support_email}}" => "contact@vgcreator.fr",
                    "{{sender_name}}" => "VG-CREATOR",
                    "{{help_url}}" => "https://github.com/popokola/VG-CREATOR-SERVER.git",
                    "{{company_name}}" => "VG-CREATOR",
                );

                if(file_exists($template_file)){
                    $body = file_get_contents($template_file);
                }else{
                    die('ennable to load the templates');
                }
                
                //swapping the variable into the templates
                foreach(array_keys($template_var) as $key){
                    if(strlen($key) > 2 && trim($key) != ""){
                        $body = str_replace($key, $template_var[$key], $body);
                        
                    }
                }

                $subject = "Veuillez confirmée votre email";
                $mail->sendMail($_POST['email'] , $body, $subject);
                //$mail->sendMail('charles258@hotmail.fr' , $body, $subject);
                //flush the current session
                echo 'Merci pour votre inscription, confirmez votre email';
                unset($_SESSION['id']);
                session_destroy();
                header("Refresh: 5; http://localhost/ "); 
            }else{
                echo 'Mot de passe different..';
                //header("Location: http://localhost/register" ); 
            }
        }
        
    }

    public function logout()
    {  
        unset($_SESSION['session_token']);
        unset($_SESSION['id']);
        unset($_SESSION['email']);
        unset($_SESSION['firstname']);
        session_destroy();
        header("Location: http://localhost/login" );   
    }



}



