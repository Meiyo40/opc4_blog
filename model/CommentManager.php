<?php

require_once "Database.php";

class CommentManager{

    public function addCommentToPost($postId){
        
    }

    public function addCommentToComment($postId, $author, $content, $commentId){
        $db = Database::connect();
        $author = $this->checkInput($author);
        $content = htmlspecialchars($content);
        
        str_replace("<p>","",$content);
        $statement = $db->prepare("INSERT INTO opc_blog_comment (post_id, comment_parent, author, comment, comment_date) VALUES (?,?,?,?,?)");
        $dateOfCom = date("Y-m-d H:i:s");
        $statement->execute(array($postId, $commentId, $author, $content, $dateOfCom));
        Database::disconnect();
    }

    public function deleteComment($commentId){

    }

    private function checkInput ($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
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