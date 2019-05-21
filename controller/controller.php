<?php

namespace controller;

use manager\CommentManager;
use manager\PostManager;
use manager\UserLogin;
use services\DAO;
use entity\Comment;

class Controller{
    private $login;
    private $PostManager;
    private $DAO;
    private $CommentManager; 
    private $Comment;

    public function __construct()
    {
        $this->login = new UserLogin();
        $this->PostManager = new PostManager();
        $this->CommentManager = new CommentManager();
        $this->DAO = new DAO();
    }

    public function listPosts($sizePage  = 5)
    {
        $posts = $this->DAO->getPosts();
        $nbPage = ceil(sizeof($posts)/$sizePage);
       
        
        require(__DIR__.'/../view/frontend/listPostsView.php');
    }

    public function post()
    {
        $post = $this->PostManager->getPost($_GET['id']);
        $comments = $this->DAO->getAllCommentsPost($_GET['id']);
        
        require(__DIR__.'/../view/frontend/postView.php');
    }

    public function addPost($title, $content, $author, $img_name){
        $newPost = $this->PostManager->addPost($title, $content, $author, $img_name);

        if ($newPost === false) {
            throw new Exception('Impossible d\'ajouter le post !');
        }
        else {
            header('Location: index.php?action=admin&addPost=success');
        }
    }

    public function updatePost($id, $title, $content, $author, $img_name){
        $update = $this->PostManager->updatePost($id, $title, $content, $author, $img_name);
        if ($update === false) {
            throw new Exception('Impossible de mettre a jour le post !');
        }
        else {
            header('Location: index.php?action=admin&edit=success');
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

    public function deleteComment($id){
        $result = $this->CommentManager->deleteComment($_POST['comment']);
        if ($result === false) {
            throw new Exception('Impossible de supprimer le commentaire !');
        }
    }

    public function reportComment($commentId){
        
        $comment = Comment::initComment($commentId);

        $report = $comment->reportComment();
        
        if ($report === false) {
            throw new Exception('Impossible de signaler le commentaire !');
        }
        
    }

   
}

$Controller = new Controller();


