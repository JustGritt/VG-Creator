<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\View;
use App\Model\Category;


class Post extends  Controller
{


    public function __construct()
    {

        $category=  new Category();
        $categories =   $category->getCategories();
        $this->render("post", "back", ['categories'=>$categories]);
       // $post->getOnePost($id_post);
       //  $id = intval($_GET['id']);

    }

    public function showAll()
    {
        $category=  new Category();
        $categories =   $category->getCategories();
        $this->render("articles", "back", []);

    }

    public function showOne($id)
    {

    }

    public function editPost($id_post){
        $post = new \App\Model\Post();
        $post = $post->getOnePost($id_post);

        // $post = new Post();
        if(isset($_POST['title'])) $this->view->assign('fields', $_POST);
        else $this->view->assign('post', $post);

        if (isset($errors) and count($errors) > 0) {
            $this->view->assign('errors', $errors);
        }else{
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                ['title' => $title,
                    'status' => $status,
                    'category' => $category,
                    'body' => $body,
                    'author' => $author,
                    'metadescription' => $metadescription,
                    'metatitle' => $metatitle, ] = $_POST;

                $post->setIdPost($post->getId());
                $post->setTitle($title);
                $post->setAuthor($author);
                $post->setCategory($category);
                $post->setMetatitle($metatitle);
                $post->setStatus($status);
                $post->setBody($body);
                $post->setMetadescription($metadescription);

               var_dump($author);
               var_dump( $post->save());
            }

            //  $post->getOnePost();
            // var_dump($categories);
        }
    }

    public function createPost()
    {
    }

    public function sendPost()
    {
        $verify_fields = [
            'title' => [
                "min_size" => 9,
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
        if(isset($_POST)) $this->view->assign('fields', $_POST);

        // $post = new Post();

        if (isset($errors) and count($errors) > 0) {
            $this->view->assign('errors', $errors);
        }else{
          //  $post->getOnePost();
           // var_dump($categories);
        }
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
                }
            }
        }
        return $result;
    }
}
