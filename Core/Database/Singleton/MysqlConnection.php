<?php
namespace Database\Singleton;

use PDO;

class MysqlConnection {
    public static $database=null;

    private function __construct(){}

    private function __clone(){}

    /**
     * Get Instance of PDO object
     *
     * @return \PDO
     */
    public static function getInstance():PDO
    {
        if(self::$database==null){
            self::$database=new PDO($_ENV['DB_DNS'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']) ;
        }
        return self::$database;
    }
}