<?php
namespace manager;

use services\Database;
use services\Helper;
use entity\Post;

define ("IMG_MAXSIZE", 10485760);

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

    public function addPost($title, $content, $author, $img_name){

        $pattern = '/(gif|png|jpeg|jpg)$/i';
        $extension = pathinfo($img_name, PATHINFO_EXTENSION);
        $extension = preg_match($pattern, $extension);

        if($_FILES['image']['size'] < IMG_MAXSIZE && $extension){
            $author = Helper::validateContent($author);
            $content = Helper::validateContent($content);
            $title = Helper::validateContent($title);
            $date = date("Y-m-d H:i:s");
            $nb_comments = 0;

                      
            $post = new Post(null, $author, $content, $date, $title, $nb_comments);  
            $post->setImg($img_name);
            $post->addPost();
        }
        else{
            if(!$extension){
                $extension = 'Mauvaise extension de fichier : '.$extension.'<br>';
            }
            elseif($_FILES['image']['size'] > IMG_MAXSIZE){
                $file_size = 'Taille du fichier trop importante <br>';
            }
            return false;
        }
        
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