<?php

namespace controller;

use controller\Controller;
use controller\Security;
use entity\User;
use entity\Post;
use entity\Comment;
use manager\UsersManager;
use manager\CommentManager;
use manager\PostManager;
use services\DAO;
use services\Helper;


class AdminController{

    private $UsersManager;
    private $PostManager;
    private $CommentManager;
    private $Controller;
    private $DAO;

    public function __construct()
    {
        $this->DAO = new DAO();
        $this->UsersManager = new UsersManager();
        $this->PostManager = new PostManager();
        $this->CommentManager = new CommentManager();
        $this->Controller = new Controller();
    }

    public function getAdminPanel($twig){
        $result = $this->UsersManager->getLoginPage();

        if($result == 'login' || $_SESSION['login']){
            $posts = $this->DAO->getNonHidePosts(5);
            $commentLimit = 10;
            $comments = $this->DAO->getAllCommentsPost(0, $commentLimit);
            echo $twig->render('/frontend/adminPanel.twig', [
                'posts' => $posts,
                'commentLimit' => $commentLimit,
                'comments' => $comments,
                ]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function getCreatePage($twig){

        $usersList = $this->UsersManager->getUsers();
        $result = $this->UsersManager->getLoginPage();
        
        if($result == 'login' || $_SESSION['login']){
            echo $twig->render('/frontend/create.twig', [
                'usersList' => $usersList,
            ]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function getModeratedComPage($twig, $sizePage= 10){
        $usersList = $this->UsersManager->getUsers();
        $result = $this->UsersManager->getLoginPage();
        $comments = Comment::getModeratedComments();

        $page = Helper::getPage();
        $sizePage = Helper::getSizePage($comments);
        $nbPage = Helper::getNbPage($comments, $sizePage);   

        if($result == 'login' || $_SESSION['login']){
            $user = $_SESSION['login'];

            echo $twig->render('/frontend/moderationPage.twig', [
                'comments' => $comments,
                'nbPage' => $nbPage,
                'usersList' => $usersList,
                'sizePage' => $sizePage,
                'page' => $page,
                'OnlineUser' => $user
            ]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function getReportedComPage($twig, $sizePage= 10){
        $usersList = $this->UsersManager->getUsers();
        $result = $this->UsersManager->getLoginPage();
        $comments = Comment::getReportedComments();
        
        $page = Helper::getPage();
        $sizePage = Helper::getSizePage($comments);
        $nbPage = Helper::getNbPage($comments, $sizePage);

        

        if($result == 'login' || $_SESSION['login']){
            $user = $_SESSION['login'];

            echo $twig->render('/frontend/moderationPage.twig', [
                'comments' => $comments,
                'nbPage' => $nbPage,
                'usersList' => $usersList,
                'sizePage' => $sizePage,
                'page' => $page,
                'OnlineUser' => $user
            ]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function getModerationPage($twig, $sizePage = 10){
        $usersList = $this->UsersManager->getUsers();
        $result = $this->UsersManager->getLoginPage();
        $comments = Comment::getAllComments();

        $page = Helper::getPage();
        $sizePage = Helper::getSizePage($comments);
        $nbPage = Helper::getNbPage($comments, $sizePage);

        if($result == 'login' || $_SESSION['login']){
            $user = $_SESSION['login'];

            echo $twig->render('/frontend/moderationPage.twig', [
                'comments' => $comments,
                'nbPage' => $nbPage,
                'usersList' => $usersList,
                'sizePage' => $sizePage,
                'page' => $page,
                'OnlineUser' => $user
            ]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function getPostEditPage($twig){
        $usersList = $this->UsersManager->getUsers();
        $result = $this->UsersManager->getLoginPage();

        $postId = $_GET['article'];

        $post = Post::initPost($postId);
        
        if($result == 'login' || $_SESSION['login']){
            echo $twig->render('/frontend/editarticle.twig', [
                'article' => $postId,
                'usersList' => $usersList,
                'post' => $post,                
            ]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function getListsPostsToEdit($twig, $page, $sizePage = 10){  
        $result = $this->UsersManager->getLoginPage();

        $posts = $this->DAO->getAllPosts();
        $nbPage = ceil(sizeof($posts)/$sizePage);
        
        for($i = 0; $i < sizeof($posts); $i++){
            if($posts[$i]->getHideState() == '1'){
                $newTitle = $posts[$i]->getTitle();
                $newTitle = "(Hide) ".$newTitle;
                $posts[$i]->setTitle($newTitle);
            }
        }
        
        if($result == 'login' || $_SESSION['login']){
            echo $twig->render('/frontend/adminlistposts.twig', [
                'page' => $page,
                'sizePage' => $sizePage,
                'post' => $posts,
                'userRank' => $_SESSION['rank'],
                'nbPage' => $nbPage]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function getUsersPage($twig){
        $result = $this->UsersManager->getLoginPage();
        $users = $this->UsersManager->getUsers();

        if($result == 'login' || $_SESSION['login']){
            echo $twig->render('/frontend/userPage.twig', [
                'users' => $users,
                'userRank' => $_SESSION['rank'],
            ]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }
}

$AdminController = new AdminController();