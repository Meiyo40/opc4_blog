<?php

namespace controller;

use manager\UsersManager;


class Security{

    public static function verifyIdentity($name){
        $users = new UsersManager();
        $users = $users->getUsers();
        if(is_array($users)){
            for($i = 0; $i < sizeof($users); $i++){
                if($users[$i]['name'] == $name){
                    if(isset($_SESSION['login']) && $_SESSION['login'] == $name){
                        $rank = (int)$users[$i]['rank'];
                        $name = Security::addRankNameToName($rank, $name);
                        return $name;
                    }else{
                        return $name = "(NOT) ".$name;
                    }
                }
            }
            $name = Security::isStealRank($name);
            return $name;                
        }
    }

    public static function addRankNameToName($rank, $name){
        switch ($rank){
            case 1:
                return $name = '[Modérateur] '.$name;
                break;
            case 2:
                return $name = '[Autheur] '.$name;
                break;
            case 3:
                return $name = '[Admin] '.$name;
                break;
            default:
                return $name;
                break;
        }
    }

    public static function isStealRank($name){
        if(strpos($name, '[Admin]') !== false || strpos($name, '[Autheur]') !== false || strpos($name, '[Modérateur]') !== false || strpos($name, '[Moderateur]') !== false ){
            return "(NOT) ".$name;
        }
        else{
            return $name;
        }
    }

    public function getLoginPage($twig){
        echo $twig->render('/frontend/loginPage.twig', [
            'action' => $_GET['action'],
        ]);
    }

    public function logout(){
        session_unset();
        session_destroy();
        header('Location: index.php');
    }
}

$Security = new Security();