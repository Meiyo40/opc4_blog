<?php

namespace services;
require_once __DIR__.'/../../vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php';

class Helper{
    public static function validateContent($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    public static function HTMLpurifier($data){
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'ISO-8859-1'); 
        $config->set('HTML.Allowed', 'a[href],i,b,img[src],font[style|size],ol,ul,li,br'); 
        $purifier = new \HTMLPurifier($config);

        $data = $purifier->purify($data);
        return $data;
    }

    public static function deleteJScode($content){
        $content = Helper::HTMLpurifier($content);
        $pattern = array('script', 'javascript');
        $content = str_replace( $pattern, '', $content, $count);
        return array( 'content' => $content, 
                    'nbCharJSReplace' => $count);
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