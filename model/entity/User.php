<?php

namespace entity;

use services\Database;
use services\Helper;

class User{
    private $id;
    private $name;
    private $hash_password;
    private $rank;
    private $auth;

    public function getRank(){
        return $this->rank;
    }

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getAuth(){
        return $this->auth;
    }

    public function setId($newId){
        $this->id = $newId;
    }

    public function setName($newName){
        $this->name = $newName;
    }

    public function setAuth($newAuth){
        $this->auth = $newAuth;
    }

    public function setRank($newRank){
        $this->rank = $newRank;
    }

    public function loginIsValid($name, $password){
        $name = Helper::validateContent($name);
        $password = Helper::validateContent($password);
        if(password_verify($password, $this->getUserPwd($name)[0]))
        {
            $this->updateLastUserConnexion($name);
            return true;
        }
        else{
            return false;
        }
    }

    private function updateLastUserConnexion($name){
        $db = Database::connect();
        $statement = $db->prepare("UPDATE `opc_blog_users` SET `last_connexion` = ? WHERE `name` = ?");

        $date = date("Y-m-d H:i:s");
        $statement->execute(array($date,$name));
        $users = $statement->fetchAll();
        
        Database::disconnect();
    }

    private function getUserPwd(string $name){
        $db = Database::connect();
        $statement = $db->prepare("
            SELECT hash_pwd
            FROM opc_blog_users
            WHERE name=?
        ");
        $statement->execute(array($name));
        $statement = $statement->fetchAll(); 
        Database::disconnect();
        return $statement[0];
    }

    public function properties(){ 
        return get_object_vars($this); 
    }

    public function properties_names(){
        return array_keys(get_object_vars($this)); 
    }

    public function to_string() { 
        return "id : $this->id, author : $this->author, content : $this->content, date : $this->date, report : $this->report"; 
    }

}