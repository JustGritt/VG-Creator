<?php

namespace App\Core;

abstract class Sql
{
    private $pdo;
    private $table;
    private $recoverytable =  "esgi_password_recovery";
    public function __construct()
    {
        //Se connecter à la bdd
        //il faudra mettre en place le singleton
        try{
            $this->pdo = new \PDO( DBDRIVER.":host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME
                ,DBUSER, DBPWD , [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING]);
        }catch (\Exception $e){
            die("Erreur SQL : ".$e->getMessage());
        }

        //Si l'id n'est pas null alors on fait un update sinon on fait un insert
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));
       

    }

    public function updateStatus($getId){
      $updateStatus = $this->pdo->prepare("UPDATE ".$this->table." SET status = 1 WHERE id = ?");
      $updateStatus->execute(array($getId));
    }
    
    public function confirmUser($getId , $getToken){

      $user = $this->pdo->prepare("SELECT * FROM ".$this->table." WHERE id = ? AND token = ? ");
      $user->execute(array($getId ,$getToken));
      $userexist = $user->fetch();
      //var_dump($userexist["status"]);
      if($user->rowCount() > 0){
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

    public function getIdFromEmail($email){
      //$idd = "SELECT id FROM esgi_user where email = charles@hotmail.fr";
      $id = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE email = ?");
      $id->execute(array($email));
      $result = $id->fetch();
      return $result['id'];
    }

    /*
    public function setId(?int $id): object
    {
        $sql = "SELECT * FROM ".$this->table." WHERE id=".$id;
        $query = $this->pdo->query($sql);
        return $query->fetchObject(get_called_class());
    }
    */
    public function save()
    {
        $columns = get_object_vars($this);
        $columns = array_diff_key($columns, get_class_vars(get_class()));

        if($this->getId() == null){
            $sql = "INSERT INTO ".$this->table." (".implode(",",array_keys($columns)).") 
            VALUES ( :".implode(",:",array_keys($columns)).")";
        }else{
            $update = [];
            foreach ($columns as $column=>$value)
            {
                $update[] = $column."=:".$column;
            }
            $sql = "UPDATE ".$this->table." SET ".implode(",",$update)." WHERE id=".$this->getId() ;

        }

        $queryPrepared = $this->pdo->prepare($sql);
        return $queryPrepared->execute( $columns );

    }
    
    public function getUserByEmail($email)
    {
      $sql = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE email = ?");
      $sql->execute(array($email));
      $result = $sql->fetch();
      return $result;
    }

    public function isUserExist($email)
    {
      $sql = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE email = ?");
      $sql->execute(array($email));
      $result = $sql->fetch();
      if($sql->rowCount() !== 1){
        return false;
      }
      return true;
    }
    
    public function connexion($getEmail , $getPdw){
      $user = $this->pdo->prepare("SELECT * FROM ".$this->table." WHERE email = ?");
      $user->execute(array($getEmail));
      $userexist = $user->fetch();
      if($user->rowCount() !== 1){
        return null;
      }
      return $userexist;
    }

    public function recovery_password($selector, $email, $token, $token_recovery){
      /*
      $pdo = new \PDO( DBDRIVER.":host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME
      ,DBUSER, DBPWD , [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING]);
      */
      $sql = "INSERT INTO ".$this->table." (selector, email, token, token_expiry) VALUES (?,?,?,?)";
      $insert= $this->pdo->prepare($sql);
      $insert->execute(array($selector, $email, $token, $token_recovery));
      
  }

  public function isExpiryResetToken($selector, $currentDate)
  {
      /*
      $pdo = new \PDO( DBDRIVER.":host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME
      ,DBUSER, DBPWD , [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING]);
      */
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
