<?php

require_once "Database.php";

class CommentManager{

    public function getComment($postId){
        

    }

    public function deleteComment($commentId){

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
        $statement = $db->prepare('SELECT id, post_id, author, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin\') AS comment_date 
        FROM opc_blog_comment 
        WHERE post_id = ?');

        $statement->execute(array($postId));
        $comments = $statement->fetchAll();
        
        Database::disconnect();
        
        return $comments;
    }
}