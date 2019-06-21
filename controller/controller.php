<?php

namespace controller;

use manager\CommentManager;
use manager\PostManager;
use manager\UsersManager;
use services\DAO;
use entity\Comment;
use entity\Post;
use entity\User;
use services\Helper;

class Controller{
    private $login;
    private $PostManager;
    private $DAO;
    private $CommentManager; 

    public function __construct()
    {
        $this->login = new UsersManager();
        $this->PostManager = new PostManager();
        $this->CommentManager = new CommentManager();
        $this->DAO = new DAO();
    }

    public function listPosts($twig, $page, $sizePage  = 5)
    {
        $posts = $this->DAO->getNonHidePosts();
        $nbPage = ceil(sizeof($posts)/$sizePage);

        

        for($i = 0; $i < sizeof($posts); $i++){
            $nb_comments = $this->DAO->getAllCommentsPost($posts[$i]->getId(), 0, true);
            $posts[$i]->setNb_comments($nb_comments);
            $description = Helper::setDescription($posts[$i]);
            $posts[$i]->setContent($description);
        }

        
       
        echo $twig->render('/frontend/listarticles.twig', [
            'year' => date('Y'),
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
            'year' => date('Y'),
            'page' => $page,
            'sizePage' => 5,
            'post' => $post,
            'comments' => $comments,
            'nbPage' => $nbPage]);
    }

    public function addPost($title, $content, $author, $img_name){
        if(Post::postExist($title)){
            header('Location: index.php?action=create&titleexist=true');
            die();
        }
        else{
            $newPost = Post::setNewPost($title, $content, $author, $img_name);
            User::updatePostAndCommentData(User::getIdFromName($author));
        }

        if ($newPost === false) {
            throw new Exception('Impossible d\'ajouter le post !');
        }
        else {
            header('Location: index.php?action=admin&addPost=success');
            die();
        }
    }

    public function updatePost($id, $title, $content, $author, $img_name){
        $update = $this->PostManager->preparePost($id, $title, $content, $author, $img_name);
        if ($update === false) {
            throw new Exception('Impossible de mettre a jour le post !');
        }
        else {
            header('Location: index.php?action=admin&edit=success');
        }
    }

    public function addComment($postId, $author, $comment)
    {

        $author = Security::verifyIdentity($author);
        $data = Helper::deleteJScode($comment);

        if(((int)$data['nbReplace']) > 0){
            $moderation = 1;
        }
        else{
            $moderation = 0;
        }

        $newComment = $this->CommentManager->addCommentToPost($postId, $author, $data['content'], $moderation);

        if ($newComment === false) {
            throw new Exception('Impossible d\'ajouter le commentaire !');
        }
        else {
            header('Location: index.php?action=post&id=' . $postId);
        }
    }
    
    public function addCommentToComment($postId, $author, $content, $commentId, $depth){

        $author = Security::verifyIdentity($author);
        $data = Helper::deleteJScode($content);

        if(((int)$data['nbReplace']) > 0){
            $moderation = 1;
        }
        else{
            $moderation = 0;
        }

        $newComment = $this->CommentManager->addCommentToComment($postId, $author, $data['content'], $commentId, $depth, $moderation);

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

    public function getPolicyPage($twig){
        echo $twig->render('/frontend/confpolicy.twig');
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


