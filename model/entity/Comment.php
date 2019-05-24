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
    private $moderation;

    public function __construct($id, $post_id, $comment_parent, $depth, $author, $comment, $comment_date, $report, $moderation = false){
        $this->id = $id;
        $this->post_id = $post_id;
        $this->comment_parent = $comment_parent;
        $this->depth = $depth;
        $this->author = $author;
        $this->comment = $comment;
        $this->comment_date = $comment_date;
        $this->report = $report;
        $this->moderation = $moderation;
    }

    public function addNewComment(){
        if($this->depth == 0){
            $db = new Database();
$db = $db->connect();

            $statement = $db->prepare("INSERT INTO opc_blog_comment (post_id, author, depth, comment, comment_date) VALUES (?,?,?,?,?)");
            $statement->execute(array($this->commentPost, $this->author, $this->depth, $this->content, $this->date));

            $db->disconnect();
        }
        else{
            $db = new Database();
$db = $db->connect();

            $statement = $db->prepare("INSERT INTO opc_blog_comment (post_id, comment_parent, depth, author, comment, comment_date) VALUES (?,?,?,?,?,?)");
            $statement->execute(array($this->commentPost, $this->commentParent, $this->depth, $this->author, $this->content, $this->date));
            
            //$this->addCommentcounter($postId);
            $db->disconnect();
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

    public function setComment($newContent){
        $this->comment = $newContent;
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

    public function setModeration($newModeration){
        $this->moderation = $newModeration;
    }

    public function getModeration(){
        return $this->moderation;
    }

    public function getComment(){
        return html_entity_decode(htmlspecialchars_decode($this->comment));
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
            $db = new Database();
$db = $db->connect();
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
                $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "entity\Comment", array("id", "post_id", "comment_parent", "depth", "author", "comment", "comment_date", "report", "moderation"));
                $db->disconnect();
                return $obj = $statement->fetchAll();                     
            }

        }
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public static function getModeratedComments($limit = 0){
        try { 
            $db = new Database();
$db = $db->connect();
            $db->exec("set names utf8");
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            if($limit == 0){
                $statement = $db->prepare("SELECT*FROM opc_blog_comment WHERE moderation > 0 ORDER BY comment_date DESC");
            }
            else{
                $statement = $db->prepare("SELECT*FROM opc_blog_comment WHERE moderation > 0 ORDER BY comment_date DESC LIMIT ".$limit);
            }

            $statement->execute();

            

            $Count = $statement->rowCount(); 
            if ($Count  > 0){
                $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "entity\Comment", array("id", "post_id", "comment_parent", "depth", "author", "comment", "comment_date", "report", "moderation"));
                $db->disconnect();
                return $obj = $statement->fetchAll();                     
            }

        }
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function reportComment(){
        $db = new Database();
$db = $db->connect();
        $statement = $db->prepare("UPDATE `opc_blog_comment` SET `report` = `report` + 1 WHERE `id` = ? ");
        $statement->execute(array($this->id));
        $db->disconnect();
    }

    public static function getAllComments($limit = 0){    
        try { 
            $db = new Database();
$db = $db->connect();
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
                $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "entity\Comment", array("id", "post_id", "comment_parent", "depth", "author", "comment", "comment_date", "report", "moderation"));
                $db->disconnect();

                $obj = $statement->fetchAll();

                return  $obj;                  
            }

        }
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function deleteCom(){
        $db = new Database();
$db = $db->connect();
        $statement = $db->prepare("DELETE FROM `opc_blog_comment` WHERE `id` = ? ");
        $statement->execute(array($this->id));
        $db->disconnect();
    }

    public static function initComment($id){
        $db = new Database();
$db = $db->connect();
        $statement = $db->prepare("SELECT*FROM opc_blog_comment WHERE id = ?");
        $statement->execute(array($id));

        $obj = $statement->fetch();
        
        $obj = new Comment($obj['id'], $obj['post_id'], $obj['comment_parent'], $obj['depth'], $obj['author'], $obj['comment'], $obj['comment_date'], $obj['report'], $obj['moderation']);
        
        return $obj;

        $db->disconnect();
    }

    public function update($mode){
        $db = new Database();
$db = $db->connect();
        
        switch($mode){

            case 'all':
                $statement = $db->prepare("UPDATE opc_blog_comment
                SET comment = ?,
                    author = ?,
                    report = ?,
                    moderation = ?
                WHERE id = ?");
                $statement->execute(array($this->comment, $this->author, $this->report, $this->moderation, $this->id));
                break;

            case 'moderation':
                $statement = $db->prepare("UPDATE opc_blog_comment
                SET moderation = ?
                WHERE id = ?");
                $statement->execute(array($this->moderation, $this->id));
                break;
        }
        $db->disconnect();
    }
}