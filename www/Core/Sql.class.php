<?php

namespace App\Core;

abstract class Sql
{
    protected $pdo;
    protected $table;
    protected $link;
    public static $instance = null;
    public static $_servers = array();

    /** @var string Database name */
    protected $database;


    /*
    protected function __construct()
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
    */

    public static function getInstance()
    {

        if(is_null(self::$instance))
        {
            try{
                self::$instance = new \PDO( DBDRIVER.":host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME
                    ,DBUSER, DBPWD , [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING]);
            }catch (\Exception $e){
                die("Erreur SQL : ".$e->getMessage());
            }
        }
        
        return self::$instance;
    }
    
    /*
    public static function getInstance($master = true)
    {
        static $id = 0;

        if (!self::$_servers) {
          self::$_servers = array(
              array('server' => DBHOST, 'user' => DBUSER, 'password' => DBPWD, 'database' => DBNAME),
          );
        }

        $total_servers = count(self::$_servers);
        if ($master || $total_servers == 1) {
            $id_server = 0;
        } else {
            $id++;
            $id_server = ($total_servers > 2 && ($id % $total_servers) != 0) ? $id % $total_servers : 1;
        }
        $test = require '/var/www/html/Core/SqlPDO.class.php' ;
        if (!isset(self::$instance[$id_server])) {
            $class = self::getClass(); 
            var_dump($class);
            self::$instance[$id_server] = new $test(
            
              self::$_servers[$id_server][DBHOST],
              self::$_servers[$id_server][DBUSER],
              self::$_servers[$id_server][DBPWD],
              self::$_servers[$id_server][DBNAME]
            );
        }

        return self::$instance[$id_server];
    }
    */
    
    /*
    abstract public function connect();
    abstract protected function _query($sql);
    */
    public static function getClass()
    {
        $class = 'MySQL';
        if (PHP_VERSION_ID >= 50200 && extension_loaded('pdo_mysql')) {
            $class = 'MySqlBuilder';
        } elseif (extension_loaded('mysqli')) {
            $class = 'DbMySQLi';
        }

        return $class;
    }

    
    protected function __construct($database, $connect = true)
    {
       
        $this->database = $database;
      if ($connect) {
        $this->connect();
        //$this->getInstance();
      }
    }
    
    public function save()
    {
        $columns = get_object_vars($this);
        $columns = array_diff_key($columns, get_class_vars(get_class()));
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));

        $id = preg_grep('/^id_(.*)/', array_keys($columns))[0] ?? "id";

        if( $this->getId() == null){
            $sql = "INSERT INTO ".$this->table." (".implode(",",array_keys($columns)).") 
            VALUES ( :".implode(",:",array_keys($columns)).")";
        }else{
            $update = [];
            $column_local = [];
            foreach ($columns as $column=>$value)
            {
                if($value != null && $column != $id) {
                    $update[] = "`".$column."`"." = :".$column;
                    $column_local[$column] = $value;
                }
            }
            $columns = $column_local;
            $sql = "UPDATE "."`".$this->table."`"." SET ".implode(",",$update)." WHERE "."`".$this->table."`."."`".$id."`"." = ".$this->getId() ;
        }
        $queryPrepared = $this->pdo->prepare($sql);
        return $queryPrepared->execute( $columns);
    }

    public function getLink()
    {
        return $this->link;
    }
    
    public function getServers()
    {
        return $this->_servers;
    }

    
}
