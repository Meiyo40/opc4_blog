<?php

namespace services;

class Helper{
    public static function validateContent($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function getNbPage(){
        
    }
}