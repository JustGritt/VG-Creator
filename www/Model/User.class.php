<?php
namespace App\Model;

use App\Core\Sql;

class User extends Sql
{
    protected $id = null;
    protected $firstname = null;
    protected $lastname = null;
    protected $email;
    protected $password;
    protected $status = 0;
    protected $token = null;
 

    public function __construct()
    {

        parent::__construct();
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId($id): int
    {
        return $this->id = $id;
    }
    
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(?string $firstname): void
    {
        $this->firstname = ucwords(strtolower(trim($firstname)));
    }

    /**
     * @return null|string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param null|string
     */
    public function setLastname(?string $lastname): void
    {
        $this->lastname = strtoupper(trim($lastname));
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return  $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = strtolower(trim($email));
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return null|string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * length : 255
     */
    public function generateToken(): void
    {
        $this->token = substr(bin2hex(random_bytes(128)), 0, 255);
    }

    /*
    public function setTokenRecovery($recovery_token): void
    {
        $this->recovery_token = $recovery_token;
    }

    public function setTokenRecoveryExpiry($recovery_token_expiry): void
    {
        $this->recovery_token_expiry = $recovery_token_expiry;
    }

    public function setSelector($selector): void
    {
        $this->selector = $selector;
    }
    */
    public function getRegisterForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "submit"=>"S'inscrire"
            ],
            'inputs'=>[
                "email"=>[
                    "type"=>"email",
                    "placeholder"=>"Votre email ...",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"emailForm",
                    "error"=>"Email incorrect",
                    "unicity"=>"true",
                    "errorUnicity"=>"Email déjà en bdd",
                ],
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
                "firstname"=>[
                    "type"=>"text",
                    "placeholder"=>"Votre prénom ...",
                    "class"=>"inputForm",
                    "id"=>"firstnameForm",
                    "min"=>2,
                    "max"=>50,
                    "error"=>"Prénom incorrect"
                ],
                "lastname"=>[
                    "type"=>"text",
                    "placeholder"=>"Votre nom ...",
                    "class"=>"inputForm",
                    "id"=>"lastnameForm",
                    "min"=>2,
                    "max"=>100,
                    "error"=>"Nom incorrect"
                ],
            ]
        ];
    }

    public function getLoginForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "submit"=>"Se connecter"
            ],
            'inputs'=>[
                "email"=>[
                    "type"=>"email",
                    "placeholder"=>"Votre email ...",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"emailForm",
                    "error"=>"Email incorrect"
                ],
                "password"=>[
                    "type"=>"password",
                    "placeholder"=>"Votre mot de passe ...",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"pwdForm",
                    "error"=>"Mot de passe incorrect"
                ]
            ]
        ];
    }

    public function getLogoutForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "submit"=>"Deconnexion"
            ],
            'inputs'=>[
                "deco"=>[
                    "type"=>"button",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"deconnexion",
                ]
            ]
        ];
    }

    public function getPasswordResetForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "submit"=>"Se connecter"
            ],
            'inputs'=>[
                "email"=>[
                    "type"=>"email",
                    "placeholder"=>"Votre email ...",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"emailForm",
                    "error"=>"Email incorrect"
                ],
            ]
        ];
    }


    public function setRegisterForm(): bool
    {
        $id = $this->getId();
        $firstname = $this->setFirstname($_POST['firstname']);
        $lastname = $this->setLastname($_POST['lastname']);
        $email = $this->setEmail($_POST['email']);
        $password = $this->setPassword($_POST['password']);
        $status = 0; //0 => non validé & 1 => email vlaidé 
        $token = $this->generateToken();
        
        //$initialPassword = password_verify($_POST['password'], $this->getPassword());
        $verifyPassword = password_verify($_POST['passwordConfirm'], $this->getPassword());
        
        // Check password 
        if(!$verifyPassword) 
        {   
            return false;
        }
        return true;
    }

    public function setLoginForm(): void 
    {
        $email = $this->setEmail($_POST['email']);
        $password = $this->setPassword($_POST['password']);
    }

    

}
