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
        
        $this->childControl($commentId);
        $db = Database::connect();
        $statement = $db->prepare("DELETE FROM `opc_blog_posts` WHERE `id` = ? ");
        $statement->execute(array($commentId));
        Database::disconnect();
        
    }

    public function childControl($commentId){
        
        if(is_array($commentId)){
            for($i = 0; $i < sizeof($commentId); $i++){
                $parent = $this->hasParent($commentId[$i]);
                $childrens = $this->hasChildren($commentId[$i]);
                if($parent && $childrens){                    
                    if($childrens){
                        $this->setNewParent($childrens, $parent);
                    }
                }
            }
        }
        else{
            
            $parent = $this->hasParent($commentId);
            file_put_contents('debug.html', 'TEST');
            $childrens = $this->hasChildren($commentId);
            
            if($parent && $childrens){    
                            
                $this->setNewParent($childrens, $parent);
            }
            else{
                
                $this->setNewparent($childrens, null);
                
            }
        }
    }

    public function setNewparent($comments, $newParent){
        if(sizeof($comments) == 1){
            $db = Database::connect();
            $statement = $db->prepare("UPDATE `opc_blog_comment` SET `comment_parent` = ?, `depth` = `depth`-1 WHERE `id` = ? ");
            $statement->execute(array($comments['id']));
            Database::disconnect();
        }
        else{
            $ids = "id = ".$comments[0]['id'];
            for($i = 1; $i < sizeof($comments); $i++){
                $ids = $ids." OR id = ".$comments[$i]->getId();
                $this->childControl($comments[$i]->getId());
            }

            
            $db = Database::connect();
            $statement = $db->prepare("UPDATE `opc_blog_comment` SET `comment_parent` = ?, `depth` = `depth`-1 WHERE ?");
            $statement->execute(array($ids));
            Database::disconnect();

            
            $this->childControl($comments);
        }
    }

    public function hasChildren($parent){
        $db = Database::connect();
        $statement = $db->prepare('SELECT*FROM opc_blog_comment WHERE comment_parent = ?');

        $statement->execute(array($parent));
        $comments = $statement->fetchAll();

        return $comments;
    }

    public function hasParent($commentId){
        $result = Comment::initComment($commentId);
        if($result['comment_parent']){
            return $result['comment_parent'];
        }
        else{
            return "null";
        }
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
        $statement = $db->prepare('SELECT*FROM opc_blog_comment WHERE report > 0 ORDER BY report DESC');
-
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