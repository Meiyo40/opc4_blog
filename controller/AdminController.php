<?php

namespace controller;

use controller\Controller;
use entity\User;
use entity\Post;
use entity\Comment;
use manager\UserLogin;
use manager\CommentManager;
use manager\PostManager;
use services\DAO;
use services\Helper;


class AdminController{

    private $User;
    private $UserLogin;
    private $PostManager;
    private $CommentManager;
    private $Controller;
    private $DAO;
    private $loggedUser;

    public function __construct()
    {
        $this->DAO = new DAO();
        $this->UserLogin = new UserLogin();
        $this->PostManager = new PostManager();
        $this->CommentManager = new CommentManager();
        $this->Controller = new Controller();
    }

    public function addAdminComment($commentId, $message, $author){
        $comment = Comment::initComment($commentId);
        $postId = $comment->getPost_id();
        $depth = (int)$comment->getDepth();
        $depth = $comment->addDepth($depth);
        $this->Controller->addCommentToComment($postId, $author, $message, $commentId, $depth);
        header('Location: index.php?action=moderation');
    }

    public function setLoggedUser(){
        $this->loggedUser = $_SESSION['login'];
    }

    public function loginPage($twig){
        $result = $this->UserLogin->getLoginPage();

        if($result){
            echo $twig->render('/frontend/adminPanel.twig');
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
        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();
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

        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();
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
        $usersList = $this->UserLogin->getUsers();
        $result = $this->UserLogin->getLoginPage();

        $postId = $_GET['article'];

        $post = $this->PostManager->getPost($postId);
        
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
        if($_GET['action'] == 'listArticles'){
            header('Location: index.php?action=listArticles');
        }
        else{
            header('Location: index.php?action=admin');
        }
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
        $user->updateUserRank();
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