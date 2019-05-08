<?php

require_once(__DIR__.'/../model/CommentManager.php');
require_once(__DIR__.'/../model/PostManager.php');
require_once(__DIR__.'/../model/UserLogin.php');

class Controller{
    private $login;
    private $PostManager;
    private $CommentManager; 

    public function __construct()
    {
        $this->login = new UserLogin();
        $this->PostManager = new PostManager();
        $this->CommentManager = new CommentManager();
    }

    public function loginPage(){
        $result = $this->login->getLoginPage();

        if($result){
            require(__DIR__.'/../view/frontend/adminPanel.php');
        }
        else{
            require(__DIR__.'/../view/frontend/loginPage.php');
        }
    }

    public function listPosts()
    {
        $posts = $this->PostManager->getPosts(); // Appel d'une fonction de cet objet
        $comments = $this->CommentManager->getNbComments();
        
        require(__DIR__.'/../view/frontend/listPostsView.php');
    }

    public function post()
    {
        $post = $this->PostManager->getPost($_GET['id']);
        $comments = $this->CommentManager->getComments($_GET['id']);
        
        require(__DIR__.'/../view/frontend/postView.php');
    }

    public function addPost($title, $content, $author){
        $newPost = $this->PostManager->addPost($title, $content, $author);

        if ($newPost === false) {
            throw new Exception('Impossible d\'ajouter le post !');
        }
        else {
            header('Location: index.php?action=admin&addPost=success');
        }
    }

    public function addComment($postId, $author, $comment)
    {
        $newComment = $this->CommentManager->addCommentToPost($postId, $author, $comment);

        if ($newComment === false) {
            throw new Exception('Impossible d\'ajouter le commentaire !');
        }
        else {
            header('Location: index.php?action=post&id=' . $postId);
        }
    }
    
    public function addCommentToComment($postId, $author, $content, $commentId, $depth){
        $newComment = $this->CommentManager->addCommentToComment($postId, $author, $content, $commentId, $depth);

        if ($newComment === false) {
            throw new Exception('Impossible d\'ajouter le commentaire !');
        }
        else {
            header('Location: index.php?action=post&id=' . $postId);
        }
    }

    public function deleteComment(){
        
    }

    public function getLoginPage(){
        require(__DIR__.'/../view/frontend/loginPage.php');
    }

    public function getAdminPanel(){
        $posts = $this->PostManager->getLastPosts();
        $comments = $this->CommentManager->getLastComments();
        $result = $this->login->getLoginPage();

        if($result == 'login' || $_SESSION['login']){
            require(__DIR__.'/../view/frontend/adminPanel.php');
        }
        else{
            header('Location: index.php?action=loginFail');
        }
    }

    public function getCreatePage(){

        $usersList = $this->login->getUsers();
        $result = $this->login->getLoginPage();
        
        if($result == 'login' || $_SESSION['login']){
            require(__DIR__.'/../view/frontend/create.php');
        }
        else{
            header('Location: index.php?action=loginFail');
        }
    }
}

$Controller = new Controller();


