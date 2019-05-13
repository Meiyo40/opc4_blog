<?php
namespace model\entity;

class Comment{
    private $content;
    private $author;
    private $date;
    private $report;
    private $commentPost;
    private $commentParent;
    private $id;
    private $depth;

    public function __construct($author, $content, $commentPost, $commentParent, $depth){
        $this->content = preg_replace("/\s|&nbsp;/",'',$content);;
        $this->author = $author;
        $this->date = date("Y-m-d H:i:s");;
        $this->commentParent = $commentParent;
        $this->commentPost = $commentPost;
        $this->$depth = $depth;
    }

    public static function initComment(){
        $db = Database::connect();
        $statement = $db->prepare('SELECT id, post_id, author, comment_parent, depth, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin\') AS comment_date 
        FROM opc_blog_comment 
        WHERE post_id = ?');

        $statement->execute(array($postId));
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

    public function getContent(){
        return $this->content;
    }
}