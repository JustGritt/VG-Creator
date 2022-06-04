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
}