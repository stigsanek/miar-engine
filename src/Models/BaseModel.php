<?php

namespace App\Models;

use App\Components\Database;

/**
 * Базовая модель
 */
class BaseModel
{
    /**
     * Статус выполнения операции
     */
    public $isError;

    /**
     * Ошибки выполнения операции
     */
    protected $errors = [];

    /**
     * Подключение к БД
     */
    protected $db;

    /**
     * Имя таблицы в БД
     */
    protected $table;

    /**
     * Подготовленный запрос в БД
     */
    protected $prepQuery;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->db = Database::getDataBase();
    }

    /**
     * Возвращает ошибки выполнения операции
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Устанавливает статус выполнения запроса
     * @param mixed $response - ответ БД
     */
    protected function setQueryState($response)
    {
        if (empty($response)) {
            $this->isError = true;
            $this->errors['queryState'] = $this->prepQuery->errorInfo();
        } else {
            $this->isError = false;
        }
    }
}
