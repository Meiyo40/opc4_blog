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
    private $img_key;
    private $img_ext;

    public function getContent(){
        return $this->content;
    }

    public function getTitle(){
        return $this->title;
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

    public function setImg($img_name){
        $this->image = $img_name;
        $this->setImgUniqueId();
    }

    private function createImgFile($imgId){
        $path = 'resources/img/'.$imgId;
        mkdir($path, 0777, true);
        $path = $path."/".$imgId.".".pathinfo($this->image, PATHINFO_EXTENSION)."";
        $move = move_uploaded_file($_FILES['image']['tmp_name'], $path);
        $err = $this->date.": Erreur lors du deplacement de l'image <strong>name</strong>= [".$this->image."] <strong>path</strong>= [".$path."]<br>";
        if(!$move){
            file_put_contents('debug.html', $err, FILE_APPEND);
        }
        else{
            $this->img_key = $imgId;
            $this->img_ext = pathinfo($this->image, PATHINFO_EXTENSION);
        }
    }

    public function setImgUniqueId(){
        $newImgId = md5(uniqid(rand(), true));
        $this->uniqueId_image = $this->ImgIdExist($newImgId);
    }

    private function ImgIdExist($imgId){
        $db = Database::connect();
        $statement = $db->prepare("SELECT img_key WHERE img_key = ?");        
        $statement->execute(array($imgId));
        $imgList = $statement->fetchAll();
        Database::disconnect();

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

    public function addPost(){
        $db = Database::connect();
        $statement = $db->prepare("INSERT INTO opc_blog_posts (author, content, title, date, img_content, img_ext) VALUES (?,?,?,?,?,?)");        
        $statement->execute(array($this->author, $this->content, $this->title, $this->date, $this->img_key, $this->img_ext));
        Database::disconnect();
    }

    public function updatePost(){
        $db = Database::connect();
        $statement = $db->prepare("UPDATE `opc_blog_posts` SET `author` = ?, `content` = ?, `title` = ? WHERE `id` = ? ");
        $statement->execute(array($this->author, $this->content, $this->title, $this->id));
        Database::disconnect();
    }

    public function deletePost(){
        $db = Database::connect();
        $statement = $db->prepare("DELETE FROM `opc_blog_posts` WHERE `id` = ? ");
        $statement->execute(array($this->id));
        Database::disconnect();
    }

    public static function addCommentcounter($postId){
        $db = Database::connect();
        $statement = $db->prepare("UPDATE `opc_blog_posts` SET `nb_comments` = `nb_comments` + 1 WHERE `id` = ? ");
        $statement->execute(array($postId));
        Database::disconnect();
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
}