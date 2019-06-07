<?php

namespace controller;

use manager\UserLogin;


class Security{

    public static function isMember($name){
        $users = new UserLogin();
        $users = $users->getUsers();
            if(is_array($users)){
                for($i = 0; $i < sizeof($users); $i++){
                    if($users[$i]['name'] == $name){
                        if(isset($_SESSION['login']) && $_SESSION['login'] == $name){
                            $rank = (int)$users[$i]['rank'];
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
                            }
                        }else{
                            return $name = "(NOT) ".$name;
                        }
                    }
                }
                
                if(strpos($name, '[Admin]') || strpos($name, '[Autheur]') || strpos($name, '[Modérateur]') || strpos($name, '[Moderateur]')){
                    return "(NOT) ".$name;
                }
                else{
                    return $name;
                }
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