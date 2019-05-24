<?php

namespace services;

use entity\Comment;
use entity\Post;
use entity\User;
use services\Database;

class DAO{

    public function getAllCommentsPost($postId = 0, $limit = 0, $countRows = false){    
        try { 
            $db = new Database();
$db = $db->connect();
            $db->exec("set names utf8");
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            if($postId > 0){
                $statement = $db->prepare("SELECT*FROM opc_blog_comment WHERE post_id = ?");
                $statement->execute(array($postId));
            }
            else{
                $statement = $db->prepare("SELECT*FROM opc_blog_comment ORDER BY comment_date DESC LIMIT ".$limit);
                $statement->execute();
            }

            $Count = $statement->rowCount(); 
            if ($Count  > 0){
                if(!$countRows){
                    $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "entity\Comment", array("id", "post_id", "comment_parent", "depth", "author", "comment", "comment_date", "report", "moderation"));
                    $obj = $statement->fetchAll(); 

                    for($i = 0; $i < sizeof($obj); $i++){
                        if($obj[$i]->getModeration()){
                            $moderatedComment = "<strong style='color:red'>Ce commentaire a été modéré car son contenu ne respectait pas le règlement de ce site internet.</strong>";
                            $obj[$i]->setComment($moderatedComment);
                        }
                    }
                }
                else{
                    file_put_contents('debug.html', $Count);
                    $obj = $Count;
                }
                
                return $obj; 

                    unset($db);
            }
            elseif($countRows){
                return 0;
            }


        }
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function getPosts( $limit = 0 ){
        try { 
            $db = new Database();
            $db = $db->connect();
            $db->exec("set names utf8");
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            if($limit > 0){
                $statement = $db->prepare("SELECT*FROM opc_blog_posts ORDER BY date DESC LIMIT ".$limit);
                $statement->execute();
            }
            else{
                $statement = $db->prepare("SELECT*FROM opc_blog_posts ORDER BY date DESC");
                $statement->execute();
            }            

            $Count = $statement->rowCount(); 
            if ($Count  > 0){
                $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "entity\Post", array("id", "author", "content", "date", "title", "nb_comments", "img_key", "img_ext"));
                return $obj = $statement->fetchAll(); 

                    unset($db);
            }

        }
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function getAllUsers($limit = 0){
        try { 
            $db = new Database();
$db = $db->connect();
            $db->exec("set names utf8");
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            if($limit > 0){
                $statement = $db->prepare("SELECT id, name, last_connexion, rank, comments, articles, mail FROM opc_blog_users ORDER BY last_connexion DESC LIMIT ".$limit);
                $statement->execute();
            }
            else{
                $statement = $db->prepare("SELECT*FROM opc_blog_users ORDER BY last_connexion DESC");
                $statement->execute();
            }            

            $Count = $statement->rowCount(); 
            if ($Count  > 0){
                return $users = $statement->fetchAll(); 
                    unset($db);
            }

        }
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

}
