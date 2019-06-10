<?php

namespace services;

class Helper{
    public static function validateContent($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function getPage(){
        if(isset($_GET['page'])){
            return $_GET['page'];
        }
        else{
           return 1;
        }
    }

    public static function setDescription($post){
        $strLimit = 510;
        $content = $post->getContent();
        $content = strlen($content) > $strLimit ? substr($content,0,$strLimit)."..." : $content;
        return $content;
    }

    public static function getNbPage($comments, $sizePage){
        if(is_array($comments)){
            return ceil(sizeof($comments)/$sizePage);   
        }
        else{
            return 1;
        }    
    }

    public static function getSizePage($comments){
        if(sizeof($comments) < 10){
            return sizeof($comments);
        }
        else{
            return 10;
        }
    }
}