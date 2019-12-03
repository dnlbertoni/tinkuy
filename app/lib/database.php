<?php
namespace App\Lib;

use PDO;

class Database
{
    public static function StartUp()
    {
        $DSN = 'mysql:host='.getenv(DB_HOST).';dbname='.getenv(DB_NAME).';charset=utf8';
        //echo $DSN,getenv(DB_USER), getenv(DB_PASS); die();
        $pdo = new PDO($DSN, getenv(DB_USER), getenv(DB_PASS));
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        
        return $pdo;
    }
}