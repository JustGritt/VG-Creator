<?php
namespace App\Model;

use App\Core\Sql;
use App\Core\SqlPDO;
use App\Core\MySqlBuilder;
use App\Core\QueryBuilder;
use App\Core\Security;


class User extends Sql{

    protected $id = null;
    protected $firstname = null;
    protected $lastname = null;
    protected $email;
    protected $password;
    protected $status = 0;
    protected $token = null;
    protected $oauth_id = null;
    protected $oauth_provider = null;
    protected $pseudo;
    protected $pdo = null;
    protected $table;

    public function __construct(){
        
        $this->pdo = Sql::getInstance();
        //$this->pdo = SqlPDO::connect();
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));
    }

    /**
     * @param string $id_user
     * @return User
     */
    public function getOneUser(string $id_user): User
    {
        $builder = BUILDER;
        $queryBuilder = new $builder();
        $request = $queryBuilder->select('esgi_user', ['*'])->where('id', $id_user)->getQuery();
        $result = $this->pdo->query($request)->fetchObject();

        $this->setId($result->id);
        $this->setFirstname($result->firstname);
        $this->setLastname($result->lastname);

        return $this;
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
    public function getPassword(): ?string
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

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
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

    public function getOauthId(): ?string
    {
        return $this->oauth_id;
    }

    public function setOauthId($oauth_id)
    {
        $this->oauth_id = $oauth_id;
    }

    public function getOauthProvider(): ?string
    {
        return $this->oauth_provider;
    }

    public function setOauthProvider($oauth_provider)
    {
        $this->oauth_provider = $oauth_provider;
    }

    public function getRegisterForm(): array
    {
        if(isset($_GET['mail'])){
            $email = htmlentities(addslashes($_GET['mail']));
        } else {
            $email = null;
        }
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
                    "error"=>"Prénom incorrect",
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
                    "value"=>$email,
                    "class"=>"inputForm",
                    "id"=>"emailForm",
                    "error"=>"Email incorrect"
                ],
                "pseudo"=>[
                    "type"=>"text",
                    "placeholder"=>"@Pseudo",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"pseudoForm",
                    "error"=>"@Pseudo incorrect",
                    "unicity"=>"true",
                    "errorUnicity"=>"@Pseudo already in use",
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
                'csrf_token'=>[
                    "type"=>"hidden",
                    "class"=>"inputForm",
                    "value"=> Security::generateCsfrToken(),
                    "id"=>"csrf_token"
                ]
            ]
        ];
    }

    public function getRegisterFormStep2(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "submit"=>"S'inscrire",
                "id"=>"formulaire"
            ],
            'inputs'=>[
                "pseudo"=>[
                    "type"=>"text",
                    "placeholder"=>"@Pseudo",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"pseudoForm",
                    "error"=>"@Pseudo incorrect",
                    "unicity"=>"true",
                    "errorUnicity"=>"@Pseudo already in use",
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

    public function getInvitationForm(): array
    {
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "submit"=>"Inviter",
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
                    "error"=>"Prénom incorrect",
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
                    "error"=>"Email incorrect"
                ],
                "role"=>[
                    "type"=>"checkbox",
                    "name"=>"role",
                    "value"=>"admin",
                    "label" => "Admin",
                    "class"=>"inputForm",
                    "id"=>"emailForm",
                    "error"=>"Email incorrect"
                ],
                "pseudo"=>[
                    "type"=>"text",
                    "placeholder"=>"@Pseudo",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"pseudoForm",
                    "error"=>"@Pseudo incorrect",
                    "unicity"=>"true",
                    "errorUnicity"=>"@Pseudo already in use",
                ],
                "password"=>[
                    "type"=>"password",
                    "placeholder"=>"Mot de passe",
                    "required"=>true,
                    "class"=>"inputForm",
                    "id"=>"pwdForm",
                    "error"=>"Votre mot de passe doit faire au min 8 caractères avec majuscule, minuscules et des chiffres",
                ],
                'csrf_token'=>[
                    "type"=>"hidden",
                    "class"=>"inputForm",
                    "value"=> Security::generateCsfrToken(),
                    "id"=>"csrf_token"
                ]
            ],
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
                'csrf_token'=>[
                    "type"=>"hidden",
                    "class"=>"inputForm",
                    "value"=> Security::generateCsfrToken(),
                    "id"=>"csrf_token"
                ]
            ]
        ];
    }

    public function  getUpdateForm(){
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "submit"=>"Update",
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
                'csrf_token'=>[
                    "type"=>"hidden",
                    "class"=>"inputForm",
                    "value"=> Security::generateCsfrToken(),
                    "id"=>"csrf_token"
                ]
            ]
        ];

    }

    public function updateStatus($getId){
        $updateStatus = $this->pdo->prepare("UPDATE ".$this->table." SET status = 1 WHERE id = ?");
        $updateStatus->execute(array($getId));
    }
    
    public function confirmUser($getId , $getToken){
        
        $user = $this->pdo->prepare("SELECT * FROM ".$this->table." WHERE id = ? AND token = ? ");
        if($user->execute(array($getId ,$getToken))) {
            $this->updateStatus($getId);
            return true;
        }
        return false;
    }
    
    public function getIdFromEmail($email) {
        $id = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE email = ?");
        $id->execute(array(addslashes($email)));
        $result = $id->fetch();
        return $result['id'];
    }

    public function getUserByEmail($email) {
        $sql = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE `email` = ?"); 
        $sql->execute(array(addslashes($email)));
        $result = $sql->fetch();
        return $result;
    }

    public function getUserById($id) {
        $sql = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE `id` = ?");
        $sql->execute(array($id));
        //$result = $sql->setFetchMode($this->pdo::FETCH_CLASS, User::class);
        $result = $sql->fetchObject(User::class);
        return $result;
    }

    public function isUserExist($email) {
        $sql = $this->pdo->prepare("SELECT id FROM ".$this->table." WHERE email = ? ");
        $sql->execute(array(addslashes($email)));
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

    public function getUserByPseudo($pseudo) {
        $sql = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE `pseudo` = ?");
        $sql->execute(array(addslashes($pseudo)));
        return $sql->fetchObject(User::class);
    }

    public function is_unique_pseudo($pseudo)
    {
        $sql = $this->pdo->prepare("SELECT id FROM ".$this->table." WHERE pseudo = ? ");
        $sql->execute(array(addslashes($pseudo)));
        return !$sql->rowCount();
    }


    public function getRoleOfUser($id , $id_site = 1) {
        $sql=
        "SELECT urole.id_role_site, s.name, rs.name as role, s.id
        FROM esgi_user_role urole 
        LEFT JOIN esgi_role_site rs ON urole.id_role_site = rs.id 
        LEFT JOIN esgi_site s ON s.id = rs.id_site 
        LEFT JOIN esgi_user u ON urole.id_user = u.id WHERE u.id = ? and s.id = ?";

        $request =  $this->pdo->prepare($sql);
        $request->execute(array($id, $id_site));
        return $request->fetch(\PDO::FETCH_ASSOC);

    }

    public function getCountUser($id_site){
        $sql =
            "SELECT count(1) FROM `esgi_user` u
            LEFT JOIN esgi_user_role ur on u.id = ur.id_user
            LEFT JOIN esgi_role_site rs on rs.id = ur.id_role_site
            WHERE rs.id_site = ?";

        if (isset($_SESSION['VGCREATOR']) && $_SESSION['VGCREATOR'] == 1 && $_SESSION['id_site'] == 1) {
            $request = Sql::getInstance()->prepare($sql);
            $request->execute(array($_SESSION['id_site']));
            $result = $request->fetchAll();
            return $result[0]['count(1)'];
        }
        if (isset($_SESSION['id_site']) && $_SESSION['id_site'] != 1) {
            $request = Sql::getInstance()->prepare($sql);
            $request->execute(array($_SESSION['id_site']));
            $result = $request->fetchAll();
            return $result[0]['count(1)'];
        }

        return 0;
    }

}
