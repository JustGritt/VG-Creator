<?php

namespace App\Core;

abstract class Sql
{
    protected $pdo;
    protected $table;
    protected $link;
    protected $server;
    public static $instance = array();
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
    

    /*
    public static function getInstance(){
      $class = get_called_class(); // or get_class(new static());
      if(!isset(self::$instance[$class])){
          self::$instance[$class] = new static(); // create and instance of child class which extends Singleton super class
          echo "new ". $class . PHP_EOL; // remove this line after testing
          return  self::$instance[$class]; // remove this line after testing
      }
      echo "old ". $class . PHP_EOL; // remove this line after testing
      return static::$instance[$class];
    }
    */
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
    
    abstract public function connect();
    abstract protected function _query($sql);

    public static function getClass()
    {
        $class = 'MySQL';
        if (PHP_VERSION_ID >= 50200 && extension_loaded('pdo_mysql')) {
            $class = 'SqlPDO';
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
      }
    }

    public function save()
    {
        $columns = get_object_vars($this);
        $columns = array_diff_key($columns, get_class_vars(get_class()));
        $calledClassExploded = explode("\\",get_called_class());
        $this->table = strtolower(DBPREFIXE.end($calledClassExploded));

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
    
    public function getLink()
    {
        return $this->link;
    }
    
    public function getServers()
    {
        return $this->_servers;
    }
    
}
