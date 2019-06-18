<?php

namespace manager;

use services\Database;
use entity\User;
use services\Helper;

class UsersManager{

    public function getUsers(){
        $db = new Database();
        $db = $db->connect();
        $statement = $db->prepare('SELECT*FROM opc_blog_users');

        $statement->execute();
        $users = $statement->fetchAll();
        
        unset($db);
        
        return $users;
    }

    public function getLoginPage(){
        if(isset($_POST['username']) && isset($_POST['password'])){
            $name = Helper::validateContent($_POST['username']);
            $userId = User::getIdFromName($name);
            $User = User::initUser($userId);
            if($User->loginIsValid($_POST['username'], $_POST['password'])){
                
                $_SESSION['login'] = $name;                
                $_SESSION['user-id'] = $userId;                
                $_SESSION['rank'] = (int)$User->getRank();

                return 'login';
            }
            else{
                return 'invalid user';
            }
        }
    }
}