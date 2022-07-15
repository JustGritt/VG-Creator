<?php
namespace App\Controller;


use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Model\Comment as CommentModel;

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

    public function editComment() {
        $view = new View("comments_back" , 'back');
        $user = new User();
        $comments = new CommentModel();
        
        $uri = explode("/", $_GET['url']);
        $endpoint = end($uri);
        $id = (int)$endpoint;

        // var_dump($_SERVER['REQUEST_METHOD']);
        if($_SERVER['REQUEST_METHOD'] == "POST"){

            // var_dump($_POST);
            // ['title' => $title,
            //     'status' => $status,
            //     'category' => $category,
            //     'body' => $body,
            //     'metadescription' => $metadescription,
            //     'metatitle' => $metatitle, ] = $_POST;

            // if(isset($id_post)) $post->setIdPost($post->getId());
            // $post->setTitle($title);
            // $post->setAuthor(isset($id_post) ? $post->getAuthor()->getId() : $_SESSION['id']);
            // $post->setCategory($category);
            // $post->setMetatitle($metatitle);
            // $post->setStatus($status);
            // $post->setBody($body);
            // $post->setMetadescription($metadescription);
            // $post->save();

            // if(isset($id_post)) Utils::redirect('admin.allPost');
        }
    }

}
