<?php

namespace App\Core;

abstract class Sql
{
    private $pdo;
    private $table;

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
      $updateStatus = $this->pdo->prepare("UPDATE ".$this->table." SET status = ? WHERE id = ?");
      $updateStatus->execute(array(1, $getId));
    }
    
    public function getUserById($getId , $getToken){

      $user = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE id = ? AND token = ?");
      $user->execute($getidm, $gettoken);
      if($user->rowCount > 0) {
        $userInfo = $user->fetch();
        if($userInfo['status'] == 1){
          $this->updateStatus($getId);
        }else{
          echo 'sql pbm';
          $_SESSION['token'] = $gettoken;
          header('Location: index.php');
        }
      }

    }
    public function getIdFromEmail($email){
      //$idd = "SELECT id FROM esgi_user where email = charles@hotmail.fr";
      $id = $this->pdo->prepare("SELECT * FROM ".$this->table."  WHERE email = ?");
      $id->execute(array($email));
      $result = $id->fetch();
      return $result['id'];
    }

    /**
     * @param int $id
     */
    public function setId(?int $id): object
    {
        $sql = "SELECT * FROM ".$this->table." WHERE id=".$id;
        $query = $this->pdo->query($sql);
        return $query->fetchObject(get_called_class());
    }

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
        $queryPrepared->execute( $columns );

    }

  public function isValidConfirmationEmail(){
    if(isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['token']) && !empty($_GET['id'])){
      
    }else{
      echo 'aucnun utilisateur trouvé' ;
    
    }

  
  }

}
