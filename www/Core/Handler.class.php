<?php

namespace App\Core;

use App\Core\Sql;

class Handler
{

    public static function setMemberRole($id_user){
        $request = "INSERT INTO `esgi_user_role` (`id_user`, `id_role_site` , `status`) VALUES (?, ?, ?)";
        $sql = Sql::getInstance()->prepare($request);
        $sql->execute(array($id_user, VGCREATORMEMBER, 1));
    }

    public static function getIdRoleSite($id_site, $role){
        $request = "SELECT `id` FROM `esgi_role_site` WHERE `id_site` = ? and name = ?";
        $sql = Sql::getInstance()->prepare($request);
        $sql->execute(array($id_site, $role));
        $result = $sql->fetch();
        return $result['id_role'];
    }

    public static function setRoleForUser($id_user, $id_site, $role){
        $id_role = self::getIdRoleSite($id_site, $role);
        $request = "INSERT INTO `esgi_user_role` (`id`, `id_role_site`) VALUES (?, ?)";
        $sql = Sql::getInstance()->prepare($request);
        $sql->execute(array($id_user, $id_role));
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

    public static function getInt($name, $default = null){
        if (!isset($_GET[$name])) return $default;
        if ($_GET[$name] === '0') return 0;

        $page = $_GET['page'] ?? 1;

        if (!filter_var($page, FILTER_VALIDATE_INT)){
            throw new \Exception('Numero de page invalide');
        }

        return (int)$_GET[$name];
    }

    public static function getPostiveInt($name, $default = null){
        $param = self::getInt($name, $default);
        if ($param !== null && $param < 0){
            throw new \Exception('Le parametre doit être positif');
        }
        return $param;
    }
}