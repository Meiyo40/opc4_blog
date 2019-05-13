<?php

namespace model\entity;

class Post{
    public $id;
    public $author;
    public $title;
    public $content;

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

    }

    public function setContent(){

    }

    public function setTitle(){

    }

    public function setAuthor(){

    }

    public function addPost(){

    }

    public function deletePost(){

    }
}