<?php
namespace App\Controller;

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;


class Comment {

    public function comment() {
        $view = new View("comment" , 'comment');
        $user = new User();
        $view->assign("user", $user);
    }



}
