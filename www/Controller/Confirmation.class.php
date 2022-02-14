<?php

namespace App\Controller;

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Core\User;

class Confirmation {

  public function confirmation(){
    $view = new View("confirmation");
    $view->assign("user", 'test');
    var_dump($_GET['id'], $_GET['token']);
    echo "les plus beaux"; 
    /*
    if(!empty($_GET['id']) && !empty($_GET['token'])){
      $getId = $_GET['id'];
      $getToken = $_GET['token'];
      echo 'test= '.$getId.' '.$getToken;
      $this->getUserById($getId, $getToken);
    }else {
      echo "une erreur sait produite...";
    } 
    */
     
  }


}
