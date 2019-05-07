<?php

require_once "Database.php";

class CommentManager{

    public function addCommentToPost($postId, $author, $content){
        $db = Database::connect();
        $author = $this->checkInput($author);
        $content = $this->checkInput($content);
        $statement = $db->prepare("INSERT INTO opc_blog_comment (post_id, author, comment, comment_date) VALUES (?,?,?,?)");
        $dateOfCom = date("Y-m-d H:i:s");
        $statement->execute(array($postId, $author, $content, $dateOfCom));
        Database::disconnect();
    }

    public function addCommentToComment($postId, $author, $content, $commentId){
        $db = Database::connect();
        $author = $this->checkInput($author);
        $content = $this->checkInput($content);
        $content = preg_replace("/\s|&nbsp;/",'',$content);
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

    public function getNbComments(){
        $db = Database::connect();
        $statement = $db->prepare('SELECT post_id, COUNT(*) AS nbComments FROM opc_blog_comment GROUP BY post_id');

        $statement->execute();
        $comments = $statement->fetchAll();
        
        Database::disconnect();
        
        return $comments;
    }

    public function getComments($postId){
        $db = Database::connect();
        $statement = $db->prepare('SELECT id, post_id, author,comment_parent, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin\') AS comment_date 
        FROM opc_blog_comment 
        WHERE post_id = ?');

        $statement->execute(array($postId));
        $comments = $statement->fetchAll();
        
        Database::disconnect();
        
        return $comments;
    }
}