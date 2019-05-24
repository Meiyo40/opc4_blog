<?php

namespace services;

class Database{
    private $connection_db = null;
    private $db;
    private $hostname;
    private $username;
    private $password; 
    private $dbname;
    private $url;

    public function __construct()
    {
        $this->url = getenv('JAWSDB_URL');
        $this->db = parse_url($this->url);
        $this->hostname = $this->db['host'];
        $this->username = $this->db['user'];
        $this->password = $this->db['pass'];
        $this->dbname = ltrim($this->db['path'], '/');
    }
    

    public function connect(){
        try {
            $this->connection_db = new \PDO("mysql:host=$this->hostname;dbname=$this->dbname", $this->username, $this->password);
            $this->connection_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            die($e->getMessage());
        }
        return $this->connection_db;
    }

    public function disconnect(){
        $this->connection_db = null;
    }


}