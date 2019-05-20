<?php

namespace services;

use entity\Comment;
use entity\Post;
use entity\User;
use services\Database;

class DAO{

    public function getAllCommentsPost($postId){    
        try { 
            $db = Database::connect(); 
            $db->exec("set names utf8");
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $statement = $db->prepare("SELECT*FROM opc_blog_comment WHERE post_id = ?");
            $statement->execute(array($postId));

            $Count = $statement->rowCount(); 
            if ($Count  > 0){
                $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "entity\Comment", array("id", "post_id", "comment_parent", "depth", "author", "comment", "comment_date", "report"));
                return $obj = $statement->fetchAll(); 

                    Database::disconnect();
            }

        }
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function getPosts(){
        try { 
            $db = Database::connect(); 
            $db->exec("set names utf8");
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $statement = $db->prepare("SELECT*FROM opc_blog_comment");
            $statement->execute();

            $Count = $statement->rowCount(); 
            if ($Count  > 0){
                $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "entity\Post", array("id", "author", "content", "date", "title", "nb_comments", "img_key", "img_ext"));
                return $obj = $statement->fetchAll(); 

                    Database::disconnect();
            }

        }
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

}
