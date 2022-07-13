<?php

namespace App\Core;


class Verificator
{

    public static function checkForm($config, $data): array
    {
        $flash = new FlashMessage();
        $result = [];
        //Le nb de inputs envoyés ?
        if( count($data) != count($config['inputs'])){
            die("Tentative de hack !!!!");
        }

        foreach ($config['inputs'] as $name=>$input){

            if(!isset($data[$name]) ){
                $result[] =  "Le champs {$name} n'existe pas";

            }

            if(empty($data[$name]) && !empty($input["required"]) ){
                $result[] = "Le champs ".$name." ne peut pas être vide";
            }

            if($input["type"] == "email" && !self::checkEmail($data[$name]) ){
                $result[] = $input["error"];
            }

            if($input["type"] == "password" && empty($input["confirm"]) && !self::checkPassword($data[$name]) ){
                $result[] = $input["error"];
            }

            if(!empty($input["confirm"]) && $data[$name] != $data[$input["confirm"]]){
                $result[] = $input["error"];
            }

        }

        return $result;

    }

    public static function checkEmail($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }


    public static function checkPassword($password): bool
    {
        return strlen($password)>=8
            && preg_match("/[0-9]/", $password, $match)
            && preg_match("/[a-z]/", $password, $match)
            && preg_match("/[A-Z]/", $password, $match)
            ;
    }

    public static function checkPseudo($pseudo): bool
    {
        $pattern = '/^@([A-Za-z-0-9]+)/';
        $pseudotocheck = htmlspecialchars($pseudo);
        return strlen($pseudotocheck)>=4 && preg_match($pattern, $pseudotocheck, $matches) == 1;
    }


}