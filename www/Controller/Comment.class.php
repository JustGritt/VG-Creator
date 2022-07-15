<?php
namespace App\Controller;


use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Model\Comment as CommentModel;
use App\Core\FlashMessage;

class Comment {

    public function showComments() {
        $view = new View("comments_back" , 'back');
        $user = new User();
        $comments = new CommentModel();

        // var_dump($_POST);

        if (isset($_GET['published'])) {
            $view->assign("results", $comments->getAllCommentsPublished($_SESSION['id_site']));
        } else if (isset($_GET['banned'])) {
            $view->assign("results", $comments->getAllCommentsBanned($_SESSION['id_site']));
        } else {
            $view->assign("results", $comments->getAllCommentsDraft($_SESSION['id_site']));
        }

    }

    public function editComments() {
        $view = new View("comments_back" , 'back');
        $user = new User();
                
        if(!empty($_POST)) 
        {
            $comments = new CommentModel();
            $data = json_decode(file_get_contents('php://input'));
            var_dump($data->type == "Confirmer");
            var_dump($data->type == "Bannir");

            if($data->type == "Confirmer") {
                $comments = $comments->getComment($data->id);
                $comments->setStatus(1);
                var_dump($comments->save());
                $comments->save();
            } else if($data->type == "Bannir") {
                $comments = $comments->getComment($data->id);
                $comments->setStatus(2);
                var_dump($comments->save());
                $comments->save();
            } else {
                FlashMessage::setFlash("errors", "Impossible de traiter la demande");
            }
        }

        // $uri = explode("/", $_GET['url']);
        // $endpoint = end($uri);
        // $id = (int)$endpoint;

        // // var_dump($_SERVER['REQUEST_METHOD']);
        // if($_SERVER['REQUEST_METHOD'] == "POST"){

        // }
    }

}
