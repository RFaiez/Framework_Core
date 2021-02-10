<?php

namespace rfaiez\framework_core\Database\Singleton;

use PDO;

class MysqlConnection
{
    public static $database = null;

    /**
     * Not authorized to create instance with new keyword.
     */
    private function __construct()
    {
    }

    /**
     * Not authorized to create clone with clone keyword.
     */
    private function __clone()
    {
    }

    /**
     * Get Instance of PDO object.
     *
     * @return \PDO
     */
    public static function getInstance(): PDO
    {
        if (null == self::$database) {
            self::$database = new PDO($_ENV['DB_DNS'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
        }

        return self::$database;
    }
}
