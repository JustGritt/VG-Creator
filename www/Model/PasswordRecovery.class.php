<?php
namespace App\Model;

use App\Core\Security;
use App\Core\Sql;
use App\Core\SqlPDO;


class PasswordRecovery extends Sql {
   
    protected $id = null;
    protected $email;
    protected $token = null;
    protected $token_expiry = null;
    protected $selector = null;
    protected $pdo = null;
    protected $table;

    public function __construct()
    {
        $this->pdo = Sql::getInstance();
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
                "submit"=>"Valider le mot de passe"
            ],
            'inputs'=>[
                "password"=>[
                    "type"=>"password",
                    "placeholder"=>"Nouveau mot de passe ...",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"pwdForm",
                    "error"=>"Votre mot de passe doit contenir 8 caractères alphanumérique minimum et un caractère spécial",
                    ],
                "passwordConfirm"=>[
                    "type"=>"password",
                    "placeholder"=>"Confirmation du mot de passe",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"pwdConfirmForm",
                    "confirm"=>"password",
                    "error"=>"Votre mot de passe de confirmation ne correspond pas",
                ],
                'csrf_token'=>[
                    "type"=>"hidden",
                    "class"=>"inputForm",
                    "value"=> Security::generateCsfrToken(),
                    "id"=>"csrf_token"
                ]
            ]
        ];
    }

    public function recovery_password($selector, $email, $token, $token_recovery)
    {
        $sql = "INSERT INTO ".$this->table." (selector, email, token, token_expiry) VALUES (?,?,?,?)";
        $insert= $this->pdo->prepare($sql);
        $insert->execute(array($selector, $email, $token, $token_recovery));
        
    }

    public function isExpiryResetToken($selector, $currentDate): bool
    {
        $result = $this->pdo->prepare("SELECT * FROM ".$this->table." WHERE selector = ? AND token_expiry >= ? ");
        $result->execute(array($selector, $currentDate));
        return $result->rowCount() > 0;
    }

    public function getUserBySelector($selector)
    {
        $result = $this->pdo->prepare("SELECT * FROM ".$this->table." WHERE selector = ?");
        $result->execute(array($selector));
        return $result->fetch();
    }

}