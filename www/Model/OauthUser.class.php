<?php
namespace App\Model;

use App\Core\Sql;
use App\Core\SqlPDO;


class OauthUser extends SqlPDO
{
    protected $id = null;
    protected $firstname = null;
    protected $lastname = null;
    protected $email;
    protected $oauth_id = null;
    protected $oauth_provider = null;
    protected $pdo = null;
    protected $table;
 
    public function __construct(){
        
        $this->pdo = SqlPDO::connect();
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));
        
        //parent::__construct();
    }

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
    public function getOauth_id(): string
    {
        return $this->oauth_id;
    }

    /**
     * @param string 
     */
    public function setOauth_id($oauth_id): void
    {
        $this->oauth_id = (trim($oauth_id));
    }

    /**
     * @return string
     */
    public function getOauth_provider(): string
    {
        return $this->oauth_provider;
    }

    /**
     * @param string 
     */
    public function setOauth_provider($oauth_provider): void
    {
        $this->oauth_provider = (trim($oauth_provider));
    }
  
    public function isUserExist($email)
    {
      $sql = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE email = ?");
      $sql->execute(array($email));
      $result = $sql->fetch();
      
      return !!$sql->rowCount();
    }

}