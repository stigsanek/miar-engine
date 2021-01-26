<?php

namespace App\Components;

use PDO;

/**
 * Компонент базы данных
 */
class Database
{
    /**
     * Возвращает подключение к БД
     * @return object
     */
    public static function getDataBase()
    {
        $env = include ROOT . '/config/.env.php';

        $parameters = $env['db'];

        $option = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];

        $db = new PDO($parameters['dsn'], $parameters['user'], $parameters['password'], $option);

        return $db;
    }
}
