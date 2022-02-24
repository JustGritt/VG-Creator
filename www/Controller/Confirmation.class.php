<?php

namespace App\Controller;

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Model\PasswordRecovery; 

class Confirmation {

  public function confirmation(){
    $view = new View("confirmation");
    $view->assign("user", 'test');
    $user = new UserModel();

    if(!empty($_GET['id']) && !empty($_GET['token'])){
      $getId = $_GET['id'];
      $getToken = $_GET['token'];
      $user->confirmUser($getId, $getToken);
      echo 'Your account has been validated! ' ."<br>";
      echo 'You will be redirect to the login page in few secondes ' ."<br>";
      header("Refresh: 3; http://localhost/login" );   
    }else {
      echo "une erreur sait produite...";
      sleep(5);
      header("Location: http://localhost/" );   
    } 
     
  }

  public function confirmationPwd(){

    $user = new UserModel();
    $user_recovery = new PasswordRecovery();
    $view = new View("reset-new-password");
    $view->assign("user_recovery", $user_recovery);

    if(!empty($_GET['selector']) && !empty($_GET['token'])){
      //Check if the selector and token match with DB
      $selector = $_GET['selector'];
      $token = $_GET['token'];
      $user_recovery->setToken($token);
      $user_recovery->setSelector($selector);
      $currentDate = date("U");
      $userinfo = $user_recovery->isExpiryResetToken($selector, $currentDate);
      $email = $userinfo['email'];
      $id = $userinfo['id'];

      //var_dump($user_recovery);
      if(!$user_recovery->isExpiryResetToken($selector, $currentDate)){
          echo "Sorry. The link is no longer valid , you will be redirected soon...";
          header("Refresh: 3; http://localhost/forget" ); 
      }
  
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      if(!empty($_POST)){
        //var_dump($_POST);
        //Check if the to password are the same
        $status_valid = 1;
        $user_update = $user->getUserByEmail($email);
        $user->setId($user_update['id']);
        $user->setFirstname($user_update['firstname']);
        $user->setLastname($user_update['lastname']);
        $user->setEmail($email);
        $user->setStatus($status_valid);
        $user->setPassword($_POST['password']);
        $user->generateToken();
        //var_dump($user->save(), $user);
        
        
        if($user->save()){
          var_dump($_POST);
          $user_recovery->setId($id);
          $user_recovery->setEmail($email);
          $user_recovery->setTokenExpiry(0);
          $user_recovery->save();
          echo 'You password has been reset!' ."<br>";
          echo 'You will be redirect to the login page soon..';
          header("Refresh: 3; http://localhost/login" ); 
        }else{
          echo 'OOps something went wrong during the update of your password' ;
          echo 'You will be redirect soon ...';
          header("Refresh: 3; http://localhost/forget" ); 
        }
        
        
      }

      var_dump($currentDate);
    }
    
  }

}
