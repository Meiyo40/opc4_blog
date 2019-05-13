<?php
namespace manager;

use services\Database;
use services\Helper;
use entity\Post;

class PostManager{

    public function getPosts(){
        $db = Database::connect();
        $statement = $db->prepare('SELECT id, title, author, content, nb_comments, DATE_FORMAT(date, \'%d/%m/%Y à %Hh%imin\') AS date FROM opc_blog_posts ORDER BY date DESC LIMIT 0, 5');
        
        $statement->execute();
        $req = $statement->fetchAll(); 
        Database::disconnect();
        return $req;        
        
    }

    public function getLastPosts(){
        //display last 5 posted article on blog
        
        $db = Database::connect();
        $statement = $db->prepare('SELECT*FROM opc_blog_posts ORDER BY (date) LIMIT 5');

        $statement->execute();
        $posts = $statement->fetchAll();
        
        Database::disconnect();
        
        return $posts;
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

    public function addPost($title, $content, $author){

        $post = new Post();
        $author = Helper::validateContent($author);
        $content = Helper::validateContent($content);
        $title = Helper::validateContent($title);
        $post->setTitle($title);
        $post->setContent($content);
        $post->setAuthor($author);
        $post->setDate(date("Y-m-d H:i:s"));
        
        $post->addPost();
    }

    public function editPost($id, $newContent, $newTitle, $newAuthor){
        $post = new Post();
        
        $author = Helper::validateContent($newAuthor);
        $content = Helper::validateContent($newContent);
        $title = Helper::validateContent($newTitle);

        $post->setId($id);
        $post->setTitle($title);
        $post->setContent($content);
        $post->setAuthor($author);
        $post->setDate(date("Y-m-d H:i:s"));
        
        $post->editPost();
    }

    public function deletePost($id){
        $post = new Post();

        $post->setId($id);

        $post->deletePost();
    }

}