<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Sql;
use App\Core\View;
use App\Helpers\Utils;
use App\Model\Category;
use App\Core\Security;

class Post extends  Controller
{

    public function __construct()
    {
        if (!Security::isLoggedIn()) {
            header("Location: " . DOMAIN . "/login");
        }
        
        if (Security::isMember()) {
            header("Location: " . DOMAIN . "/dashboard");
        }

        $category=  new Category();
        $categories =   $category->getCategoriesBySite($_SESSION['id_site']);
        $this->render("post", "back", ['categories'=>$categories]  );
    }

    public function getAllArticles()
    {
        $this->render("articles", "back"  );
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $query = $queryBuilder
            ->select('esgi_post', ['*'])
            ->where('id_site', $_SESSION['id_site'])
            ->getQuery();

        $query_drafts = $queryBuilder
            ->select('esgi_post', ['*'])
            ->where('status', 0)
            ->where('id_site', $_SESSION['id_site'])
            ->getQuery();

        $query_published = $queryBuilder
            ->select('esgi_post', ['*'])
            ->where('status', 1)
            ->where('id_site', $_SESSION['id_site'])
            ->getQuery();

        $result = Sql::getInstance()
            ->query($query)
            ->fetchAll();

        $result_draft = Sql::getInstance()->query($query_drafts)->fetchAll();
        $result_published = Sql::getInstance()->query($query_published)->fetchAll();

        if (isset($_GET['published'])) {
            $this->view->assign("result", $result_published);
        } else if (isset($_GET['drafts'])) {
            $this->view->assign("result", $result_draft);
        } else {
            $this->view->assign("result", $result);
        }
    }


    public function editShowPost($id_post){
        $this->sendDataPost($id_post);
    }

    public function createPost()
    {
        $this->view->un_assign('id_post');
        $this->sendDataPost();
    }

    /**
     * @param $id_post
     * Delete a post
     */
    public function deletePost(int $id_post): bool
    {
        $post = new \App\Model\Post();
        $post = $post->getOnePost($id_post);
        if (!$post->getIdSite() == $_SESSION['id_site']) {
            return false;
        }
        return $post->delete();
    }


    /**
     * @param array $form
     * @param array $verify_fields
     * @return null|array
     */
    public function verifyForm(array $form, array $verify_fields): ?array
    {
        $result = [];
        foreach ($form as $name => $input) {
            if (isset($verify_fields[$name])) {
                if (isset($verify_fields[$name]['required']) and strlen($input) == 0) {
                    $result[$name] =  "Le champs " . $name . " est vide.";
                } else if (isset($verify_fields[$name]["min_size"])  and strlen($input) < $verify_fields[$name]["min_size"]) {
                    $result[$name] =  "Le champs " . $name . " est inférieur à " . $verify_fields[$name]["min_size"];
                } else if (isset($verify_fields[$name]["max_size"])  and strlen($input) > $verify_fields[$name]["max_size"]) {
                    $result[$name] =  "Le champs " . $name . " est supérieur à " . $verify_fields[$name]["max_size"];
                }
            }
        }
        return $result;
    }


    public function sendDataPost($id_post= null)
    {

        $verify_fields = [
            'title' => [
                "min_size" => 9,
                "max_size" => 30,
                "required" => true,
            ],
            'body' => [
                "min_size" => 100,
                "required" => true,
            ],
            'metadata' => [
                "required" => true,
            ]
        ];
        $errors = $this->verifyForm($_REQUEST, $verify_fields);

        if (isset($errors) and count($errors) > 0) {
            $this->view->assign('errors', $errors);
        }else{
            $post = new \App\Model\Post();
            if(isset($id_post)){
                $post = $post->getOnePost($id_post);
                $this->view->assign('id_post', $id_post);
                $this->view->assign('post', $post);
            }
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                    ['title' => $title,
                        'status' => $status,
                        'category' => $category,
                        'body' => $body,
                        'metadescription' => $metadescription,
                        'metatitle' => $metatitle, ] = $_POST;

                    if(isset($id_post)) $post->setIdPost($post->getId());
                    $post->setTitle($title);
                    $post->setAuthor(isset($id_post) ? $post->getAuthor()->getId() : $_SESSION['id']);
                    $post->setCategory($category);
                    $post->setMetatitle($metatitle);
                    $post->setStatus($status);
                    $post->setBody($body);
                    $post->setIdSite($_SESSION['id_site']);
                    $post->setMetadescription($metadescription);
                    $post->save();
                    // if(!isset($id_post)) Utils::redirect('admin.allPost');
                    Utils::redirect('admin.allPost');
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->view->assign('fields', $_POST);
        }
    }
}
