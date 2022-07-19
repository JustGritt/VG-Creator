<?php
namespace App\Controller;
//session_start();

use App\Core\CleanWords;
use App\Core\FlashMessage;
use App\Core\Security;
use App\Core\Sql;
use App\Core\SqlPDO;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Core\Mail;
use App\Model\PasswordRecovery as Recovery; 


class PasswordRecovery {

    public function pwdforget()
    {
        $user = new UserModel();
        $password_recovery = new Recovery();
        
        $view = new View("password_recovery");
        $view->assign("user", $user);
        
        //Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
            $usertoverify = $user->getUserByEmail($_POST['email']);

            if (empty($usertoverify)) {
                FlashMessage::setFlash('errors', 'Utilisateur inexistant');
                return;
            }elseif (empty($usertoverify['status'])) {
                FlashMessage::setFlash('errors', 'Vous devez d\'abord confirmé votre email');
                return;
            }

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

            $template_file = "/var/www/html/Templates/password_recovery_email.php";
            if (file_exists($template_file))
                $body = file_get_contents($template_file);

            //Send a special link with a expiry
            $toanchor = DOMAIN . '/reset-new-password?selector='.$selector.'&token='.$recovery_token;

            $template_var = array(
                "{{product_url}}" => DOMAIN."",
                "{{product_name}}" => "VG-CREATOR",
                "{{name}}" => $user->getFirstname(),
                "{{action_url}}" => $toanchor,
                "{{support_email}}" => "contact@vgcreator.fr",
                "{{company_name}}" => "VG-CREATOR",
            );

            //swapping the variable into the templates
            foreach(array_keys($template_var) as $key){
                if (strlen($key) > 2 && trim($key) != "") {
                    $body = str_replace($key, $template_var[$key], $body);
                }
            }

            $mail = new Mail();
            $subject = "Mot de passe oublié ?";
            $mail->sendMail($email , $body, $subject);
            unset($_SESSION['csrf_token']);
        }
    }
}