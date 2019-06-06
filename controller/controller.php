<?php

namespace controller;

use manager\CommentManager;
use manager\PostManager;
use manager\UserLogin;
use services\DAO;
use entity\Comment;
use entity\Post;

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

    public function listPosts($twig, $page, $sizePage  = 5)
    {
        $posts = $this->DAO->getPosts();
        $nbPage = ceil(sizeof($posts)/$sizePage);

        for($i = 0; $i < sizeof($posts); $i++){
            $nb_comments = $this->DAO->getAllCommentsPost($posts[$i]->getId(), 0, true);
            $posts[$i]->setNb_comments($nb_comments);
        }
       
        echo $twig->render('/frontend/listarticles.twig', [
            'page' => $page,
            'sizePage' => 5,
            'posts' => $posts,
            'nbPage' => $nbPage]);
    }

    public function post($twig)
    {
        $post = Post::initPost($_GET['id']);
        $comments = $this->DAO->getAllCommentsPost($_GET['id']);

        $page = 1;
        $nbPage = 1;

        echo $twig->render('/frontend/postView.twig', [
            'page' => $page,
            'sizePage' => 5,
            'post' => $post,
            'comments' => $comments,
            'nbPage' => $nbPage]);
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

        if($author == 'admin' && $_SESSION['login'] != 'admin'){
            $author = '(not) '.$author;
        }

        $newComment = $this->CommentManager->addCommentToPost($postId, $author, $comment);

        if ($newComment === false) {
            throw new Exception('Impossible d\'ajouter le commentaire !');
        }
        else {
            header('Location: index.php?action=post&id=' . $postId);
        }
    }
    
    public function addCommentToComment($postId, $author, $content, $commentId, $depth){

        if($author == 'admin' && $_SESSION['login'] != 'admin'){
            $author = '(not) '.$author;
        }

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


