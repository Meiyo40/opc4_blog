<?php

namespace controller;

use entity\User;
use manager\UserLogin;

class LoginController{

    private $User;
    private $UserLogin;

    public function __construct()
    {
        $this->User = new User();
        $this->UserLogin = new UserLogin();
    }

    public function loginPage(){
        $result = $this->UserLogin->getLoginPage();

        if($result){
            require(__DIR__.'/../view/frontend/adminPanel.php');
        }
        else{
            require(__DIR__.'/../view/frontend/loginPage.php');
        }
    }

    public function getLoginPage(){
        require(__DIR__.'/../view/frontend/loginPage.php');
    }

    public function getAdminPanel(){
        $posts = $this->PostManager->getLastPosts();
        $comments = $this->CommentManager->getLastComments();
        $result = $this->UserLogin->getLoginPage();

        if($result == 'login' || $_SESSION['login']){
            require(__DIR__.'/../view/frontend/adminPanel.php');
        }
        else{
            header('Location: index.php?action=loginFail');
        }
    }

    public function getCreatePage(){

        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();
        
        if($result == 'login' || $_SESSION['login']){
            require(__DIR__.'/../view/frontend/create.php');
        }
        else{
            header('Location: index.php?action=loginFail');
        }
    }

    public function getModerationPage(){

        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();
        $comments = $this->CommentManager->getAllComments();
        $sizePage = 10;
        $nbPage = ceil(sizeof($comments)/$sizePage);

        
        
        if($result == 'login' || $_SESSION['login']){
            require(__DIR__.'/../view/frontend/moderationPage.php');
        }
        else{
            header('Location: index.php?action=loginFail');
        }
    }
}

$LoginController = new LoginController();