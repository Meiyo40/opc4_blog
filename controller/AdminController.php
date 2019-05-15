<?php

namespace controller;

use entity\User;
use manager\UserLogin;
use manager\CommentManager;
use manager\PostManager;


class AdminController{

    private $User;
    private $UserLogin;
    private $PostManager;
    private $CommentManager;

    public function __construct()
    {
        $this->User = new User();
        $this->UserLogin = new UserLogin();
        $this->PostManager = new PostManager();
        $this->CommentManager = new CommentManager();
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

    public function getPostEditPage(){
        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();

        $postId = $_GET['article'];

        $post = $this->PostManager->getPost($postId);
        
        if($result == 'login' || $_SESSION['login']){
            require(__DIR__.'/../view/frontend/editArticle.php');
        }
        else{
            header('Location: index.php?action=loginFail');
        }
    }

    public function getListsPostsToEdit(){
        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();
        
        if($result == 'login' || $_SESSION['login']){
            require(__DIR__.'/../view/frontend/listArticles.php');
        }
        else{
            header('Location: index.php?action=loginFail');
        }
    }
}

$AdminController = new AdminController();