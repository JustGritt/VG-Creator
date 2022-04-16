<?php
namespace App\Model;

use App\Core\Sql;
use App\Core\SqlPDO;


class PasswordRecovery extends SqlPDO {
   
    protected $id = null;
    protected $email;
    protected $token = null;
    protected $token_expiry = null;
    protected $selector = null;
    protected $pdo = null;
    protected $table;

    public function __construct()
    {
        $this->pdo = SqlPDO::connect();
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): int
    {
        return $this->id = $id;
    }

    public function setEmail($email)
    {
        $this->email = strtolower(trim($email));
    }

    public function setToken($recovery_token)
    {
        $this->token = $recovery_token;
    }

    public function setTokenExpiry($recovery_token_expiry)
    {
        $this->token_expiry =  $recovery_token_expiry;
    }

    public function setSelector($selector)
    {
        $this->selector = $selector;
    }

    public function getResetForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "submit"=>"S'inscrire"
            ],
            'inputs'=>[
                "password"=>[
                    "type"=>"password",
                    "placeholder"=>"Votre mot de passe ...",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"pwdForm",
                    "error"=>"Votre mot de passe doit faire au min 8 caractères avec majuscule, minuscules et des chiffres",
                    ],
                "passwordConfirm"=>[
                    "type"=>"password",
                    "placeholder"=>"Confirmation ...",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"pwdConfirmForm",
                    "confirm"=>"password",
                    "error"=>"Votre mot de passe de confirmation ne correspond pas",
                ],
            ]
        ];
    }


    public function recovery_password($selector, $email, $token, $token_recovery)
    {
        $sql = "INSERT INTO ".$this->table." (selector, email, token, token_expiry) VALUES (?,?,?,?)";
        $insert= $this->pdo->prepare($sql);
        $insert->execute(array($selector, $email, $token, $token_recovery));
        
    }
  
    public function isExpiryResetToken($selector, $currentDate)
    {
        $result = $this->pdo->prepare("SELECT * FROM ".$this->table." WHERE selector = ? AND token_expiry >= ? ");
        $result->execute(array($selector, $currentDate));
        $userexist = $result->fetch();
        
        if($result->rowCount() > 0){
            return $userexist;
        }else{
            return false;
        }
        
  
    }
  

}