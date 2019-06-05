<?php

namespace controller;

use entity\User;
use entity\Post;
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
    private $loggedUser;

    public function __construct()
    {
        $this->DAO = new DAO();
        $this->UserLogin = new UserLogin();
        $this->PostManager = new PostManager();
        $this->CommentManager = new CommentManager();
    }

    public function setLoggedUser($newUser){
        $this->loggedUser = $newUser;
    }

    public function loginPage($twig){
        $result = $this->UserLogin->getLoginPage();

        if($result){
            echo $twig->render('/frontend/adminPanel.twig', [
                'OnlineUser' => $_SERVER['login'],
                ]);
        }
        else{
            echo $twig->render('/frontend/loginPage.twig', [
                'action' => $_GET['action'],
            ]);
        }
    }

    public function getLoginPage($twig){
        echo $twig->render('/frontend/loginPage.twig', [
            'action' => $_GET['action'],
        ]);
    }

    public function getAdminPanel($twig){
        $posts = $this->DAO->getPosts(5);
        $commentLimit = 10;
        $comments = $this->DAO->getAllCommentsPost(0, $commentLimit);
        $result = $this->UserLogin->getLoginPage();

        if($result == 'login' || $_SESSION['login']){
            echo $twig->render('/frontend/adminPanel.twig', [
                'OnlineUser' => $this->loggedUser,
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

        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();
        
        if($result == 'login' || $_SESSION['login']){
            echo $twig->render('/frontend/create.twig', [
                'usersList' => $usersList,
                'OnlineUser' => $this->loggedUser,
            ]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function displayArticle($article){
        $post = Post::initPost($article);
        $post->setHideState('0');
        $post->updatePost();
        header('Location: index.php?action=listArticles');
    }

    public function hideArticle($article){
        $post = Post::initPost($article);
        $post->setHideState('1');
        $post->updatePost();
        header('Location: index.php?action=listArticles');
    }

    public function getModeratedComPage($twig, $sizePage= 10){
        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();
        $comments = Comment::getModeratedComments();

        if(is_array($comments)){
            $nbPage = ceil(sizeof($comments)/$sizePage);   
        }
        else{
            $nbPage = 1;
        }     
        
        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }
        else{
            $page = 1;
        }

        if(is_array($comments)){
            if(sizeof($comments) < 10){
                $sizePage = sizeof($comments);
            }
            else{
                $sizePage = 10;
            }
        }        

        if($result == 'login' || $_SESSION['login']){
            echo $twig->render('/frontend/moderationPage.twig', [
                'comments' => $comments,
                'nbPage' => $nbPage,
                'usersList' => $usersList,
                'sizePage' => $sizePage,
                'page' => $page,
                'OnlineUser' => $this->loggedUser,
            ]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function getReportedComPage($twig, $sizePage= 10){
        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();
        $comments = Comment::getReportedComments();

        if(is_array($comments)){
            $nbPage = ceil(sizeof($comments)/$sizePage);   
        }     
        
        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }
        else{
            $page = 1;
        }

        if(sizeof($comments) < 10){
            $sizePage = sizeof($comments);
        }
        else{
            $sizePage = 10;
        }

        if($result == 'login' || $_SESSION['login']){
            echo $twig->render('/frontend/moderationPage.twig', [
                'comments' => $comments,
                'nbPage' => $nbPage,
                'usersList' => $usersList,
                'sizePage' => $sizePage,
                'page' => $page,
                'OnlineUser' => $this->loggedUser,
            ]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function getModerationPage($twig, $sizePage = 10){

        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();
        $comments = Comment::getAllComments();

        if(is_array($comments)){
            $nbPage = ceil(sizeof($comments)/$sizePage);   
        }     
        
        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }
        else{
            $page = 1;
        }

        if(sizeof($comments) < 10){
            $sizePage = sizeof($comments);
        }
        else{
            $sizePage = 10;
        }

        if($result == 'login' || $_SESSION['login']){
            echo $twig->render('/frontend/moderationPage.twig', [
                'comments' => $comments,
                'nbPage' => $nbPage,
                'usersList' => $usersList,
                'sizePage' => $sizePage,
                'page' => $page,
                'OnlineUser' => $this->loggedUser,
            ]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function getPostEditPage($twig){
        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();

        $postId = $_GET['article'];

        $post = $this->PostManager->getPost($postId);
        
        if($result == 'login' || $_SESSION['login']){
            echo $twig->render('/frontend/editarticle.twig', [
                'OnlineUser' => $this->loggedUser,
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

    public function getListsPostsToEdit($twig, $page, $sizePage = 10){
        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();

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
                'OnlineUser' => $this->loggedUser,
                'page' => $page,
                'sizePage' => $sizePage,
                'post' => $posts,
                'nbPage' => $nbPage]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function deletePost($id){
        $post = Post::initPost($id);
        $post->deletePost();
        header('Location: index.php?action=listArticles');
    }

    public function deleteComment($id){
        $post = Comment::initComment($id);
        $post->deleteCom();
    }

    public function getUsersPage($twig){
        $result = $this->UserLogin->getLoginPage();
        $users = DAO::getAllUsers();

        if($result == 'login' || $_SESSION['login']){
            echo $twig->render('/frontend/userPage.twig', [
                'users' => $users,
                'OnlineUser' => $this->loggedUser,
                'userRank' => $_SESSION['rank'],
            ]);
        }
        else{
            header('Location: index.php?action=loginFail');
            die();
        }
    }

    public function manageUser($action, $userId){
        $user = User::initUser($userId);
        $userRank = (int)$user->getRank();

        if($action == 'promote'){
            $userRank += 1;            
        }
        elseif($action == 'demote'){
            $userRank -= 1;
        }

        $user->setRank($userRank);
        $user->updateUser('rank');
    }

    public function newUser(){
        $result = $this->UserLogin->getLoginPage();
        
        $userName = $_POST['name'];
        $userRawPwd = $_POST['raw_pwd'];
        $userMail = $_POST['email'];
        $userRank = $_POST['rank'];

        

        if($result == 'login' || $_SESSION['login']){
            $user = User::createUser($userName, $userRawPwd, $userMail, $userRank);
        }
        else{
            echo 'ERROR';
        }
    }

    public function deleteUser($user){
        $user = User::initUser($user);
        $result = $user->deleteUser();
        file_put_contents('debug.html', $result);
        if($result){
            header('Location: index.php?action=users&delete=success');
            die();
        }
        else{
            header('Location: index.php?action=users&delete=failed');
            die();
        }
    }

    public function disconnectUser(){
        require(__DIR__.'/../view/frontend/logout.php');
    }
}

$AdminController = new AdminController();