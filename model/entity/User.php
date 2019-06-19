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
        $this->countUserComments();
        $this->updateUserNbComments();
        $this->countUserPosts();
        $this->updateUserPosts();
    }

    public function getRank(){
        return $this->rank;
    }

    private function getHash_pwd(){
        return $this->hash_pwd;
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

    private function setHash_pwd($new_pwd){
        $this->hash_pwd = $new_pwd;
    }

    public function loginIsValid($name, $password){
        $name = Helper::validateContent($name);
        $password = Helper::validateContent($password);
        if(password_verify($password, $this->getHash_pwd()))
        {
            $this->updateLastUserConnexion($this->getName());

            return true;
        }
        else{
            return false;
        }
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
    
    public static  function getIdFromName(string $name){
        $db = new Database();
        $db = $db->connect();
        $req = $db->prepare("
        SELECT id
        FROM opc_blog_users
        WHERE name=?");
        
        $req->execute(array($name));
        $req = $req->fetch();
        unset($db);
        return $req[0];
    }

    public function updateLastUserConnexion($name){
        $db = new Database();
        $db = $db->connect();
        $statement = $db->prepare("UPDATE `opc_blog_users` SET `last_connexion` = ? WHERE `name` = ?");

        $date = date("Y-m-d H:i:s");
        $statement->execute(array($date,$name));
        
        unset($db);
    } 

    public function countUserComments(){
        $db = new Database();
        $db = $db->connect();

        $statement = $db->prepare("SELECT*FROM opc_blog_comment WHERE author NOT LIKE '%(NOT)%' AND author LIKE '%{$this->name}%'");
        $statement->execute();
        
        $result = $statement->fetchAll();
        file_put_contents('debug.html', 'nbcomment: '.sizeof($result));
        $this->comments = sizeof($result);
        unset($db);

        return $this->comments;        
    }

    public function updateUserNbComments(){
        $db = new Database();
        $db = $db->connect();
        $statement = $db->prepare("UPDATE `opc_blog_users` SET `comments` = ? WHERE `name` = ?");

        $statement->execute(array($this->comments, $this->name));
        
        unset($db);
    }

    public function countUserPosts(){
        $db = new Database();
        $db = $db->connect();

        $statement = $db->prepare("SELECT*FROM opc_blog_posts WHERE author NOT LIKE '%(NOT)%' AND author LIKE '%{$this->name}%'");
        $statement->execute();
        
        $result = $statement->fetchAll();
        file_put_contents('debug.html', 'nbcomment: '.sizeof($result));
        $this->articles = sizeof($result);
        unset($db);

        return $this->articles;        
    }

    public function updateUserPosts(){
        $db = new Database();
        $db = $db->connect();
        $statement = $db->prepare("UPDATE `opc_blog_users` SET `articles` = ? WHERE `name` = ?");

        $statement->execute(array($this->articles, $this->name));
        
        unset($db);
    }

    public function updateUserRank(){
        $db = new Database();
        $db = $db->connect();

        if($this->rank > 3){
            $this->rank = 3;
        }
        elseif($this->rank < 0){
            $this->rank = 0;
        }          
        $statement = $db->prepare("UPDATE `opc_blog_users` SET `rank` = ? WHERE `id` = ? ");
        $statement->execute(array($this->rank,$this->id));
        
        unset($db);

        echo 'Nouveau rang de l\'utilisateur: '.$this->getRankName($this->rank);
    } 

    public function getRankName($ranklevel){
        switch($ranklevel){
            case '0':
                return 'Utilisateur';
                break;
            case '1':
                return 'Modérateur';
                break;
            case '2':
                return 'Autheur';
                break;
            case '3':
                return 'Administrateur';
                break;
            default:
                return 'Erreur';
                break;
        }
    }

    public static function mailExist($email){
        $db = new Database();
        $db = $db->connect();
        $statement = $db->prepare("SELECT*FROM opc_blog_users WHERE mail = ?");
        $statement->execute(array($email));
        $result = $statement->fetch();

        unset($db);

        if($result){
            return true;
        }
        else{
            return false;
        }
        
    }

    public static function userExist($username){
        $db = new Database();
        $db = $db->connect();
        $statement = $db->prepare("SELECT*FROM opc_blog_users WHERE name = ?");
        $statement->execute(array($username));
        $result = $statement->fetch();

        unset($db);
        
        if($result){
            return true;
        }
        else{
            return false;
        }
        
    }

    public static function createUser($userName, $userRawPwd, $userMail, $userRank){
        $last_connexion = date("Y-m-d H:i:s");
        $hash_pwd = password_hash($userRawPwd, PASSWORD_BCRYPT);
        $userName = Helper::validateContent($userName);
        $db = new Database();
        $db = $db->connect();
        $statement = $db->prepare("INSERT INTO `opc_blog_users` 
                                    (name, hash_pwd, last_connexion, rank, mail, comments, articles) 
                                    VALUES (?,?,?,?,?,0,0)");

        $statement->execute(array($userName, $hash_pwd, $last_connexion, $userRank, $userMail));

        unset($db);
    }

    public static function initUser($id){
        $db = new Database();
        $db = $db->connect();
        $statement = $db->prepare("SELECT*FROM opc_blog_users WHERE id = ?");
        $statement->execute(array($id));

        $obj = $statement->fetch();
        
        $obj = new User($obj['id'], $obj['name'], $obj['hash_pwd'], $obj['last_connexion'], $obj['rank'], $obj['comments'], $obj['articles'], $obj['mail']);
        unset($db);
        
        return $obj;

        
    }

    public function deleteUser(){
        $db = new Database();
        $db = $db->connect();
        $statement = $db->prepare("DELETE FROM `opc_blog_users` WHERE id = ?");
        $result = $statement->execute(array($this->id));        
        unset($db);

        return $result;
    }

}