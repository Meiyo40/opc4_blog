<?php

namespace entity;

class User{
    private $id;
    private $name;
    private $hash_password;
    private $rank;
    private $auth;

    public function getRank(){
        return $this->rank;
    }

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getAuth(){
        return $this->auth;
    }

    public function setId($newId){
        $this->id = $newId;
    }

    public function setName($newName){
        $this->name = $newName;
    }

    public function setAuth($newAuth){
        $this->auth = $newAuth;
    }

    public function setRank($newRank){
        $this->rank = $newRank;
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

}