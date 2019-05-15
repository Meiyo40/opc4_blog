<?php

namespace manager;

use services\Database;
use services\Helper;
use entity\Post;
use entity\Comment;



class CommentManager{

    public function addCommentToPost($postId, $author, $content){
        $db = Database::connect();
        $author = Helper::validateContent($author);
        $content = Helper::validateContent($content);
        $statement = $db->prepare("INSERT INTO opc_blog_comment (post_id, author, depth, comment, comment_date) VALUES (?,?,?,?,?)");
        $dateOfCom = date("Y-m-d H:i:s");
        $statement->execute(array($postId, $author, 0, $content, $dateOfCom));

        Post::addCommentcounter($postId);
        Database::disconnect();
    }

    public function addCommentToComment($postId, $author, $content, $commentId, $depth){
        $db = Database::connect();
        $author = Helper::validateContent($author);
        $content = Helper::validateContent($content);
        $content = preg_replace("/\s|&nbsp;/",'',$content);
        $statement = $db->prepare("INSERT INTO opc_blog_comment (post_id, comment_parent, depth, author, comment, comment_date) VALUES (?,?,?,?,?,?)");
        $dateOfCom = date("Y-m-d H:i:s");
        $statement->execute(array($postId, $commentId, $depth,$author, $content, $dateOfCom));
        
        Post::addCommentcounter($postId);
        Database::disconnect();
    }

    public function deleteComment($commentId){

    }

    public function reportComment($commentId){
        $db = Database::connect();
        $statement = $db->prepare("UPDATE `opc_blog_comment` SET `report` = `report` + 1 WHERE `id` = ? ");
        $statement->execute(array($commentId));
        Database::disconnect();
    }

    public function getLastComments(){
        //return last 8 posted comments on blog
        
        $db = Database::connect();
        $statement = $db->prepare('SELECT*FROM opc_blog_comment ORDER BY (comment_date) LIMIT 8');

        $statement->execute();
        $comments = $statement->fetchAll();
        
        Database::disconnect();
        
        return $comments;
    }

    public function getAllcomments(){
        
        $db = Database::connect();
        $statement = $db->prepare('SELECT*FROM opc_blog_comment ORDER BY (comment_date) DESC');

        $statement->execute();
        $comments = $statement->fetchAll();
        
        Database::disconnect();
        
        return $comments;
    }

    public function getAllReportedcomments(){
        
        $db = Database::connect();
        $statement = $db->prepare('SELECT*FROM opc_blog_comment WHERE report > 0 ORDER BY (comment_date) DESC');

        $statement->execute();
        $comments = $statement->fetchAll();
        
        Database::disconnect();
        
        return $comments;
    }
    
    public function updateComment($commentId, $newContent){
        $db = Database::connect();
        $statement = $db->prepare("UPDATE opc_blog_comment
        SET comment = ?
        WHERE id = ?");

        $statement->execute(array($newContent, $commentId));
        $comment = $statement->fetch();

        Database::disconnect();
        return $comment; 
    }

    public function getComments($postId){
        $db = Database::connect();
        $statement = $db->prepare('SELECT id, post_id, author, comment_parent, depth, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin\') AS comment_date 
        FROM opc_blog_comment 
        WHERE post_id = ?');

        $statement->execute(array($postId));
        $comments = $statement->fetchAll();
        
        Database::disconnect();
        
        return $comments;
    }
}