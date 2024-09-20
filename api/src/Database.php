<?php

namespace KnowledgeCity;
use PDO;
use PDOException;
class Database
{
    private static ?PDO $instance = null;

    private function __construct() { }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            
            try{
                
                $dsn = 'mysql:host=database.cc.localhost;dbname=course_catalog;charset=utf8';
                $username = 'test_user';
                $password = 'test_password';
                self::$instance = new PDO($dsn, $username, $password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            }catch(PDOException $e){
                die( "Connection failed: " . $e->getMessage());
            }

        }

        return self::$instance;
    }
}
