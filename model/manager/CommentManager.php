<?php

namespace manager;

use services\Database;
use services\Helper;
use entity\Post;
use entity\Comment;



class CommentManager{

    public function addCommentToPost($postId, $author, $content, $moderation){
        $db = new Database();
        $db = $db->connect();
        $author = Helper::validateContent($author);
        $content = Helper::validateContent($content);

        if($moderation){
            $content = '[Sys]
                            Ce contenu a été modéré automatiquement car pourrait potentiellement contenir du code malintentionné
                        [Sys] <br>'.$content;
        }

        $statement = $db->prepare("INSERT INTO opc_blog_comment (post_id, author, depth, comment, comment_date, moderation) VALUES (?,?,?,?,?,?)");
        $dateOfCom = date("Y-m-d H:i:s");
        $statement->execute(array($postId, $author, 0, $content, $dateOfCom, $moderation));

        Post::addCommentcounter($postId);
        unset($db);
    }

    public function addCommentToComment($postId, $author, $content, $commentId, $depth, $moderation){
        $db = new Database();
        $db = $db->connect();
        $author = Helper::validateContent($author);
        $content = Helper::validateContent($content);

        if($moderation){
            $content = '[Sys]
                            Ce contenu a été modéré automatiquement car pourrait potentiellement contenir du code malintentionné
                        [Sys] <br>'.$content;
        }

        $statement = $db->prepare("INSERT INTO opc_blog_comment (post_id, comment_parent, depth, author, comment, comment_date, moderation) VALUES (?,?,?,?,?,?,?)");
        $dateOfCom = date("Y-m-d H:i:s");
        $statement->execute(array($postId, $commentId, $depth,$author, $content, $dateOfCom, $moderation));
        
        Post::addCommentcounter($postId);
        unset($db);
    }
}