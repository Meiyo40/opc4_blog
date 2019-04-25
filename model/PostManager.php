<?php
require_once ('Database.php');

class PostManager{

    public function getPosts(){
        $db = Database::connect();
        $statement = $db->prepare('SELECT id, title, author, content, DATE_FORMAT(date, \'%d/%m/%Y à %Hh%imin\') AS date FROM opc_blog_posts ORDER BY date DESC LIMIT 0, 5');
        
        
        $statement->execute();
        $req = $statement->fetchAll(); 
        Database::disconnect();
        return $req;
        
        
    }

    public function getPost($postId){
        $db = Database::connect();
        $req = $db->prepare('
        SELECT id, title, author, content, DATE_FORMAT(date, \'%d/%m/%Y à %Hh%imin\') AS date 
        FROM opc_blog_posts
        WHERE id = ?');
        $req->execute(array($postId));
        $post = $req->fetch();
        Database::disconnect();
        return $post;
        
    }

    public function addPost(){

    }

    public function editPost(){

    }

    public function deletePost(){
        
    }

}