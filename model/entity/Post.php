<?php

namespace entity;

use services\Database;

class Post{
    private $id;
    private $author;
    private $title;
    private $content;
    private $date;

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
        $statement = $db->prepare("INSERT INTO opc_blog_posts (author, content, title, date) VALUES (?,?,?,?)");        
        $statement->execute(array($this->author, $this->content, $this->title, $this->date));
        Database::disconnect();
    }

    public function deletePost(){

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