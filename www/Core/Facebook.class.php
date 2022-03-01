<?php

namespace App\Core;
use App\Model\User as UserModel;

class Facebook
{

    public function login($token)
    {
       // $user = new UserModel();
       // $view = new View("login");
        //$view->assign("user", $user);
        //var_dump($_REQUEST);
        //var_dump($_GET);
        $url = "https://graph.facebook.com/v13.0/oauth/access_token?";
        $client_id = "343703544163557";
        $client_secret = "543b46d85481555a39f341f663706972";
        $redirect_uri = DOMAIN.'/"login-fb';
        //$token =  (string)$_GET['code'];
        
        $user_token = $this->GetAccessTokenfb($client_id , $redirect_uri , $client_secret , $token);
        
        //var_dump($user_info);
        $access_token = (string)$user_token['access_token'];
        
        $user_info = $this->validateFbToken($access_token);
        //var_dump($user_info);
        //echo 'lol';
        //var_dump($this->validateFbToken($token, $access_token));
        //var_dump($user_info);

        if(isset($user_info) && !empty($user_info)){
            return $user_info;
        }
        
        return false;
        
    }

    public function validateFbToken($access_token){
        $url = "https://graph.facebook.com/me?fields=id,name,email&access_token=";
        $curl = '&access_token=' . $access_token;
        $ch = curl_init();		
        curl_setopt($ch, CURLOPT_URL, $url);		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
        curl_setopt($ch, CURLOPT_POST, 1);		
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curl);	
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
        if($http_code != 200){
            echo 'Error : Failed to validate access token';
            return false;
        }
          
        
        return $data;

    }

    public function GetAccessTokenfb($client_id, $redirect_uri, $client_secret, $token) {	
        $url = "https://graph.facebook.com/v13.0/oauth/access_token?";		
    
        $curl = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $token ;
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

}