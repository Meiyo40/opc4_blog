<?php

require_once(__DIR__.'/../model/CommentManager.php');
require_once(__DIR__.'/../model/PostManager.php');
require_once(__DIR__.'/../model/UserLogin.php');

class Controller{
    public $login;

    public function __construct()
    {
        $this->login = new UserLogin();
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

    public static function listPosts()
    {
        $postManager = new PostManager(); // Création d'un objet
        $posts = $postManager->getPosts(); // Appel d'une fonction de cet objet
        
        require(__DIR__.'/../view/frontend/listPostsView.php');
    }

    public static function post()
    {
        $postManager = new PostManager();
        $commentManager = new CommentManager();

        $post = $postManager->getPost($_GET['id']);
        $comments = $commentManager->getComments($_GET['id']);
        
        require(__DIR__.'/../view/frontend/postView.php');
    }

    public static function addComment($postId, $author, $comment)
    {
        $commentManager = new CommentManager();

        $affectedLines = $commentManager->postComment($postId, $author, $comment);

        if ($affectedLines === false) {
            throw new Exception('Impossible d\'ajouter le commentaire !');
        }
        else {
            header('Location: index.php?action=post&id=' . $postId);
        }
    }

    public static function deleteComment(){
        
    }

    public static function getLoginPage(){
        //TODO request for user ID from db

        require(__DIR__.'/../view/frontend/loginPage.php');
    }
}



