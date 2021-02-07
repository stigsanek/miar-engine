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
        $parameters = include ROOT . '/config/database.php';

        $option = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];

        $db = new PDO($parameters['dsn'], $parameters['user'], $parameters['password'], $option);

        return $db;
    }
}
