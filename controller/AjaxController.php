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
use services\Database;

class AjaxController{

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

    public function newUser(){
        $result = $this->UsersManager->getLoginPage();
        
        $userName = $_POST['name'];
        $userRawPwd = $_POST['raw_pwd'];
        $userMail = $_POST['email'];
        $userRank = $_POST['rank'];

        if($result == 'login' || $_SESSION['login']){            
            if(User::userExist($userName)){
                $state = array("ERROR" => 'userexist');
                echo json_encode($state);
            }
            elseif(User::mailExist($userMail)){
                $state = array("ERROR" => 'mailexist');
                echo json_encode($state);
            }
            else{                
                $user = User::createUser($userName, $userRawPwd, $userMail, $userRank);
                $state = array('SUCCESS' => 'true');
                echo json_encode($state);
            }
        }
        else{
            $state = array("ERROR" => 'Erreur, vous ne semblez pas connecte');
            echo json_encode($state);
        }
    }

    public function deleteUser($user){
        $user = User::initUser($user);
        $result = $user->deleteUser();
        if($result){
            $state = array("result" => 'success');
            echo json_encode($state);
        }
        else{
            $state = array("result" => 'failed');
            echo json_encode($state);
        }
    }

    public function promoteUser($userId){
        $user = User::initUser($userId);
        $userRank = (int)$user->getRank();
        $userRank++;
        $user->setRank($userRank);
        $user->updateUserRank();
    }

    public function demoteUser($userId){
        $user = User::initUser($userId);
        $userRank = (int)$user->getRank();
        $userRank--;
        $user->setRank($userRank);
        $user->updateUserRank();
    }

    public function deletePost($id){
        $post = Post::initPost($id);
        $post->deletePost();
        header('Location: index.php?action=listArticles');
        die();
    }

    public function deleteComment($id){
        $post = Comment::initComment($id);
        $post->deleteCom();
    }

    public function setModeration($commentId, $mode){
        $comment = Comment::initComment($commentId);
        if($mode == 'true'){         
            $comment->setModeration(true);   
            $comment->update();
        }
        else{
            $comment->setModeration(false);
            $comment->update();
        }
    }

    public function displayArticle($article){
        $post = Post::initPost($article);
        $post->setHideState('0');
        $post->updatePost();
        header('Location: index.php?action=listArticles');
        die();
    }

    public function hideArticle($article){
        $post = Post::initPost($article);
        $post->setHideState('1');
        $post->updatePost();
        header('Location: index.php?action=listArticles');
        die();
    }

    public function addAdminComment($commentId, $message, $author){
        $comment = Comment::initComment($commentId);
        $postId = $comment->getPost_id();
        $depth = (int)$comment->getDepth();
        $depth = $comment->addDepth($depth);
        $this->Controller->addCommentToComment($postId, $author, $message, $commentId, $depth);
        header('Location: index.php?action=moderation');
        die();
    }

    public function getRawPost($postId){
        $db = new Database();	
        $db = $db->connect();	
        $req = $db->prepare('	
        SELECT * 	
        FROM opc_blog_posts	
        WHERE id = ?');	
        $req->execute(array($postId));	
        $post = $req->fetch();	
        $post['content'] = html_entity_decode($post['content']);
        unset($db);	
        return $post; 	
    }

}

$AjaxController = new AjaxController();