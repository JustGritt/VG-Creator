<?php

namespace App\Core;

use App\Core\Sql;

class Handler
{

    public static function setMemberRole($id_user){
        $request = "INSERT INTO `esgi_user_role` (`id_user`, `id_role_site`) VALUES (?, ?)";
        $sql = Sql::getInstance()->prepare($request);
        $sql->execute(array($id_user, VGCREATORMEMBER));
    }

    public static function setDirectoryForUser($pseudo){
        $pseudo_site = './UserSites/'.$pseudo;
        if (!file_exists($pseudo_site)) {
            mkdir($pseudo_site, 0777, true);
            //create the file
            $file = fopen($pseudo_site.'/conf.inc.php', 'a+');
            fwrite($file, '<?php'."\n");
            fwrite($file, "\n");
            fwrite($file, 'define("AUTHOR", '.$_SESSION['id'].');'."\n");
            fclose($file);
        }
    }

    public static function createUserSite($pseudo_site, $site_name){
        if (!is_dir($site_name)){
            mkdir($pseudo_site, 0777, true);
            copy('./View/client.tpl.php', $pseudo_site);
        }
    }

    public static function uploadFile() {
        $file = $_FILES['fileToUpload'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 500000) {
                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    $fileDestination = 'uploads/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    $ext = pathinfo($fileDestination, PATHINFO_EXTENSION);
                    Handler::setFileTODB($fileNameNew, $_SESSION['id'] , $_SESSION['id_site'], $ext);
                    echo "File uploaded successfully";
                    $_FILES = [];
                } else {
                    echo "Your file is too big";
                }
            } else {
                echo "There was an error uploading your file";
            }
        } else {
            echo "You cannot upload files of this type";
        }
    }

    public static function setFileTODB($fileName, $id_user, $id_site, $ext){
        $request = "INSERT INTO `esgi_document` (`id_user`, `path` ,`id_site`, `type` ) VALUES (?, ?, ?, ?)";
        $sql = Sql::getInstance()->prepare($request);
        $sql->execute(array($id_user, $fileName, $id_site, $ext));
    }

}