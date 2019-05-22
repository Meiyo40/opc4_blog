<?php

namespace entity;

use services\Database;
use services\Helper;

class User{
    private $id;
    private $name;
    private $hash_pwd;
    private $last_connexion;
    private $rank;
    private $auth;
    private $comments;
    private $articles;
    private $mail;

    public function __construct($id, $name, $hash_pwd, $last_connexion, $rank, $comments, $articles, $mail)
    {
        $this->id = $id;
        $this->name = $name;
        $this->hash_pwd = $hash_pwd;
        $this->last_connexion = $last_connexion;
        $this->rank = $rank;
        $this->comments = $comments;
        $this->articles = $articles;
        $this->mail = $mail;
    }

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

    public function getComments(){
        return $this->comments;
    }

    public function getArticles(){
        return $this->articles;
    }

    public function getMail(){
        return $this->mail;
    }

    public function setMail($newMail){
        return $this->mail = $newMail;
    }

    public function setComments($newComments){
        $this->comments = $newComments;
    }

    public function setArticles($newArticles){
        $this->articles = $newArticles;
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

    public function updateUser($action){
        $db = Database::connect();
        switch($action){
            case 'rank':
                if($this->rank > 3){
                    $this->rank = 3;
                }
                $statement = $db->prepare("UPDATE `opc_blog_user` SET `rank` = ? WHERE `id` = ? ");
                $statement->execute(array($this->rank,$this->id));
                break;
            
            case 'last_connexion':
                $statement = $db->prepare("UPDATE `opc_blog_user` SET `last_connexion` = ? WHERE `id` = ? ");
                $statement->execute(array($this->last_connexion,$this->id));
                break;

            case 'comments':
                break;

            case 'articles':
                break;

            case 'mail':
                break;

            case 'pwd':
                break;
        }
        Database::disconnect();
    }

    /*
    $date = date("Y-m-d H:i:s");
    file_put_contents('debug.html', $date.'<br> name: '.$_POST['name'].'<br> raw_pwd: '.$_POST['raw_pwd'].'<br> email: '.$_POST['email'].'<br> rank: '.$_POST['rank']);
    */

    public static function createUser($userName, $userRawPwd, $userMail, $userRank){
        $last_connexion = date("Y-m-d H:i:s");
        $hash_pwd = password_hash($userRawPwd, PASSWORD_BCRYPT);
        $userName = Helper::validateContent($userName);
        file_put_contents('debug.html', 'name: '.$userName.'<br> raw_pwd: '.$hash_pwd.'<br> email: '.$userMail.'<br> rank: '.$userRank);
        $db = Database::connect();
        $statement = $db->prepare("INSERT INTO `opc_blog_users` 
                                    (name, hash_pwd, last_connexion, rank, mail, comments, articles) 
                                    VALUES (?,?,?,?,?,0,0)");

        $statement->execute(array($userName, $hash_pwd, $last_connexion, $userRank, $userMail));

        Database::disconnect();
    }

    public static function initUser($id){
        $db = Database::connect();
        $statement = $db->prepare("SELECT*FROM opc_blog_user WHERE id = ?");
        $statement->execute(array($id));

        $obj = $statement->fetch();
        
        $obj = new User($obj['id'], $obj['name'], $obj['hash_pwd'], $obj['last_connexion'], $obj['rank'], $obj['comments'], $obj['articles'], $obj['mail']);
        
        return $obj;

        Database::disconnect();
    }

}