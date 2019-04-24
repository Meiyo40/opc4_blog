<?php

class Database{
    private static $db_user = 'OPC4_PHP_BLOG';
    private static $db_pwd = 'azerty';
    private static $dsn = 'mysql:host=localhost;dbname=OPC4_blog';
    private static $connection_db = null;

    public static function connect(){
        try {
            self::$connection_db = new PDO(self::$dsn, self::$db_user, self::$db_pwd);
        }
        catch (PDOException $e)
        {
            die($e->getMessage());
        }
        return self::$connection_db;
    }

    public static function disconnect(){
        self::$connection_db = null;
    }


}