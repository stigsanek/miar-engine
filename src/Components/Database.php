<?php

namespace App\Components;

use PDO;

/**
 * Database component
 */
class Database
{
    /**
     * Returns the connection to the database
     * @return object
     */
    public static function getDataBase()
    {
        $option = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        return new PDO($_ENV['DB_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $option);
    }
}
