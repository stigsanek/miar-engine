<?php

namespace App\Components;

/**
 * Компонент обработки запросов
 */
class Request
{
    /**
     * Проверяет входящий запрос на тип GIT
     * @return boolean
     */
    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Проверяет входящий запрос на тип POST
     * @return boolean
     */
    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Возвращает GET-параметр запроса
     * @param string $key - ключ параметра в массиве
     * @return string
     */
    public function getParam(string $key)
    {
        return $_GET[$key];
    }

    /**
     * Возвращает POST-параметр запроса
     * @param string $key - ключ параметра в массиве
     * @return string
     */
    public function getPostParam(string $key)
    {
        return $_POST[$key];
    }

    /**
     * Загружает данные в форму
     * @param object $form - форма
     * @param boolean $isTrim - флаг отсечения пробелов по краям
     */
    public function loadData(object $form, $isTrim = true)
    {
        foreach ($form->dataFields as $field) {
            if (isset($_POST[$field])) {
                if ($isTrim) {
                    $form->setFormData($field, trim($_POST[$field]));
                } else {
                    $form->setFormData($field, $_POST[$field]);
                }
            }
        }

        foreach ($form->fileFields as $field) {
            if (isset($_FILES[$field])) {
                $form->setFormFiles($field, $_FILES[$field]);
            }
        }
    }

    /**
     * Проверяет отправку формы
     * @param string $name - имя кнопки отправки в форме
     * @return boolean
     */
    public function isSubmitted(string $name)
    {
        return isset($_POST[$name]);
    }

    /**
     * Возвращает путь маршрута
     * @return string
     */
    public static function getUri()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }
}
