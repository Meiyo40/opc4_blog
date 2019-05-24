<?php

namespace entity;

use services\Database;

class Post{
    private $id;
    private $author;
    private $title;
    private $content;
    private $date;
    private $image;
    private $nb_comments;
    private $img_key;
    private $img_ext;

    public function __construct($id, $author, $content, $date, $title, $nb_comments, $img_key = '', $img_ext = '')
    {
        $this->id = $id;
        $this->author = $author;
        $this->content = $content;
        $this->date = $date;
        $this->title = $title;
        $this->$nb_comments = $nb_comments;
        $this->img_key = $img_key;
        $this->img_ext = $img_ext;
    }

    public function getContent(){
        return html_entity_decode(htmlspecialchars_decode($this->content));
    }

    public function getTitle(){
        return html_entity_decode(htmlspecialchars_decode($this->title));
    }

    public function getId(){
        return $this->id;
    }

    public function getAuthor(){
        return $this->author;
    }

    public function getDate(){
        return $this->date;
    }

    public function getImg_key(){
        return $this->img_key;
    }

    public function getImg_ext(){
        return $this->img_ext;
    }

    public function getNb_comments(){
        return $this->nb_comments;
    }

    public function setImg($img_name){
        $this->image = $img_name;
        $this->setImgUniqueId();
    }

    private function createImgFile($imgId){
        $path = 'resources/img';
        mkdir($path, 0777, true);
        $this->toFileName($this->title);
        $path = $path."/".$this->img_key.'.'.pathinfo($this->image, PATHINFO_EXTENSION);
        $move = move_uploaded_file($_FILES['image']['tmp_name'], $path);
        if(!$move){
            $err = $this->date.": Erreur lors du deplacement de l'image <strong>name</strong>= [".$this->image."] <strong>path</strong>= [".$path."]<br>";
            file_put_contents('debug.html', $err, FILE_APPEND);
        }
        else{                
            $this->img_ext = pathinfo($this->image, PATHINFO_EXTENSION);
        }
    }
    
    private function toFileName($fileName){
        $string = preg_replace("/\s+/", '', $fileName);
        $this->img_key = $string;
    }

    public function setImgUniqueId(){
        $newImgId = md5(uniqid(rand(), true));
        $this->uniqueId_image = $this->ImgIdExist($newImgId);
    }

    private function ImgIdExist($imgId){
        $db = new Database();
        $db = $db->connect();
        $statement = $db->prepare("SELECT img_key FROM opc_blog_posts WHERE img_key = ?");        
        $statement->execute(array($imgId));
        $imgList = $statement->fetchAll();
        unset($db);

        if(sizeof($imgList)){
            $this->setImgUniqueId();
        }
        else{
            $this->createImgFile($imgId);
            return $imgId;            
        }
    }

    public function setContent($newContent){
        $this->content = $newContent;
    }

    public function setTitle($newTitle){
        $this->title = $newTitle;
    }

    public function setAuthor($newAuthor){
        $this->author = $newAuthor;
    }

    public function setDate($newDate){
        $this->date = $newDate;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setNb_comments($nb){
        $this->nb_comments = $nb;
    }

    public function IncrementNb_comments(){
        $this->nb_comments += 1;
    }


    public function addPost(){
        $db = new Database();
$db = $db->connect();
        $statement = $db->prepare("INSERT INTO opc_blog_posts (author, content, title, date, img_key, img_ext) VALUES (?,?,?,?,?,?)");        
        $statement->execute(array($this->author, $this->content, $this->title, $this->date, $this->img_key, $this->img_ext));
        $err = $this->author."<br>".$this->content."<br>".$this->title."<br>".$this->date."<br>".$this->img_key."<br>".$this->img_ext;
            file_put_contents('debug.html', $err, FILE_APPEND);
        unset($db);
    }

    public function updatePost(){
        $db = new Database();
$db = $db->connect();
        if($this->image != null){

            $statement = $db->prepare("UPDATE `opc_blog_posts` SET `author` = ?, `content` = ?, `title` = ?, `img_key` = ?, `img_ext` = ?  WHERE `id` = ? ");
            $statement->execute(array($this->author, $this->content, $this->title, $this->img_key, $this->img_ext, $this->id));
        }
        else{

            $statement = $db->prepare("UPDATE `opc_blog_posts` SET `author` = ?, `content` = ?, `title` = ?  WHERE `id` = ? ");
            $statement->execute(array($this->author, $this->content, $this->title, $this->id));
        }
        
        unset($db);
    }

    public function deletePost(){
        $db = new Database();
$db = $db->connect();
        $statement = $db->prepare("DELETE FROM `opc_blog_posts` WHERE `id` = ? ");
        $statement->execute(array($this->id));
        unset($db);
    }

    public static function addCommentcounter($postId){
        $db = new Database();
$db = $db->connect();
        $statement = $db->prepare("UPDATE `opc_blog_posts` SET `nb_comments` = `nb_comments` + 1 WHERE `id` = ? ");
        $statement->execute(array($postId));
        unset($db);
    }

    public function properties(){ 
        return get_object_vars($this); 
    }

    public function properties_names(){
        return array_keys(get_object_vars($this)); 
    }

    public function to_string() { 
        return "id : $this->id, author : $this->author, title : $this->title, content : $this->content, date : $this->date"; 
    }

    public static function initPost($id){
        $db = new Database();
$db = $db->connect();
        $statement = $db->prepare("SELECT*FROM opc_blog_posts WHERE id = ?");
        $statement->execute(array($id));

        $obj = $statement->fetch();

        $obj = new Post($obj['id'], $obj['author'], $obj['content'], $obj['date'], $obj['title'], $obj['nb_comments'], $obj['img_key'], $obj['img_ext']);
        
        return $obj;

        unset($db);
    }
}