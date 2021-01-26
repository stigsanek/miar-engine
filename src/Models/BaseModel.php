<?php

namespace App\Models;

use App\Components\Database;
use App\Components\Session;

/**
 * Базовая модель
 */
class BaseModel
{
    /**
     * Флаг ошибки выполнения запроса к БД
     */
    public $isError;

    /**
     * Ошибки выполнения запроса
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
     * Флаг установки уведомлений в сессию
     */
    protected $isAlert = true;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->db = Database::getDataBase();
    }

    /**
     * Возвращает данные по ошибке выполнения запроса
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Устанавливает уведомление по итогам выполнения запроса
     * @param mixed $response - ответ БД
     */
    protected function setQueryState($response)
    {
        if (empty($response)) {
            $this->isError = true;
            $this->errors = $this->prepQuery->errorInfo();

            if ($this->isAlert) {
                Session::setAlert(
                    'danger',
                    'Не удалось выполнить операцию. ' . $this->errors[2]
                        . ' Код ошибки: ' . $this->errors[0] . '.'
                );
            }
        } else {
            $this->isError = false;

            if ($this->isAlert) {
                Session::setAlert('success', 'Операция успешно выполнена');
            }
        }
    }
}
