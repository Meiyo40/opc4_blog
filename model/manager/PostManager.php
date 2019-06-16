<?php
namespace manager;

use services\Database;
use services\Helper;
use entity\Post;



class PostManager{

    public function getPost($postId){
        $db = new Database();
        $db = $db->connect();
        $req = $db->prepare('
        SELECT * 
        FROM opc_blog_posts
        WHERE id = ?');
        $req->execute(array($postId));
        $post = $req->fetch();
        unset($db);
        return $post; 
    }

    public function updatePost($id, $title, $content, $author, $img_name){
        $author = Helper::validateContent($author);
        $title = Helper::validateContent($title);
        $content = Helper::validateContent($content);
        
        $post = Post::initPost($id);

        $post->setAuthor($author);
        $post->setTitle($title);
        $post->setContent($content);

        if($img_name != null){
            $post->setImg($img_name);
        }
        $post->updatePost();
    }

}