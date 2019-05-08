<?php
class UserLogin{

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

    private function getUserId(string $name){
        $db = Database::connect();
        $req = $db->prepare("
        SELECT id
        FROM opc_blog_users
        WHERE name=?");
        
        $req->execute(array($name));
        $req = $req->fetch();
        Database::disconnect();
        return $req[0];
    }

    private function setPassword(string $name, string $password){
        $db = Database::connect();
        $req = $db->prepare("
        UPDATE `opc_blog_users`
        SET hash_pwd = ?
        WHERE name = ?");
        
        $req->execute(array(password_hash($password, PASSWORD_BCRYPT), $name));
        $req = $req->fetch();
        Database::disconnect();
        return $req;
    }

    private function deleteUser(string $name, string $password){

    }

    private function updateLastUserConnexion($name){
        $db = Database::connect();
        $statement = $db->prepare("UPDATE `opc_blog_users` SET `last_connexion` = ? WHERE `name` = ?");

        $date = date("Y-m-d H:i:s");
        $statement->execute(array($date,$name));
        $users = $statement->fetchAll();
        
        Database::disconnect();
    }

    private function checkInput ($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    private function loginIsValid(string $name, string $password){
        $name = $this->checkInput($name);
        $password = $this->checkInput($password);
        if(password_verify($password, $this->getUserPwd($name)[0]))
        {
            $this->updateLastUserConnexion($name);
            return true;
        }
        else{
            return false;
        }
    }

    public function getUsers(){
        //display users/author list
        $db = Database::connect();
        $statement = $db->prepare('SELECT*FROM opc_blog_users');

        $statement->execute();
        $users = $statement->fetchAll();
        
        Database::disconnect();
        
        return $users;
    }

    public function getLoginPage(){
        if(isset($_POST['username']) && isset($_POST['password'])){
            if($this->loginIsValid($_POST['username'], $_POST['password'])){
                $_SESSION['login'] = $_POST['username'];
                $_SESSION['user-id'] = $this->getUserId($this->checkInput($_POST['username']))[0];
                return 'login';
            }
            else{
                return 'invalid user';
            }
        }
    }
}