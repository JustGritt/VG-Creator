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
use App\Model\OauthUser;
use App\Core\Facebook;

class User {
    
    public function loginwithfb()
    {
        $user = new UserModel();
        $view = new View("login");
        $view->assign("user", $user);
        $facebooklogin = new Facebook();
        //var_dump($_GET);
        $token =  (string)$_GET['code'];
        $user_info = $facebooklogin->login($token);
        
        if($user_info)
        {
            $oauth_user = new OauthUser();
            $_SESSION['id'] = $user_info['id'];
            $_SESSION['email'] = $user_info['email'];
            $_SESSION['code'] = $token;
            $user_name =  explode(" " , $user_info['name']);
            $_SESSION['lastname'] = $user_name[1];
            $_SESSION['firstname'] = $user_name[0];
            if(!$oauth_user->isUserExist($user_info['email'])){
                $oauth_user->setFirstname($user_name[0]);
                $oauth_user->setLastname($user_name[1]);
                $oauth_user->setEmail($user_info['email']);
                $oauth_user->setOauth_id( $user_info['id']);
                $oauth_user->setOauth_provider('facebook_api');
                $oauth_user->save();     
            }
            echo "Bienvenue"; 
            header("Location: http://localhost/dashboard"); 
        
            //echo 'Bienvenue' .$user_info['id'] . ' ' .$user_info['name'] . '' .$user_info['email'];
            // var_dump($_SESSION);
        }else{
            echo "OOps sorry something went wrong with google";
            //unset($_SESSION['id']);
            //unset($_SESSION['code']);
            //unset($_SESSION['email']);
            //var_dump(isset($_SESSION['id']));
            // var_dump($_SESSION);
            header("Refresh: 5; http://localhost/login "); 
        }
        
    }


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
        }else if(!empty($_GET)){
            // var_dump($_POST);
            $oauth_user = new OauthUser();
            $redirect_uri = 'http://localhost/login';
            $data = $this->GetAccessToken(GOOGLE_ID , $redirect_uri , GOOGLE_SECRET , $_GET['code']);
            //var_dump('client_id=' . GOOGLE_ID . '&redirect_uri=' . $redirect_uri . '&client_secret=' . GOOGLE_SECRET . '&code='. $_GET['code'] . '&grant_type=authorization_code');
            $access_token = $data['access_token'];
            $user_info = $this->GetUserProfileInfo($access_token);
            //var_dump($user_info);
            if($user_info['verified_email']){
                $_SESSION['id'] = $user_info['id'];
                $_SESSION['email'] = $user_info['email'];
                $_SESSION['code'] = $access_token;
                $_SESSION['lastname'] = $user_info['family_name'];
                $_SESSION['firstname'] = $user_info['given_name'];
                //var_dump($oauth_user->isUserExist($user_info['email']));
                if(!$oauth_user->isUserExist($user_info['email'])){
                    $oauth_user->setFirstname($user_info['given_name']);
                    $oauth_user->setLastname($user_info['family_name']);
                    $oauth_user->setEmail($user_info['email']);
                    $oauth_user->setOauth_id( $user_info['id']);
                    $oauth_user->setOauth_provider('google_api');
                    $oauth_user->save();     
                }
                //TODO: envoyer en DB l'utilisateur
                echo "Bienvenue"; 
                header("Location: http://localhost/dashboard"); 
            }else{
                echo "OOps sorry something went wrong with google";
                unset($_SESSION['id']);
                unset($_SESSION['code']);
                unset($_SESSION['email']);
                //var_dump(isset($_SESSION['id']));
                // var_dump($_SESSION);
                header("Refresh: 5; http://localhost/login "); 
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
        if(!empty($_SESSION['code']))
        {
            $this->revokeToken($_SESSION['code']);
        }
        unset($_SESSION['session_token']);
        unset($_SESSION['id']);
        unset($_SESSION['email']);
        unset($_SESSION['firstname']);
        session_destroy();
        header("Location: http://localhost/login" );   
    }


    public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {	
        $url = 'https://www.googleapis.com/oauth2/v4/token';			
    
        $curl = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
        $ch = curl_init();		
        curl_setopt($ch, CURLOPT_URL, $url);		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
        curl_setopt($ch, CURLOPT_POST, 1);		
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curl);	
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
        if($http_code != 200){
            //echo 'Error : Failed to receieve access token';
            return false;
        }
          
        
        return $data;
    }

    public function GetUserProfileInfo($access_token) {	
        $url = 'https://www.googleapis.com/oauth2/v2/userinfo?fields=id,given_name,family_name,email,verified_email';	
        //$url2 = 'https://www.googleapis.com/userinfo/v2/me?';

        $ch = curl_init();		
        curl_setopt($ch, CURLOPT_URL, $url);		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
        if($http_code != 200){
            //echo 'Error : Failed to get user information';
            return false;
        }

        return $data;
    }

    public function revokeToken($token){
        $url = 'https://oauth2.googleapis.com/revoke?token='. $token;	
        		
        $ch = curl_init();		
        curl_setopt($ch, CURLOPT_URL, $url);		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
        curl_setopt($ch, CURLOPT_POST, 1);		
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curl);	
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
        if($http_code != 200) 
            echo 'Error : Failed to revoke access token';
        
        return $data;
    }
}



