<?php

namespace App\Controller;

use App\Model\Category as CategoryModel;
use App\Core\CleanWords;
use App\Core\FlashMessage;
use App\Core\Security;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Core\PaginatedQuery;
use App\Model\User as UserModel;
use App\Core\QueryBuilder;
use App\Core\Handler;


class Category
{

    public function show()
    {
        $category = new CategoryModel();
        $categories = $category->getCategoriesFromSite($_SESSION['id_site']);
        $view = new View("category", "back");
        $view->assign("categories", $categories);
    }

    public function createCategory()
    {
        $category = new CategoryModel();
        $view = new View("category", "back");
        $view->assign("category", $category);

        $result = Verificator::checkForm($category->getRegisterForm(), $_POST);
        var_dump($_POST);
        die();

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (!empty($_POST) && Security::checkCsrfToken($_POST['csrf_token'])) {
            $category->setName($_POST['name']);
            $category->setIdSite($_SESSION['id_site']);
            $category->save();
        }

    }


    public function editCategory()
    {
        $category = new CategoryModel();
        $view = new View("category", "back");
        $view->assign("categories", $category);

        $category->setId($_POST['id']);
        $category->setName($_POST['name']);
        $category->setIdSite($_POST['id_site']);
        $category->save();
    }
}