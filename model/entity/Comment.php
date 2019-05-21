<?php
namespace entity;

use services\Database;

class Comment{
    private $id;
    private $post_id;
    private $comment_parent;
    private $depth;
    private $author;
    private $comment;
    private $comment_date;
    private $report;


    public function __construct($id, $post_id, $comment_parent, $depth, $author, $comment, $comment_date, $report){
        $this->id = $id;
        $this->post_id = $post_id;
        $this->comment_parent = $comment_parent;
        $this->depth = $depth;
        $this->author = $author;
        $this->comment = $comment;
        $this->comment_date = $comment_date;
        $this->report = $report;
    }

    public function addNewComment(){
        if($this->depth == 0){
            $db = Database::connect();

            $statement = $db->prepare("INSERT INTO opc_blog_comment (post_id, author, depth, comment, comment_date) VALUES (?,?,?,?,?)");
            $statement->execute(array($this->commentPost, $this->author, $this->depth, $this->content, $this->date));

            Database::disconnect();
        }
        else{
            $db = Database::connect();

            $statement = $db->prepare("INSERT INTO opc_blog_comment (post_id, comment_parent, depth, author, comment, comment_date) VALUES (?,?,?,?,?,?)");
            $statement->execute(array($this->commentPost, $this->commentParent, $this->depth, $this->author, $this->content, $this->date));
            
            //$this->addCommentcounter($postId);
            Database::disconnect();
        }
    }

    public function properties(){ 
        return get_object_vars($this); 
    }

    public function properties_names(){
        return array_keys(get_object_vars($this)); 
    }

    public function to_string() { 
        return "id : $this->id, author : $this->author, content : $this->content, date : $this->date, report : $this->report"; 
    }

    public function update(){
        $db = Database::connect();
        $statement = $db->prepare("UPDATE opc_blog_comment
        SET comment = $this->comment,
            author = $this->author,
            report = $this->report
        WHERE id = $this->id");

        $statement->execute();
        $comment = $statement->fetch();

        Database::disconnect();
        return $comment; 
    }

    public function setComment($newContent){
        $this->comment = $newContent;
        $this->update();
    }

    public function setAuthor($newAuthor){
        $this->author =  $newAuthor;
    }

    public function setDate($newDate){
        $this->comment_date = $newDate;
    }

    public function setParentPost($newParentPost){
       $this->post_id = $newParentPost; 
    }

    public function setParentComment($newParentCom){
        $this->comment_parent = $newParentCom;
    }

    public function setId($newId){
        $this->id = $newId;
    }

    public function setDepth($newDepth){
        $this->depth = $newDepth;
    }

    public function getComment(){
        return $this->comment;
    }

    public function getAuthor(){
        return $this->author;
    }

    public function getComment_date(){
        return $this->comment_date;
    }

    public function getPost_id(){
       return $this->post_id; 
    }

    public function getComment_parent(){
        return $this->comment_parent;
    }

    public function getId(){
        return $this->id;
    }

    public function getDepth(){
        return $this->depth;
    }

    public function getReport(){
        return $this->report;
    }

    public static function getReportedComments($limit = 0){
        try { 
            $db = Database::connect(); 
            $db->exec("set names utf8");
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            if($limit == 0){
                $statement = $db->prepare("SELECT*FROM opc_blog_comment WHERE report > 0 ORDER BY report DESC");
            }
            else{
                $statement = $db->prepare("SELECT*FROM opc_blog_comment WHERE report > 0 ORDER BY report DESC LIMIT ".$limit);
            }

            $statement->execute();

            

            $Count = $statement->rowCount(); 
            if ($Count  > 0){
                $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "entity\Comment", array("id", "post_id", "comment_parent", "depth", "author", "comment", "comment_date", "report"));
                Database::disconnect();
                return $obj = $statement->fetchAll();                     
            }

        }
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function reportComment(){
        $db = Database::connect();
        $statement = $db->prepare("UPDATE `opc_blog_comment` SET `report` = `report` + 1 WHERE `id` = ? ");
        $statement->execute(array($this->id));
        Database::disconnect();
    }

    public static function getAllComments($limit = 0){    
        try { 
            $db = Database::connect(); 
            $db->exec("set names utf8");
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            if($limit == 0){
                $statement = $db->prepare("SELECT*FROM opc_blog_comment ORDER BY comment_date DESC");
            }
            else{
                $statement = $db->prepare("SELECT*FROM opc_blog_comment ORDER BY comment_date DESC LIMIT ".$limit);
            }

            $statement->execute();

            

            $Count = $statement->rowCount(); 
            if ($Count  > 0){
                $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "entity\Comment", array("id", "post_id", "comment_parent", "depth", "author", "comment", "comment_date", "report"));
                Database::disconnect();
                return $obj = $statement->fetchAll();                     
            }

        }
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public static function deleteCom($id){
        $db = Database::connect();
        $statement = $db->prepare("DELETE FROM `opc_blog_comment` WHERE `id` = ? ");
        $statement->execute(array($id));
        Database::disconnect();
    }

    public static function initComment($id){
        $db = Database::connect();
        $statement = $db->prepare("SELECT*FROM opc_blog_comment WHERE id = ?");
        $statement->execute(array($id));

        $obj = $statement->fetch();
        
        $obj = new Comment($obj['id'], $obj['post_id'], $obj['comment_parent'], $obj['depth'], $obj['author'], $obj['comment'], $obj['comment_date'], $obj['report']);
        
        return $obj;

        Database::disconnect();
    }
}