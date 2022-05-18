<?php
namespace App\Model;

use App\Core\Sql;
use App\Core\SqlPDO;


class User extends SqlPDO {

    protected $id = null;
    protected $firstname = null;
    protected $lastname = null;
    protected $email;
    protected $password;
    protected $status = 0;
    protected $id_role = null;
    protected $token = null;
    protected $pdo = null;

    public function __construct(){
        
        $this->pdo = SqlPDO::connect();
    }
    /**
     * @return null|int
     */
    public function getId(): ?int{
        return $this->id;
    }

    /**
     * @return null|int
     */
    public function setId($id): ?int{
        return $this->id = $id;
    }

    /**
     * @return null|int
     */
    public function getIdRole(): ?int{
        return $this->id_role;
    }

    /**
     * @return null|int
     */
    public function setIdRole($id_role): ?int{
        return $this->id_role = $id_role;
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


    public function getRegisterForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "submit"=>"S'inscrire",
                "id"=>"formulaire"
            ],
            'inputs'=>[
                "firstname"=>[
                    "type"=>"text",
                    "placeholder"=>"Prénom",
                    "class"=>"inputForm tmp",
                    "id"=>"firstnameForm",
                    "min"=>2,
                    "max"=>50,
                    "error"=>"Prénom incorrect"
                ],
                "lastname"=>[
                    "type"=>"text",
                    "placeholder"=>"Nom",
                    "class"=>"inputForm tmp",
                    "id"=>"lastnameForm",
                    "min"=>2,
                    "max"=>100,
                    "error"=>"Nom incorrect"
                ],
                "email"=>[
                    "type"=>"email",
                    "placeholder"=>"Email",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"emailForm",
                    "error"=>"Email incorrect",
                    "unicity"=>"true",
                    "errorUnicity"=>"Email déjà en bdd",
                ],
                "password"=>[
                    "type"=>"password",
                    "placeholder"=>"Mot de passe",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"pwdForm",
                    "error"=>"Votre mot de passe doit faire au min 8 caractères avec majuscule, minuscules et des chiffres",
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
                "action"=>"logout",
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

    public function updateStatus($getId){
        $updateStatus = $this->pdo->prepare("UPDATE ".$this->table." SET status = 1 WHERE id = ?");
        $updateStatus->execute(array($getId));
    }
      
    public function confirmUser($getId , $getToken){
        
        $user = $this->pdo->prepare("SELECT * FROM ".$this->table." WHERE id = ? AND token = ? ");
        $user->execute(array($getId ,$getToken));
        $userexist = $user->fetch();
        
        if ($user->rowCount() > 0) {
            if($userexist["status"] == "0") {
            $this->updateStatus($getId);
            }else{
            echo 'Votre email est deja enregistre'."<br>". '';
            $_SESSION['token'] = $getToken;
            }

        }else{
            echo 'Erreur: utilisateur inconnu en bdd'."<br>". '';
        }
    
    }
  
    public function getIdFromEmail($email) {
        $id = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE email = ?");
        $id->execute(array($email));
        $result = $id->fetch();
        return $result['id'];
    }

    public function getUserByEmail($email) {
        $sql = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE email = ?");
        
        $sql->execute(array($email));
        $result = $sql->fetch();
        return $result;
    }

    public function isUserExist($email) {
        $sql = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE email = ?");
        $sql->execute(array($email));
        $result = $sql->fetch();
        
        return !!$sql->rowCount();
    }
    public function connexion($getEmail , $getPdw) {
        $user = $this->pdo->prepare("SELECT * FROM ".$this->table." WHERE email = ?");
        $user->execute(array($getEmail));
        $userexist = $user->fetch();
        if ($user->rowCount() !== 1) {
            return null;
        }

        return $userexist;
    }


    

}