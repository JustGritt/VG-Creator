<?php
namespace App\Model;

use App\Core\Sql;

class PasswordRecovery extends Sql
{
    private $pdo;
    protected $id = null;
    protected $email;
    protected $token = null;
    protected $token_expiry = null;
    protected $selector = null;

    public function __construct()
    {
        parent::__construct();
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

  

}