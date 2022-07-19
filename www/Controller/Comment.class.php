<?php
namespace App\Controller;

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Model\Comment as CommentModel;
use App\Core\FlashMessage;
use App\Core\Security;

class Comment {

    public function showComments() 
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }

        if (Security::isMember()) {
            header("Location: " . DOMAIN . "/dashboard");
        }

        $view = new View("comments_back" , 'back');
        $user = new User();
        $comments = new CommentModel();

        if (isset($_GET['published'])) {
            $view->assign("results", $comments->getAllCommentsPublished($_SESSION['id_site']));
        } else if (isset($_GET['banned'])) {
            $view->assign("results", $comments->getAllCommentsBanned($_SESSION['id_site']));
        } else {
            $view->assign("results", $comments->getAllCommentsDraft($_SESSION['id_site']));
        }

    }

    public function editComments() 
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }
        
        if (Security::isMember()) {
            header("Location: " . DOMAIN . "/dashboard");
        }

        $view = new View("comments_back" , 'back');
        $user = new User();
                
        if(!empty($_POST)) 
        {
            $comments = new CommentModel();
            $data = json_decode(file_get_contents('php://input'));

            if($data->type == "Confirmer") {
                $comments = $comments->getComment($data->id);
                $comments->setStatus(1);
                $comments->save();
            } else if($data->type == "Bannir") {
                $comments = $comments->getComment($data->id);
                $comments->setStatus(2);
                $comments->save();
            } else {
                FlashMessage::setFlash("errors", "Impossible de traiter la demande");
            }
        }

    }

}
