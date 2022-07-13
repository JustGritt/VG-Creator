<?php

namespace App\Controller;

use App\Core\PaginatedQuery;
use App\Core\Routing\Router;
use App\Core\Security;
use App\Core\Uploader;
use App\Core\View;
use App\Model\Document;
use App\Model\User as UserModel;

class Media
{

    public function setuploadmediaview()
    {
        var_dump($_SESSION);
        $user = new UserModel();
        $user->setFirstname($_SESSION['firstname']);
        $documents = new Document();
        //$documents = $document->getPaginatedDocuments($_SESSION['id_site']);

        $query = "SELECT * FROM esgi_document WHERE id_site = {$_SESSION['id_site']}";
        $count = "SELECT COUNT(1) FROM esgi_document WHERE id_site = {$_SESSION['id_site']}";
        $pagination = new PaginatedQuery($query, $count, Document::class, 2);
        $pagination->getItems();
        $router = Router::getInstance();

        //var_dump($router->url('dashboard.media', ['page' => $pagination->previousLink('dashboard/media')]));

        //$documents = $document->getAllDocumentsForSite($_SESSION['id_site']);
        $view = new View("media", "back");
        $view->assign('user', $user);
        $view->assign('documents',  $pagination->getItems());
        $view->assign('previous', $pagination->previousLink('media'));
        $view->assign('next', $pagination->nextLink('media'));

        if(!empty($_POST['submit']) && Security::checkCsrfToken($_POST['csrf_token'])) {
            unset($_POST['csrf_token']);
            if(!empty($_FILES)) {
                $document = new Document();
                $upload = new Uploader($_FILES);
                if ($upload->upload()) {
                    $document->setPath($upload->getFilePath());
                    $document->setType($upload->getFileType());
                    $document->setIdSite($_SESSION['id_site']);
                    $document->setIdUser($_SESSION['id']);
                    $document->save();
                    //$_FILES = [];
                    header('Refresh:  3' . DOMAIN . '/dashboard/media');
                    return;

                }
                $_FILES = [];
                //return;
                 header('Refresh:  3' . DOMAIN . '/dashboard/media');
                //exit();
            }
        }
    }

}