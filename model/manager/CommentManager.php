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
        $statement = $db->prepare("INSERT INTO opc_blog_comment (post_id, comment_parent, depth, author, comment, comment_date) VALUES (?,?,?,?,?,?)");
        $dateOfCom = date("Y-m-d H:i:s");
        $statement->execute(array($postId, $commentId, $depth,$author, $content, $dateOfCom));
        
        Post::addCommentcounter($postId);
        Database::disconnect();
    }
}