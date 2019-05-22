<?php

namespace controller;

use entity\User;
use entity\Comment;
use manager\UserLogin;
use manager\CommentManager;
use manager\PostManager;
use services\DAO;


class AdminController{

    private $User;
    private $UserLogin;
    private $PostManager;
    private $CommentManager;
    private $DAO;

    public function __construct()
    {
        $this->DAO = new DAO();
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
        $posts = $this->DAO->getPosts(5);
        $commentLimit = 10;
        $comments = $this->DAO->getAllCommentsPost(0, $commentLimit);
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

    public function getModerationPage($mode, $sizePage = 10){

        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();
        if($mode == 'list'){
            $comments = Comment::getAllComments();
        }
        elseif($mode == 'priority'){
            $comments = Comment::getReportedComments();
        }
        elseif($mode == 'modlist'){
            $comments = Comment::getModeratedComments();
        }
        if(is_array($comments)){
            $nbPage = ceil(sizeof($comments)/$sizePage);   
        }     
        
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

    public function setModeration($commentId, $mode){
        $comment = Comment::initComment($commentId);
        if($mode == 'true'){
            $comment->setModeration('1');
        }
        else{
            $comment->setModeration('0');
        }
        $comment->update('moderation');
    }

    public function getListsPostsToEdit($sizePage = 10){
        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();

        $posts = $this->DAO->getPosts();
        $nbPage = ceil(sizeof($posts)/$sizePage);
        
        if($result == 'login' || $_SESSION['login']){
            require(__DIR__.'/../view/frontend/listArticles.php');
        }
        else{
            header('Location: index.php?action=loginFail');
        }
    }

    public function deletePost($id){
        $post = Post::initPost($id);
        $post->deletePost();
    }

    public function deleteComment($id){
        $post = Comment::initComment($id);
        $post->deleteCom();
    }

    public function getUsersPage(){
        $result = $this->UserLogin->getLoginPage();
        $users = DAO::getAllUsers();

        if($result == 'login' || $_SESSION['login']){
            require(__DIR__.'/../view/frontend/userPage.php');
        }
        else{
            header('Location: index.php?action=loginFail');
        }
    }

    public function manageUser($action, $userId){
        if($action == 'promote'){
            $user = User::initUser($userId);
            $userRank = (int)$user->getRank();
            $userRank += 1;
            $user->setRank($userRank);
            $user->updateUser('rank');
        }
        elseif($action == 'demote'){

        }
    }
}

$AdminController = new AdminController();