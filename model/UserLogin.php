<?php
class UserLogin{

    private function getUserName(string $name){
        $db = Database::connect();
        $statement = $db->prepare("SELECT id, name, hash_pwd, DATE_FORMAT(last_connexion, \'%d/%m/%Y à %Hh%imin\') AS last_visit FROM opc_blog_users WHERE name = ?");
        
        $statement->execute(array($name));
        $req = $statement->fetchAll(); 
        Database::disconnect();
        return $req;
    }

    private function getHashedPassword(string $name, string $password){
        $db = Database::connect();
        $req = $db->prepare("
        SELECT id, name, hash_pwd, last_connexion 
        FROM opc_blog_users
        WHERE name=? and hash_pwd=?");
        
        $req->execute(array($name, $password));
        $req = $req->fetch();
        Database::disconnect();
        return $req;
    }

    private function setPassword(string $name, string $oldPassword){

    }

    private function deleteUser(string $name, string $password){

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
        if($this->getHashedPassword($name, $password))
        //password_hash($password, PASSWORD_BCRYPT)
        {
            return true;
        }
        else{
            return false;
        }
    }

    public function getLoginPage(){
        if(isset($_POST['username']) && isset($_POST['password'])){
            if($this->loginIsValid($_POST['username'], $_POST['password'])){
                return 'login';
            }
            else{
                return 'invalid user';
            }
        }
    }
}