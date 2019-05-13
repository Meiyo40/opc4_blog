<?php
namespace entity;

use services\Database;

class Comment{
    private $content;
    private $author;
    private $date;
    private $report;
    private $parentPost;
    private $parentComment;
    private $id;
    private $depth;

    public function __construct($author, $content, $commentPost, $commentParent, $depth){
        $this->content = preg_replace("/\s|&nbsp;/",'',$content);;
        $this->author = $author;
        $this->date = date("Y-m-d H:i:s");;
        $this->parentPost = $commentParent;
        $this->parentComment = $commentPost;
        $this->$depth = $depth;
    }

    public static function initComment(){
        $db = Database::connect();
        $statement = $db->prepare('SELECT id, post_id, author, comment_parent, depth, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin\') AS comment_date 
        FROM opc_blog_comment 
        WHERE post_id = ?');

        $statement->execute(array($parentComment));
        $comments = $statement->fetchAll();
        
        Database::disconnect();

        $obj = new Comment($comments['id'], $comments['author'], $comments['comment'], $comments['post_id'], $comments['comment_parent'], $comments['depth'], $comments['comment_date'], $comments['report']);
        
        return $comments;
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
        SET comment = $this->content,
            author = $this->author,
            report = $this->report
        WHERE id = $this->id");

        $statement->execute();
        $comment = $statement->fetch();

        Database::disconnect();
        return $comment; 
    }

    public function setContent($newContent){
        $this->content = $newContent;
        $this->update();
    }

    public function setAuthor($newAuthor){
        $this->author =  $newAuthor;
    }

    public function setDate($newDate){
        $this->date = $newDate;
    }

    public function setParentPost($newParentPost){
       $this->parentPost = $newParentPost; 
    }

    public function setParentComment($newParentCom){
        $this->parentCom = $newParentCom;
    }

    public function setId($newId){
        $this->id = $newId;
    }

    public function setDepth($newDepth){
        $this->depth = $newDepth;
    }

    public function getContent(){
        return $this->content;
    }

    public function getAuthor(){
        return $this->author;
    }

    public function getDate(){
        return $this->date;
    }

    public function getParentPost(){
       return $this->parentPost; 
    }

    public function getParentComment(){
        return $this->parentCom;
    }

    public function getId(){
        return $this->id;
    }

    public function getDepth(){
        return $this->depth;
    }
}