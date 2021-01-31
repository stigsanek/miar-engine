<?php

namespace App\Models;

use App\Components\Database;

/**
 * Базовая модель
 */
class BaseModel
{
    /**
     * Флаг ошибки выполнения операции
     * @var bool
     */
    public $isError = false;

    /**
     * Данные об ошибках
     * @var array
     */
    protected $errors = [];

    /**
     * Подключение к БД
     * @var object
     */
    protected $db;

    /**
     * Имя таблицы в БД
     * @var string
     */
    protected $table;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->db = Database::getDataBase();
    }

    /**
     * Возвращает данные об ошибках
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Подготавливает и выполняет запрос
     * @param string $sql - текст запроса
     * @param string $params - параметры запроса
     * @return object
     */
    protected function execQuery(string $sql, array $params = [])
    {
        try {
            $prepQuery = $this->db->prepare($sql);
            $response = $prepQuery->execute($params);

            if (empty($response)) {
                $this->isError = true;
                $this->errors[] = $prepQuery->errorInfo()[2];
            }

            return $prepQuery;
        } catch (\Throwable $th) {
            $this->isError = true;
            $this->errors[] = $th->getMessage();
        }
    }
}
