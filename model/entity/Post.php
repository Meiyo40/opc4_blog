<?php

namespace entity;

use services\Database;
use services\Helper;

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
    private $isHide;
    private $IMG_MAXSIZE = 10485760;

    public function __construct($id, $author, $content, $date, $title, $nb_comments, $img_key = '', $img_ext = '', $isHide = '0')
    {
        $this->id = $id;
        $this->author = $author;
        $this->content = $content;
        $this->date = $date;
        $this->title = $title;
        $this->$nb_comments = $nb_comments;
        $this->img_key = $img_key;
        $this->img_ext = $img_ext;
        $this->isHide = $isHide;
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

    public function getHideState(){
        return $this->isHide;
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

    public function setHideState($state){
        $this->isHide = $state;
    }

    public function IncrementNb_comments(){
        $this->nb_comments += 1;
    }


    public function addPost(){
        $db = new Database();
        $db = $db->connect();
        $statement = $db->prepare("INSERT INTO opc_blog_posts (author, content, title, date, img_key, img_ext) VALUES (?,?,?,?,?,?)");        
        $statement->execute(array($this->author, $this->content, $this->title, $this->date, $this->img_key, $this->img_ext));
        unset($db);
    }

    static function setNewPost($title, $content, $author, $img_name = null){
        
        $pattern = '/(gif|png|jpeg|jpg)$/i';
        $extension = pathinfo($img_name, PATHINFO_EXTENSION);
        $extension = preg_match($pattern, $extension);

        $author = Helper::validateContent($author);
        $content = Helper::validateContent($content);
        $title = Helper::validateContent($title);
        $date = date("Y-m-d H:i:s");
        $nb_comments = 0;  

        $post = new Post(null, $author, $content, $date, $title, $nb_comments);  

        if($_FILES['image']['size'] < $post->IMG_MAXSIZE && $extension){                              
            
            $post->setImg($img_name);
            $post->addPost();
        }
        else{
            if(!$extension){
                $extension = 'Mauvaise extension de fichier : '.$extension.'<br>';
            }
            elseif($_FILES['image']['size'] > $post->IMG_MAXSIZE){
                $file_size = 'Taille du fichier trop importante <br>';
            }
            return false;
        }
        
    }

    public function updatePost(){
        $db = new Database();
        $db = $db->connect();
        if($this->image != null){

            $statement = $db->prepare("UPDATE `opc_blog_posts` SET `author` = ?, `content` = ?, `title` = ?, `img_key` = ?, `img_ext` = ?, `isHide` = ?  WHERE `id` = ? ");
            $statement->execute(array($this->author, $this->content, $this->title, $this->img_key, $this->img_ext, $this->isHide, $this->id));
        }
        else{

            $statement = $db->prepare("UPDATE `opc_blog_posts` SET `author` = ?, `content` = ?, `title` = ?, `isHide` = ?  WHERE `id` = ? ");
            $statement->execute(array($this->author, $this->content, $this->title, $this->isHide, $this->id));
        }
        
        unset($db);
    }

    public function deletePost(){
        $filename = "./resources/img/{$this->img_key}.{$this->img_ext}";
        unlink($filename);
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

        $obj = new Post($obj['id'], $obj['author'], $obj['content'], $obj['date'], $obj['title'], $obj['nb_comments'], $obj['img_key'], $obj['img_ext'], $obj['isHide']);
        unset($db);
        
        return $obj;

        
    }

    public static function postExist($title){
        $db = new Database();
        $db = $db->connect();
        $statement = $db->prepare("SELECT*FROM opc_blog_posts WHERE title = ?");
        $statement->execute(array($title));

        $result = $statement->fetch();

        if($result){
            return true;
        }
        else{
            return false;
        }
    }
}